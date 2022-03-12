<?php

namespace P4BGroup\Authentication;

use JsonSerializable;

class User implements JsonSerializable
{
    /**
     * @var string
     */
    protected $id = '';
    /**
     * @var string
     */
    protected $first_name = '';
    /**
     * @var mixed|string
     */
    protected $last_name = '';
    /**
     * @var string
     */
    protected $email = '';
    /**
     * @var string
     */
    protected $user_name;
    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @param array $userData
     */
    public function __construct(array $userData)
    {
        $this->id = $userData['dn'] ?? '';
        $this->user_name = $userData['samaccountname'][0] ?? '';
        $this->email = $userData['mail'][0] ?? '';
        $this->first_name = $userData['givenname'][0] ?? '';
        $this->last_name = $userData['sn'][0] ?? '';
        $this->groups = $userData['memberof'] ?? [];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'groups' => $this->groups,
        ];
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
}
