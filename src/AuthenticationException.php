<?php

namespace P4BGroup\Authentication;

use RuntimeException;

class AuthenticationException extends RuntimeException
{
    protected $message = 'AUTHENTICATION_FAILED';
    protected $code = 400;
}
