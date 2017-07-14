<?php
declare(strict_types=1);

namespace CommandEventApi;

interface CommandFactoryInterface
{
    /**
     * @param string $commandName
     * @param array  $payload
     *
     * @return mixed|null
     */
    public function createCommand(string $commandName, array $payload);
}
