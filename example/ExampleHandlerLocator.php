<?php
declare(strict_types=1);

use CommandEventApi\CommandBus\CommandHandlerLocatorInterface;
use CommandEventApi\Event\EventDispatcher;

class ExampleHandlerLocator implements CommandHandlerLocatorInterface
{
    /** @var EventDispatcher */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function get($command)
    {
        $className = get_class($command) . 'Handler';
        if (!class_exists($className)) {
            return null;
        }

        return new $className($this->eventDispatcher);
    }
}
