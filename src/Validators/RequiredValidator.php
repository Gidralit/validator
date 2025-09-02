<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class RequiredValidator extends AbstractValidator
{

    protected string $message = 'Field :field is required';

    public function rule(): bool
    {
        return !empty($this->value);
    }
}