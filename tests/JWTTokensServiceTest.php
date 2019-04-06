<?php

namespace P4BGroup\Authentication\Tests;

use Firebase\JWT\JWT;
use P4BGroup\Authentication\Claims;
use P4BGroup\Authentication\DecodeException;
use P4BGroup\Authentication\JWTTokensService;
use PHPUnit\Framework\TestCase;

class JWTTokensServiceTest extends TestCase
{
    /**
     * @var JWTTokensService
     */
    private $tokensService;

    public function setUp(): void
    {
        JWT::$leeway = 0; // disable clock skew for testing
        $this->tokensService = new JWTTokensService('HS256', 'foo', 'foo');
    }

    /**
     * @return void
     */
    public function testEncode(): void
    {
        $claims = new Claims();
        $claims->setSubject('test');

        $token = $this->tokensService->encode($claims);
        self::assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ0ZXN0In0.umFsgm6UOsJCQsNutNNOK_1nmEjZ5XnYBbzwTeeiS8M',
            $token
        );
    }

    /**
     * @throws DecodeException
     * @return void
     */
    public function testDecode(): void
    {
        $claims = $this->tokensService->decode(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ0ZXN0In0.umFsgm6UOsJCQsNutNNOK_1nmEjZ5XnYBbzwTeeiS8M'
        );
        self::assertEquals('{"sub":"test"}', json_encode($claims));
    }
}
