<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Logger;

use Monolog\Processor\ProcessorInterface;

class LogDetailProcessor implements ProcessorInterface
{
    public function __construct(
        protected string $messageDetailLevel
    ) {
    }

    /**
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    public function __invoke(array $record): array
    {
        $record = $this->addExecutionTime($record);

        switch ($this->messageDetailLevel) {
            case MessageDetail::BLACK_BOX:
                return $this->blackBoxRecord($record);
            case MessageDetail::GRAY_BOX:
                return $this->grayBoxRecord($record);
            case MessageDetail::WHITE_BOX:
                return $this->whiteBoxRecord($record);
            case MessageDetail::URL_LOGGING:
            default:
                return $this->urlOnlyRecord($record);
        }

        return $record;
    }

    /**
     * Returns the URL and body text
     *
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    protected function blackBoxRecord(array $record): array
    {
        unset($record['context']['arguments']);
        $record['context']['response'] = $record['context']['response']['body'];

        return $record;
    }

    /**
     * Returns the URL, request parameters and body text
     *
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    protected function grayBoxRecord(array $record): array
    {
        $record['context']['response'] = $record['context']['response']['body'];

        return $record;
    }

    /**
     * Returns the full unaltered record and adds a stacktrace
     *
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    protected function whiteBoxRecord(array $record): array
    {
        $exception = new \Exception();
        $record['context']['trace'] = $exception->getTraceAsString();

        return $record;
    }

    /**
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    protected function urlOnlyRecord(array $record): array
    {
        unset($record['context']['arguments']);
        unset($record['context']['response']);

        return $record;
    }

    /**
     * @param array<mixed> $record
     *
     * @return array<mixed>
     */
    protected function addExecutionTime(array $record): array
    {
        $time = microtime(true) - $record['context']['arguments']['headers']['_owc_request_logging'];
        $record['extra']['total_time'] = $time;

        return $record;
    }
}
