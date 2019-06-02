<?php

namespace App\Exception;

class DataModelException extends \UnexpectedValueException {
    private $model;

    public function __construct($model, $message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->setModel($model);
    }

    public function getModel()
    {
        return $this->model;
    }

    private function setModel($model): void
    {
        $this->model = $model;
    }
}
