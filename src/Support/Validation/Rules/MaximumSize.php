<?php

declare(strict_types=1);

namespace OWC\ZGW\Support\Validation\Rules;

class MaximumSize extends ValidationRule
{
    protected int $maximum;

    public function __construct($operator)
    {
        $this->maximum = (int) $operator;
    }
    public function validate($value)
    {
        if (is_string($value)) {
            return $this->validateInt(mb_strlen($value));
        }

        return $this->validateInt((int) $value);
    }

    protected function validateInt(int $value)
    {
        if ($value <= $this->maximum) {
            return $this->setSuccess();
        }

        return $this->setFailure(sprintf(
            'The value must not be greater than %d',
            $this->maximum
        ));
    }
}
