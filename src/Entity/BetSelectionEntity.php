<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bet_selections")
 */
class BetSelectionEntity
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var BetEntity
     * @ORM\ManyToOne(targetEntity="BetEntity", inversedBy="selections")
     */
    private $bet;

    /**
     * @var int
     * @ORM\Column(name="selection_id", type="integer")
     */
    private $selectionId;

    /**
     * @var float
     * @ORM\Column(name="odds", type="decimal", precision=5, scale=3)
     */
    private $odds;

    public function getId(): int
    {
        return $this->id;
    }

    public function getBet(): BetEntity
    {
        return $this->bet;
    }

    public function setBet(BetEntity $bet): void
    {
        $this->bet = $bet;
    }

    public function getSelectionId(): int
    {
        return $this->selectionId;
    }

    public function setSelectionId(int $selectionId): void
    {
        $this->selectionId = $selectionId;
    }

    public function getOdds(): float
    {
        return $this->odds;
    }

    public function setOdds(float $odds): void
    {
        $this->odds = $odds;
    }
}
