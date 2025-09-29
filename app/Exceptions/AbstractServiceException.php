<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractServiceException extends Exception
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;
}
