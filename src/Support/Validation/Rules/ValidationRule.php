<?php

declare(strict_types=1);

namespace OWC\ZGW\Support\Validation\Rules;

abstract class ValidationRule
{
    protected bool $succeeded;
    protected string $errorMessage;

    abstract public function validate($value);

    public function getErrorMessage(): string
    {
        if (!isset($this->succeeded) || $this->succeeded) {
            return '';
        }

        if (isset($this->errorMessage)) {
            return $this->errorMessage;
        }

        return 'An unknown validation error occured';
    }

    protected function setFailure(string $errorMessage): bool
    {
        $this->succeeded = false;
        $this->errorMessage = $errorMessage;

        return false;
    }

    protected function setSuccess(): bool
    {
        $this->succeeded = true;
        unset($this->errorMessage);

        return true;
    }
}
