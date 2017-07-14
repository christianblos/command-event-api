<?php
declare(strict_types=1);

use CommandEventApi\CommandBus\CommandHandlerInterface;
use CommandEventApi\Event\EventDispatcher;

class CreateUserHandler implements CommandHandlerInterface
{
    /** @var EventDispatcher */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param CreateUser $command
     */
    public function execute($command)
    {
        if (!filter_var($command->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('invalid email address');
        }

        // logic to create user would be done here
        $userId = 1;

        $this->eventDispatcher->addEvent(new UserCreated($userId));
    }
}
