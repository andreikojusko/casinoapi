<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="balance_transaction")
 */
class BalanceTransactionEntity
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var PlayerEntity
     * @ORM\ManyToOne(targetEntity="PlayerEntity", inversedBy="transactions")
     */
    private $player;

    /**
     * @var float
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @var float
     * @ORM\Column(name="amount_before", type="decimal", precision=10, scale=2)
     */
    private $amountBefore;


    public function getId(): int
    {
        return $this->id;
    }

    public function getPlayer(): PlayerEntity
    {
        return $this->player;
    }

    public function setPlayer(PlayerEntity $player): void
    {
        $this->player = $player;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmountBefore(): float
    {
        return $this->amountBefore;
    }

    public function setAmountBefore(float $amountBefore): void
    {
        $this->amountBefore = $amountBefore;
    }
}
