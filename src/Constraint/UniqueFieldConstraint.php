<?php

namespace App\Constraint;

use App\Validator\UniqueFieldConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
class UniqueFieldConstraint extends Constraint
{
    public $message = 'The collection contains duplicate field {{ fieldName }} values [{{ values }}]';
    public $fieldName;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (null === $this->fieldName) {
            throw new MissingOptionsException(
                sprintf('Option "fieldName" must be given for constraint %s', __CLASS__),
                ['fieldName']
            );
        }
    }

    public function validatedBy(): string
    {
        return UniqueFieldConstraintValidator::class;
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
