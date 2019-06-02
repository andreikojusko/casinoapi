<?php

namespace App\Model\Parts;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

class SelectionPart
{
    /**
     * @var int
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @JMS\Type("integer")
     */
    public $id;

    /**
     * @var int
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type("float")
     * @Assert\GreaterThan(1)
     * @Assert\LessThanOrEqual(10000)
     * @JMS\Type("float")
     */
    public $odds;

    /**
     * @var array
     * @JMS\Type("array")
     */
    public $errors = [];
}
