<?php declare(strict_types=1);

namespace P4BGroup\Authentication;

use DateTimeInterface;
use JsonSerializable;
use stdClass;

class Claims implements JsonSerializable
{
    /**
     * This claim is application specific.
     * Example of usage: host of the service generating the claim, server ip, etc
     *
     * @var string
     * @see https://tools.ietf.org/html/rfc7519#section-4.1.1
     */
    private $issuer;

    /**
     * This claim is application specific
     * Example of usage: description of the token usage scope (authentication, refresh, acl, etc)
     *
     * @var string
     * @see https://tools.ietf.org/html/rfc7519#section-4.1.2
     */
    private $subject;

    /**
     * This claim is application specific
     * Example of usage: a hwid of the client that generated the token, to be used for theft prevention purposes
     *
     * @var string
     * @see https://tools.ietf.org/html/rfc7519#section-4.1.3
     */
    private $audience;

    /**
     * @var int
     * @see https://tools.ietf.org/html/rfc7519#section-4.1.4
     */
    private $expirationTime;

    /**
     * @var int
     * @see https://tools.ietf.org/html/rfc7519#section-4.1.5
     */
    private $notBefore;

    /**
     * @var int
     * @see https://tools.ietf.org/html/rfc7519#section-4.1.6
     */
    private $issuedAt;

    /**
     * Application specific - unique identifier of a token.
     * Example of usage: An id, a hash, etc
     *
     * @var string
     * @see https://tools.ietf.org/html/rfc7519#section-4.1.7
     */
    private $jwtId;

    /**
     * Custom claims that are not part of the RFC should be grouped and isolated in a single claim.
     * This will keep a fixed structure of the JWT body and will allow us clients better control on de-serialization
     * Example: $data can have multiple structures depending on the "subject" claim
     *
     * @var array
     */
    private $data = [];

    /**
     * Sets the audience to "authentication"
     */
    public const SUBJECT_AUTHENTICATION = 'authentication';

    /**
     * Sets the audience to "refresh"
     */
    public const SUBJECT_REFRESH = 'refresh';

    /**
     * @param stdClass|null $claims
     */
    public function __construct(?stdClass $claims = null)
    {
        if ($claims instanceof stdClass) {
            $data = ($claims->data ?? null) instanceof stdClass ? $claims->data : new stdClass();
            $claims->data = json_decode(json_encode($data), true);

            foreach ($this->claimsMap() as $claim => $property) {
                $this->{$property} = $claims->{$claim} ?? $this->{$property};
            }
        }
    }

    /**
     * @return string[]
     */
    private function claimsMap(): array
    {
        return [
            'iss' => 'issuer',
            'sub' => 'subject',
            'aud' => 'audience',
            'exp' => 'expirationTime',
            'nbf' => 'notBefore',
            'iat' => 'issuedAt',
            'jti' => 'jwtId',
            // claims extra data
            'data' => 'data',
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $claims = [];
        foreach ($this->claimsMap() as $claim => $property) {
            $value = $this->{$property} ?? null;
            // skip empty values
            if (in_array($value, [null, []], true)) {
                continue;
            }

            $claims[$claim] = $value;
        }

        return $claims;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getIssuer(): string
    {
        return $this->issuer;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getAudience(): string
    {
        return $this->audience;
    }

    /**
     * @return int
     */
    public function getExpirationTime(): int
    {
        return $this->expirationTime;
    }

    /**
     * @return int
     */
    public function getNotBefore(): int
    {
        return $this->notBefore;
    }

    /**
     * @return int
     */
    public function getIssuedAt(): int
    {
        return $this->issuedAt;
    }

    /**
     * @return string
     */
    public function getJwtId(): string
    {
        return $this->jwtId;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $issuer
     *
     * @return Claims
     */
    public function setIssuer(string $issuer): Claims
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * @param string $subject
     *
     * @return Claims
     */
    public function setSubject(string $subject): Claims
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string $audience
     *
     * @return Claims
     */
    public function setAudience(string $audience): Claims
    {
        $this->audience = $audience;
        return $this;
    }

    /**
     * @param DateTimeInterface $dateTime
     *
     * @return Claims
     */
    public function setExpirationTime(DateTimeInterface $dateTime): Claims
    {
        $this->expirationTime = $dateTime->getTimestamp();
        return $this;
    }

    /**
     * @param int $notBefore
     *
     * @return Claims
     */
    public function setNotBefore(int $notBefore): Claims
    {
        $this->notBefore = $notBefore;
        return $this;
    }

    /**
     * @param int $issuedAt
     *
     * @return Claims
     */
    public function setIssuedAt(int $issuedAt): Claims
    {
        $this->issuedAt = $issuedAt;
        return $this;
    }

    /**
     * @param string $jwtId
     *
     * @return Claims
     */
    public function setJwtId(string $jwtId): Claims
    {
        $this->jwtId = $jwtId;
        return $this;
    }

    /**
     * @param array $data
     *
     * @return Claims
     */
    public function setData(array $data): Claims
    {
        $this->data = $data;
        return $this;
    }
}
