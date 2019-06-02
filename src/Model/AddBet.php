<?php

namespace App\Model;

use App\Constraint\UniqueFieldConstraint;
use App\Constraint\MaxWinAmountConstraint;
use App\Model\Parts\SelectionPart;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @MaxWinAmountConstraint(max=20000)
 */
class AddBet {
    /**
     * @var int
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @JMS\Type("integer")
     */
    public $playerId;

    /**
     * @var int
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type("float")
     * @Assert\GreaterThan(0.3)
     * @Assert\LessThanOrEqual(10000)
     * @JMS\Type("float")
     */
    public $stakeAmount;

    /**
     * @var array
     * @JMS\Type("array")
     */
    public $errors = [];

    /**
     * @var SelectionPart[]
     * @Assert\Type("array")
     * @Assert\Count(min=1, max=20)
     * @Assert\Valid
     * @UniqueFieldConstraint(fieldName="id")
     * @JMS\Type("array<App\Model\Parts\SelectionPart>")
     */
    public $selections = [];
}
