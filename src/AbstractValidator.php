<?php

namespace Gidralit\Validators;

abstract class AbstractValidator
{
    protected string $field = '';
    protected $value;
    protected array $args = [];
    protected array $messageKeys = [];
    protected string $message = '';

    public function __construct(
        string $fieldName,
               $value,
        array $args = [],
        ?string $message = null
    ) {
        $this->field = $fieldName;
        $this->value = $value;
        $this->args = $args;
        $this->message = $message ?? $this->message;

        $this->messageKeys = [
            ":value" => $this->value,
            ":field" => $this->field
        ];

        foreach ($this->args as $index => $arg) {
            $this->messageKeys[":arg[$index]"] = $arg;
        }
    }

    public function validate(): bool|string
    {
        return $this->rule() ? true : $this->messageError();
    }

    private function messageError(): string
    {
        $message = $this->message;
        foreach ($this->messageKeys as $key => $value) {
            $message = str_replace($key, (string)$value, $message);
        }
        return $message;
    }

    abstract public function rule(): bool;
}