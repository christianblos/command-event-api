<?php
declare(strict_types=1);

namespace CommandEventApi;

use CommandEventApi\CommandBus\CommandBus;
use CommandEventApi\Event\EventDispatcher;
use CommandEventApi\Exception\CommandNotFoundException;
use CommandEventApi\Exception\InvalidRequestException;
use Throwable;

class RequestHandler
{
    /** @var CommandBus */
    private $commandBus;
    /** @var EventDispatcher */
    private $eventDispatcher;
    /** @var CommandFactoryInterface */
    private $commandFactory;
    /** @var ResponseAssignerInterface */
    private $responseAssigner;

    public function __construct(
        CommandBus $commandBus,
        EventDispatcher $eventDispatcher,
        CommandFactoryInterface $commandFactory,
        ResponseAssignerInterface $responseAssigner
    ) {
        $this->commandBus       = $commandBus;
        $this->eventDispatcher  = $eventDispatcher;
        $this->commandFactory   = $commandFactory;
        $this->responseAssigner = $responseAssigner;
    }

    public function execute(array $requests): array
    {
        $allResponses = [];

        foreach ($requests as $request) {
            if (!is_array($request)) {
                throw new InvalidRequestException('request must be an array of request-arrays');
            }

            $responses = $this->executeRequest($request)->getAll();

            if ($responses) {
                array_push($allResponses, ...$responses);
            }
        }

        return $allResponses;
    }

    protected function executeRequest(array $request): ResponseList
    {
        $this->validateRequest($request);
        $commandName    = $request['command'] ?? '';
        $payload        = $request['payload'] ?? [];
        $requestId      = $request['reqId'] ?? '';
        $eventWhitelist = $request['events'] ?? [];

        $responses = new ResponseList($requestId, $eventWhitelist);

        $listener = function ($event) use ($responses) {
            $this->responseAssigner->addEventToResponse($responses, $event);
        };

        $this->eventDispatcher->listenAll($listener);

        try {
            $command = $this->commandFactory->createCommand($commandName, $payload);
            if (!$command) {
                throw new CommandNotFoundException($commandName);
            }

            $this->commandBus->execute($command);
        } catch (Throwable $error) {
            $this->eventDispatcher->cleanEvents();
            $this->responseAssigner->addErrorToResponse($responses, $error);
        }

        $this->eventDispatcher->dispatchAll();
        $this->eventDispatcher->stopListenAll($listener);

        return $responses;
    }

    protected function validateRequest(array $request)
    {
        if (!$request['command'] ?? '') {
            throw new InvalidRequestException('request must have a "command" property');
        }

        if (!is_array($request['payload'] ?? [])) {
            throw new InvalidRequestException('request payload must be an array');
        }

        if (!is_string($request['reqId'] ?? '')) {
            throw new InvalidRequestException('request reqId must be a string');
        }

        if (!is_array($request['events'] ?? [])) {
            throw new InvalidRequestException('request events must be an array');
        }
    }
}
