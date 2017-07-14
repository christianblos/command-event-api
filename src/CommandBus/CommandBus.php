<?php
declare(strict_types=1);

namespace CommandEventApi\CommandBus;

use CommandEventApi\Exception\HandlerNotFoundException;

class CommandBus
{
    /**
     * @var CommandHandlerLocatorInterface
     */
    private $handlerLocator;

    /**
     * @param CommandHandlerLocatorInterface $handlerLocator
     */
    public function __construct(CommandHandlerLocatorInterface $handlerLocator)
    {
        $this->handlerLocator = $handlerLocator;
    }

    public function execute($command)
    {
        $handler = $this->handlerLocator->get($command);
        if (!$handler) {
            throw new HandlerNotFoundException($command);
        }

        $handler->execute($command);
    }
}
