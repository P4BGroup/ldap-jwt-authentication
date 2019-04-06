<?php

namespace P4BGroup\Authentication;

use Firebase\JWT\JWT;
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
        $this->encodeKey = $encodeKey;
        $this->decodeKey = $decodeKey;
    }

    /**
     * @param Claims $claims
     *
     * @return string
     */
    public function encode(Claims $claims): string
    {
        return JWT::encode($claims, $this->encodeKey, $this->algorithm);
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
            $rawClaims = JWT::decode($token, $this->decodeKey, array_keys(JWT::$supported_algs));
        } catch (Throwable $exception) {
            throw new DecodeException('UNABLE_TO_DECODE', 400, $exception);
        }

        return new Claims($rawClaims);
    }
}
