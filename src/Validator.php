<?php

namespace Gidralit\Validators;

class Validator
{
    private array $validators = [];
    private array $errors = [];
    private array $fields = [];
    private array $rules = [];
    private array $messages = [];

    public function __construct(
        array $fields,
        array $rules,
        array $messages = []
    ) {
        $this->validators = $this->getDefaultValidators();
        $this->fields = $fields;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->validate();
    }

    private function getDefaultValidators(): array
    {
        return [
            'required' => \Validators\RequiredValidator::class,
            'string' => \Validators\StringValidator::class,
            'min' => \Validators\MinValidator::class,
            'max' => \Validators\MaxValidator::class,
            'unique' => \Validators\UniqueValidator::class,
        ];
    }

    private function validate(): void
    {
        foreach ($this->rules as $fieldName => $fieldValidators) {
            $this->validateField($fieldName, $fieldValidators);
        }
    }

    private function validateField(string $fieldName, array $fieldValidators): void
    {
        foreach ($fieldValidators as $validatorName) {
            $tmp = explode(':', $validatorName);
            [$validatorName, $args] = count($tmp) > 1 ? $tmp : [$validatorName, null];
            $args = $args ? explode(',', $args) : [];

            if (!isset($this->validators[$validatorName])) {
                continue;
            }

            $validatorClass = $this->validators[$validatorName];

            if (!class_exists($validatorClass)) {
                continue;
            }

            $validator = new $validatorClass(
                $fieldName,
                $this->fields[$fieldName] ?? null,
                $args,
                $this->messages[$validatorName] ?? null
            );

            $result = $validator->validate();

            if ($result !== true) {
                $this->errors[$fieldName][] = $result;
            }
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return count($this->errors) > 0;
    }
}