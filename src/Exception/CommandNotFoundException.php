<?php
declare(strict_types=1);

namespace CommandEventApi\Exception;

class CommandNotFoundException extends \Exception
{
    /** @var string */
    private $commandName;

    public function __construct(string $commandName)
    {
        parent::__construct(sprintf('command "%s" not found', $commandName));
        $this->commandName = $commandName;
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }
}
