<?php
declare(strict_types=1);

use CommandEventApi\CommandBus\CommandBus;
use CommandEventApi\Event\EventDispatcher;
use CommandEventApi\RequestHandler;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testExecuteReturnsEvent()
    {
        $eventDispatcher  = new EventDispatcher();
        $handlerLocator   = new ExampleHandlerLocator($eventDispatcher);
        $commandFactory   = new ExampleCommandFactory();
        $responseAssigner = new ExampleResponseAssigner();

        $commandBus = new CommandBus($handlerLocator);

        $requestHandler = new RequestHandler($commandBus, $eventDispatcher, $commandFactory, $responseAssigner);
        $reqId          = uniqid('', true);

        $response = $requestHandler->execute([
            [
                'command' => 'createUser',
                'payload' => [
                    'email'    => 'my@email.com',
                    'password' => 'unsafe',
                ],
                'reqId'   => $reqId,
            ],
        ]);

        self::assertEquals(
            [
                [
                    'event'   => 'userCreated',
                    'payload' => [
                        'userId' => 1,
                    ],
                    'reqId'   => $reqId,
                ],
            ],
            $response
        );
    }

    public function testExecuteReturnsError()
    {
        $eventDispatcher  = new EventDispatcher();
        $handlerLocator   = new ExampleHandlerLocator($eventDispatcher);
        $commandFactory   = new ExampleCommandFactory();
        $responseAssigner = new ExampleResponseAssigner();

        $commandBus = new CommandBus($handlerLocator);

        $requestHandler = new RequestHandler($commandBus, $eventDispatcher, $commandFactory, $responseAssigner);
        $reqId          = uniqid('', true);

        $response = $requestHandler->execute([
            [
                'command' => 'createUser',
                'payload' => [
                    'email'    => 'wrong-email-address',
                    'password' => 'unsafe',
                ],
                'reqId'   => $reqId,
            ],
        ]);

        self::assertEquals(
            [
                [
                    'event'   => 'errorOccurred',
                    'payload' => [
                        'message' => 'invalid email address',
                    ],
                    'reqId'   => $reqId,
                ],
            ],
            $response
        );

    }
}
