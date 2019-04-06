<?php

namespace P4BGroup\Authentication;

use DateTime;
use PHPUnit\Framework\TestCase;

class ClaimsTest extends TestCase
{
    public function gettersProvider(): array
    {
        return [
            'simple data' => [
                'claims' => '{"aud": "audience", "iss": "issuer", "sub": "subject", ' .
                    '"exp": 1, "nbf": 2, "iat": 3, "jti": "test", "data": {"foo": "bar"}}',
                'audience' => 'audience',
                'issuer' => 'issuer',
                'expires at' => 1,
                'not before' => 2,
                'issued at' => 3,
                'jwt id' => 'test',
                'data' => ['foo' => 'bar'],
            ]
        ];
    }

    /**
     * @dataProvider gettersProvider
     *
     * @param string $encodedClaims
     * @param string $audience
     * @param string $issuer
     * @param int $expirationTime
     * @param int $notBefore
     * @param int $issuedAt
     * @param string $jwtId
     * @param array $data
     */
    public function testGetters(
        string $encodedClaims,
        string $audience,
        string $issuer,
        int $expirationTime,
        int $notBefore,
        int $issuedAt,
        string $jwtId,
        array $data
    ): void {
        $tokens = json_decode($encodedClaims);
        $claims = new Claims($tokens);

        self::assertSame($audience, $claims->getAudience());
        self::assertSame($issuer, $claims->getIssuer());
        self::assertSame($expirationTime, $claims->getExpirationTime());
        self::assertSame($notBefore, $claims->getNotBefore());
        self::assertSame($issuedAt, $claims->getIssuedAt());
        self::assertSame($jwtId, $claims->getJwtId());
        self::assertSame($data, $claims->getData());
    }

    public function testSetters(): void
    {
        $claims = new Claims();

        $claims->setSubject('testSubject')
            ->setExpirationTime(new DateTime('2000-01-01 00:00:00'))
            ->setAudience('testAudience')
            ->setData(['foo' => 'bar'])
            ->setIssuedAt(2)
            ->setIssuer('testIssuer')
            ->setJwtId('test jwt id')
            ->setNotBefore(3);

        self::assertSame([
            'iss' => 'testIssuer',
            'sub' => 'testSubject',
            'aud' => 'testAudience',
            'exp' => 946684800,
            'nbf' => 3,
            'iat' => 2,
            'jti' => 'test jwt id',
            'data' => ['foo' => 'bar'],
        ], $claims->toArray());
    }
}
