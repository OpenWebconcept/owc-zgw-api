<?php

namespace OWC\ZGW\Entities\Traits;

trait HasSchemaValidation
{
    public function getValidationSchema(string $operation): array
    {
        if (isset($this->validationSchema[$operation])) {
            return $this->validationSchema[$operation];
        }

        return [];
    }

    public function setValidationSchema(string $operation, array $schema): void
    {
        $this->validationSchema[$operation] = $schema;
    }

    public function addSchemaConstraint(string $operation, string $property, string $rule): void
    {
        $schema = $this->getValidationSchema($operation);

        // Property doesn't have a rule yet.
        if (! is_array($schema[$property])) {
            $schema[$property] = [];
        }

        $schema[$property][] = $rule;

        $this->setValidationSchema($operation, $schema);
    }

    public function removeSchemaConstraint(string $operation, string $property, string $rule): void
    {
        $schema = $this->getValidationSchema($operation);

        // Property doesn't have a rule yet.
        if (! is_array($schema[$property]) || empty($schema[$property])) {
            return;
        }

        $schema[$property] = array_diff($schema[$property], [$rule]);

        $this->setValidationSchema($operation, $schema);
    }
}
