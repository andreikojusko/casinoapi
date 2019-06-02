<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

class Error
{
    /**
     * @var int
     * @JMS\Type("integer")
     */
    public $code;

    /**
     * @var string
     * @JMS\Type("string")
     */
    public $message;
}
