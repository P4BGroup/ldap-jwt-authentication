<?php

namespace P4BGroup\Authentication;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;

class JWTTokensService
{
    /**
     * @var string
     */
    private $algorithm;
    /**
     * @var string
     */
    private $encodeKey;
    /**
     * @var string
     */
    private $decodeKey;

    /**
     * @param string $algorithm
     * @param string $encodeKey
     * @param string $decodeKey
     */
    public function __construct(
        string $algorithm,
        string $encodeKey,
        string $decodeKey
    ) {
        $this->algorithm = $algorithm;
        $this->encodeKey = $this->readKey($encodeKey);
        $this->decodeKey = $this->readKey($decodeKey);
    }

    /**
     * @param $key
     *
     * @return string
     */
    private function readKey($key): string
    {
        return file_exists($key) && is_readable($key) ? file_get_contents($key) : $key;
    }

    /**
     * @param Claims $claims
     *
     * @return string
     */
    public function encode(Claims $claims): string
    {
        return JWT::encode($claims->toArray(), $this->encodeKey, $this->algorithm);
    }

    /**
     * @param string $token
     *
     * @return Claims
     * @throws DecodeException
     */
    public function decode(string $token): Claims
    {
        try {
            $rawClaims = JWT::decode($token, new Key($this->decodeKey, $this->algorithm));
        } catch (Throwable $exception) {
            throw new DecodeException('UNABLE_TO_DECODE', 400, $exception);
        }

        return new Claims($rawClaims);
    }
}
