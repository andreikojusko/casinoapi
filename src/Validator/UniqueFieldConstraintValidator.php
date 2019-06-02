<?php

namespace App\Validator;

use App\Constraint\UniqueFieldConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueFieldConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueFieldConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueFieldConstraint::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        $entries = [];
        foreach ($value as $item) {
            $key = $item->{$constraint->fieldName};
            if (!isset($entries[$key])) {
                $entries[$key] = 0;
            }
            $entries[$key]++;
        }

        $duplicates = [];
        foreach ($entries as $fieldValue => $num) {
            if ($num > 1) {
                $duplicates[] = $fieldValue;
            }
        }

        if (\count($duplicates) > 0) {
            foreach ($value as $key => $item) {
                if (\in_array($item->{$constraint->fieldName}, $duplicates, true)) {
                    $this
                        ->context
                        ->buildViolation($constraint->message)
                        ->atPath('[' . $key . '].' . $constraint->fieldName)
                        ->setParameter('{{ fieldName }}', $constraint->fieldName)
                        ->setParameter('{{ values }}', \implode(', ', $duplicates))
                        ->addViolation();
                }
            }
        }
    }
}
