<?php

declare(strict_types=1);

namespace OWC\ZGW\Support\Validation\Rules;

class Elfproef extends ValidationRule
{
    public function validate($value)
    {
        if ($this->elfproef($value)) {
            return $this->setSuccess();
        }

        return $this->setFailure("The value does not pass the elfproef");
    }

    protected function elfproef($input): bool
    {
        $input = str_split(str_pad($input, 9, '0', STR_PAD_LEFT));

        // Elfproef
        $total = 0;
        foreach ($input as $i => $number) {
            $value = $number * (9 - $i) * ((9 - $i) === 1 ? -1 : 1);
            $total += $value;
        }

        return ($total % 11) === 0;
    }
}
