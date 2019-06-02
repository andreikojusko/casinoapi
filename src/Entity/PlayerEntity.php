<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="player")
 */
class PlayerEntity
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned" = true})
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="public_id", type="integer", options={"unsigned" = true})
     */
    private $publicId;

    /**
     * @var BetEntity[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="BetEntity", mappedBy="player")
     */
    private $bets;

    /**
     * @var float
     * @ORM\Column(name="balance", type="decimal", precision=10, scale=2, options={"unsigned" = true})
     */
    private $balance = 1000;

    /**
     * @var BalanceTransactionEntity[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="BalanceTransactionEntity", mappedBy="player")
     */
    private $transactions;

    /**
     * @var int
     * @ORM\Column(name="busy_until", type="integer", options={"unsigned" = true})
     */
    private $busyUntil;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->bets = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPublicId(): int
    {
        return $this->publicId;
    }

    public function setPublicId(int $publicId): void
    {
        $this->publicId = $publicId;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function addBet(BetEntity $entity): void
    {
        if (!$this->bets->contains($entity)) {
            $this->bets->add($entity);
        }
    }

    public function addTransaction(BalanceTransactionEntity $entity): void
    {
        if (!$this->transactions->contains($entity)) {
            $this->transactions->add($entity);
        }
    }

    public function getBusyUntil(): int
    {
        return $this->busyUntil;
    }

    public function setBusyUntil(int $busyUntil): void
    {
        $this->busyUntil = $busyUntil;
    }
}
