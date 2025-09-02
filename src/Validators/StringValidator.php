<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class StringValidator extends AbstractValidator
{

    protected string $message = 'Поле :field должно быть строкой';

    public function rule(): bool
    {
        return is_string($this->value);
    }
}