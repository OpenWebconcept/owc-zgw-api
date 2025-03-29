<?php

declare(strict_types=1);

namespace OWC\ZGW\Support\Validation;

class Validator
{
    protected array $rules = [
        'maxSize' => Rules\MaximumSize::class,
        'elfproef' => Rules\Elfproef::class,
        // 'required' => RequiredRule::class,
    ];

    public function __construct(?array $rules = [])
    {
        $this->rules = array_merge($this->rules, $rules);
    }

    // $value = 'foo'
    // $rules = ['required', 'length:8']
    public function validate($value, array $rules)
    {
        foreach ($rules as $ruleDefinition) {
            $rule = $this->getRuleFromDefinition($ruleDefinition);

            if ($rule->validate($value) !== true) {
                throw new ValidationError($rule->getErrorMessage());
            }
        }

        return true;
    }

    public function addRule(string $name, Rules\ValidationRule $rule): void
    {
        $this->rules[$name] = $rule;
    }

    public function removeRule(string $name): void
    {
        unset($this->rules[$name]);
    }

    protected function getRuleFromDefinition(string $ruleDefinition): Rules\ValidationRule
    {
        if (strpos($ruleDefinition, ':') === false) {
            return $this->getRuleByName($ruleDefinition);
        }

        [$ruleName, $operators] = explode(':', $ruleDefinition);

        return $this->getRuleByName($ruleName, $operators);
    }

    protected function getRuleByName(string $ruleName, ?string $operators = ''): Rules\ValidationRule
    {
        if (! isset($this->rules[$ruleName])) {
            // Error
            throw new ValidationError(sprintf('No such rule "%s"', $ruleName));
        }

        return new $this->rules[$ruleName]($operators);
    }
}
