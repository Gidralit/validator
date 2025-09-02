<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class MaxValidator extends AbstractValidator
{

    protected string $message = 'Поле :field не должно быть длиннее :max симолов(-а)';

    public function rule(): bool
    {
        if ($this->value === null || $this->value === '') {
            return false;
        }
        return mb_strlen($this->value) <= $this->args[0];
    }
}