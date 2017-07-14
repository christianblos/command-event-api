<?php

namespace CommandEventApi\CommandBus;

interface CommandHandlerInterface
{
    public function execute($command);
}
