<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Logger;

use Monolog\Processor\ProcessorInterface;

class FilterBsnProcessor implements ProcessorInterface
{
    /**
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    public function __invoke(array $record): array
    {
        return $this->removeBsnFromRecord($record);
    }

    /**
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    protected function removeBsnFromRecord(array $record): array
    {
        foreach ($record as $key => $value) {
            if (is_string($value)) {
                $record[$key] = $this->filterBsn($value);
            } elseif (is_array($value)) {
                $record[$key] = $this->removeBsnFromRecord($value);
            }
        }

        return $record;
    }

    protected function filterBsn(string $input): string
    {
        $matches = $this->getPossibleBsnMatches($input);
        if (empty($matches)) {
            return $input;
        }

        foreach ($matches as $match) {
            if ($this->isBsn($match)) {
                $input = str_replace($match, 'XXXXXXXXX', $input);
            }
        }

        return $input;
    }

    /**
     * @return array<mixed>
     */
    protected function getPossibleBsnMatches(string $input): array
    {
        preg_match_all('/([\d]{8,9})/', $input, $matches);

        return array_filter($matches[0]);
    }

    protected function isBsn(string $input): bool
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
