<?php
declare(strict_types=1);

namespace CommandEventApi\Exception;

class HandlerNotFoundException extends \Exception
{
    /** @var mixed */
    private $command;

    public function __construct($command)
    {
        $this->command = $command;
        parent::__construct(sprintf('command handler not found for "%s"', get_class($command)));
    }

    public function getCommand()
    {
        return $this->command;
    }
}
