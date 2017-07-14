<?php

namespace CommandEventApi\CommandBus;

interface CommandHandlerLocatorInterface
{
    /**
     * @param mixed $command
     *
     * @return CommandHandlerInterface|null
     */
    public function get($command);
}
