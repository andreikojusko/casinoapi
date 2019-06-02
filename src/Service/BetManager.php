<?php

namespace App\Service;

use App\Entity\BalanceTransactionEntity;
use App\Entity\BetEntity;
use App\Entity\BetSelectionEntity;
use App\Entity\PlayerEntity;
use App\Enum\BetError;
use App\Exception\DataModelException;
use App\Model\AddBet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BetManager
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var ErrorFactory */
    private $errorFactory;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        ValidatorInterface $validator,
        ErrorFactory $errorFactory,
        EntityManagerInterface $em
    ) {
        $this->validator = $validator;
        $this->errorFactory = $errorFactory;
        $this->em = $em;
    }

    public function process(AddBet $model): void
    {
        $errors = $this->validator->validate($model);

        if (\count($errors)) {
            $this->bindViolationsToModel($errors, $model);

            throw new DataModelException($model);
        }

        $player = $this->safeGetPlayer($model);

        $newAmount = $player->getBalance() - $model->stakeAmount;

        if ($newAmount < 0) {
            $model->errors[] = $this->errorFactory->createError(BetError::class, BetError::BALANCE);

            throw new DataModelException($model);
        }

        $player->setBalance($newAmount);

        //artificial delay
        $player->setBusyUntil(\time() + \random_int(10, 30));

        $bet = new BetEntity();
        $bet->setPlayer($player);
        $bet->setStakeAmount($model->stakeAmount);
        $this->em->persist($bet);

        foreach ($model->selections as $selection) {
            $betSelection = new BetSelectionEntity();
            $betSelection->setBet($bet);
            $betSelection->setSelectionId($selection->id);
            $betSelection->setOdds($selection->odds);
            $bet->addSelection($betSelection);
            $this->em->persist($betSelection);
        }


        $transaction = new BalanceTransactionEntity();
        $transaction->setPlayer($player);
        $transaction->setAmount($model->stakeAmount);
        $transaction->setAmountBefore($player->getBalance());
        $this->em->persist($transaction);

        $player->addTransaction($transaction);

        try {
            $this->em->beginTransaction();
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception|\Error $e) {
            $this->em->rollback();

            throw $e;
        }
    }

    private function safeGetPlayer(AddBet $model): PlayerEntity
    {
        $player = $this->em->getRepository(PlayerEntity::class)->findOneBy(['publicId' => $model->playerId]);

        if (!$player) {
            $player = new PlayerEntity();
            $player->setPublicId($model->playerId);
            $this->em->persist($player);

            return $player;
        }

        if ($player->getBusyUntil() > \time()) {
            $model->errors[] = $this->errorFactory->createError(BetError::class, BetError::UNFINISHED);

            throw new DataModelException($model);
        }

        return $player;
    }

    private function bindViolationsToModel(ConstraintViolationListInterface $violations, AddBet $model): void
    {
        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            /** @var Constraint $constraint */
            $constraint = $violation->getConstraint();
            $constraintType = \get_class($constraint);
            $constraintProperty = $violation->getPropertyPath();

            \preg_match('/^([^\[]+)(\[(\d+)\]\.(.+))?$/', $constraintProperty, $matches);

            $property = $matches[4] ?? $matches[1] ?? '';
            $index = $matches[3] ?? null;

            switch ($property) {
                case 'stakeAmount':
                    if ($constraintType === GreaterThan::class) {
                        $model->errors[] = $this
                            ->errorFactory
                            ->createError(BetError::class, BetError::MIN_AMOUNT, [':min_amount' => $constraint->value]);
                    }
                    if ($constraintType === LessThanOrEqual::class) {
                        $model->errors[] = $this
                            ->errorFactory
                            ->createError(BetError::class, BetError::MAX_AMOUNT, [':max_amount' => $constraint->value]);
                    }
                    break;
                case 'selections':
                    $model->errors[] = \count($model->selections) < $constraint->min
                        ? $this
                            ->errorFactory
                            ->createError(
                                BetError::class,
                                BetError::MIN_SELECTIONS,
                                [':min_selections' => $constraint->min]
                            )
                        : $this
                            ->errorFactory
                            ->createError(
                                BetError::class,
                                BetError::MAX_SELECTIONS,
                                [':max_selections' => $constraint->max]
                            );
                    break;
                case 'odds':
                    if ($constraintType === GreaterThan::class) {
                        $model->selections[$index]->errors[] = $this
                            ->errorFactory
                            ->createError(BetError::class, BetError::MIN_ODDS, [':min_odds' => $constraint->value]);
                    }
                    if ($constraintType === LessThanOrEqual::class) {
                        $model->selections[$index]->errors[] = $this
                            ->errorFactory
                            ->createError(BetError::class, BetError::MAX_ODDS, [':max_odds' => $constraint->value]);
                    }
                    break;
                case 'id':
                    $model->selections[$index]->errors[] = $this
                        ->errorFactory
                        ->createError(BetError::class, BetError::DUPLICATE);
                    break;
                case '':
                    $model->errors[] = $this
                        ->errorFactory
                        ->createError(BetError::class, BetError::MAX_WIN, [':max_win_amount' => $constraint->max]);
                    break;
                default:
                    throw new DataModelException($model);
            }
        }
    }
}
