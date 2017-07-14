<?php
declare(strict_types=1);

use CommandEventApi\CommandFactoryInterface;

class ExampleCommandFactory implements CommandFactoryInterface
{
    public function createCommand(string $commandName, array $payload)
    {
        switch ($commandName) {
            case 'createUser':
                return new CreateUser($payload['email'], $payload['password']);
        }
    }
}
