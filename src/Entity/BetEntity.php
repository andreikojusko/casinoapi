<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bet")
 */
class BetEntity
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
     * @ORM\ManyToOne(targetEntity="PlayerEntity", inversedBy="bets")
     */
    private $player;

    /**
     * @var float
     * @ORM\Column(name="stake_amount", type="decimal", precision=10, scale=2, options={"unsigned" = true})
     */
    private $stakeAmount;

    /**
     * @var DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var BetSelectionEntity[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="BetSelectionEntity", mappedBy="bet")
     */
    private $selections;

    public function __construct()
    {
        $this->selections = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

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

    public function getStakeAmount(): float
    {
        return $this->stakeAmount;
    }

    public function setStakeAmount(float $stakeAmount): void
    {
        $this->stakeAmount = $stakeAmount;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function addSelection(BetSelectionEntity $entity): void
    {
        if (!$this->selections->contains($entity)) {
            $this->selections->add($entity);
        }
    }
}
