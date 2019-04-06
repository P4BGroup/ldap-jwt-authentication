<?php

namespace P4BGroup\Authentication;

use Exception;

class DecodeException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'JWT_DECODE_FAILED';

    /**
     * @var int
     */
    protected $code = 400;
}
