<?php

namespace App\Helpers;

use Exception as BaseException;

class Exception extends BaseException {
    public function __construct(string $message = '', private array $data = [])
    {
        parent::__construct($message);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
