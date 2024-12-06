<?php

namespace OWC\ZGW\Endpoints\Filter;

use DateTimeInterface;

abstract class AbstractFilter
{
    /**
     * @var array<mixed>
     */
    protected array $parameters;

    public const OPERATOR_EQUALS = '=';
    public const OPERATOR_IS_NULL = 'isnull';
    public const OPERATOR_GT = 'gt';
    public const OPERATOR_GTE = 'gte';
    public const OPERATOR_LT = 'lt';
    public const OPERATOR_LTE = 'lte';

    /**
     * @param array<mixed> $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array<mixed>
     */
    public function getParameters(): array
    {
        return array_filter($this->parameters, function ($param) {
            return $param !== null;
        });
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->parameters[$name] ?? $default;
    }

    /**
     * @param mixed $value
     */
    public function add(string $name, $value): self
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    public function remove(string $name): void
    {
        unset($this->parameters[$name]);
    }

    public function has(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    public function page(int $pageNumber): self
    {
        return $this->add('page', $pageNumber);
    }

    protected function addDateFilter(
        string $fieldName,
        DateTimeInterface $date,
        string $operator = self::OPERATOR_EQUALS,
        string $dateFormat = 'Y-m-d'
    ): self {
        if (! $this->isSupportedOperator($operator)) {
            throw new \InvalidArgumentException(sprintf('Invalid operator "%s" given', $operator));
        }

        if ($operator !== self::OPERATOR_EQUALS) {
            $fieldName = $fieldName .= '__' . $this->getOperatorAppendix($operator);
        }

        return $this->add($fieldName, $date->format($dateFormat));
    }

    protected function isSupportedOperator(string $operator): bool
    {
        $supported = [
            self::OPERATOR_EQUALS, self::OPERATOR_IS_NULL, self::OPERATOR_GT,
            self::OPERATOR_GTE, self::OPERATOR_LT, self::OPERATOR_LTE
        ];

        return in_array($operator, $supported);
    }

    protected function getOperatorAppendix(string $operator): string
    {
        if ($operator === self::OPERATOR_EQUALS) {
            return '';
        }

        return $operator;
    }
}
