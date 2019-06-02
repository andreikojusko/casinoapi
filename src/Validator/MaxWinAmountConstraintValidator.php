<?php

namespace App\Validator;

use App\Constraint\MaxWinAmountConstraint;
use App\Model\AddBet;
use App\Model\Parts\SelectionPart;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class MaxWinAmountConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof MaxWinAmountConstraint) {
            throw new UnexpectedTypeException($constraint, MaxWinAmountConstraint::class);
        }

        if (null === $value) {
            return;
        }

        if (!($value instanceof AddBet)) {
            throw new UnexpectedValueException($value, 'AddBet');
        }

        $maxWinAmount = $value->stakeAmount;
        /** @var SelectionPart $selection */
        foreach ($value->selections as $selection) {
            $maxWinAmount *= $selection->odds;
        }

        if ($maxWinAmount > $constraint->max) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $constraint->max)
                ->addViolation();
        }
    }
}
