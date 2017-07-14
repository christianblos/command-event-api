<?php
declare(strict_types=1);

namespace CommandEventApi;

use Throwable;

interface ResponseAssignerInterface
{
    public function addEventToResponse(ResponseList $responses, $event);

    public function addErrorToResponse(ResponseList $responses, Throwable $error);
}
