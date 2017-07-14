<?php
declare(strict_types=1);

namespace CommandEventApi;

class ResponseList
{
    /** @var string */
    private $requestId;

    /** @var array */
    private $responses = [];
    /** @var array */
    private $eventWhitelist;

    public function __construct(string $requestId = '', array $eventWhitelist = [])
    {
        $this->requestId      = $requestId;
        $this->eventWhitelist = $eventWhitelist;
    }

    public function add(string $eventName, array $payload = [])
    {
        if ($this->eventWhitelist && !in_array($eventName, $this->eventWhitelist, true)) {
            return;
        }

        $res = [
            'event'   => $eventName,
            'payload' => $payload,
        ];

        if ($this->requestId) {
            $res['reqId'] = $this->requestId;
        }

        $this->responses[] = $res;
    }

    public function getAll(): array
    {
        return $this->responses;
    }
}
