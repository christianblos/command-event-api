<?php
declare(strict_types=1);

use CommandEventApi\ResponseAssignerInterface;
use CommandEventApi\ResponseList;

class ExampleResponseAssigner implements ResponseAssignerInterface
{
    public function addEventToResponse(ResponseList $responses, $event)
    {
        if ($event instanceof UserCreated) {
            $responses->add('userCreated', [
                'userId' => $event->getUserId(),
            ]);
        }
    }

    public function addErrorToResponse(ResponseList $responses, Throwable $error)
    {
        $responses->add('errorOccurred', [
            'message' => $error->getMessage(),
        ]);
    }
}
