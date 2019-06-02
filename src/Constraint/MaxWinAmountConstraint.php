<?php

namespace App\Constraint;

use App\Validator\MaxWinAmountConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
class MaxWinAmountConstraint extends Constraint
{
    public $message = 'Max win amount should be less or equal to {{ value }}';
    public $max;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (null === $this->max) {
            throw new MissingOptionsException(
                sprintf('Option "max" must be given for constraint %s', __CLASS__),
                ['max']
            );
        }
    }

    public function validatedBy(): string
    {
        return MaxWinAmountConstraintValidator::class;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
