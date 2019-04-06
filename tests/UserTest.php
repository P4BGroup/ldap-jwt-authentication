<?php

namespace P4BGroup\Authentication\Tests;

use P4BGroup\Authentication\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @return array
     */
    public function objectStateProvider(): array
    {
        return [
            'default data (no data)' => [
                'constructor arguments' => [],
                'expected result' => [
                    'id' => '',
                    'user_name' => '',
                    'first_name' => '',
                    'last_name' => '',
                    'email' => '',
                    'groups' => [],
                ],
            ],
            'simple user' => [
                'constructor arguments' => [
                    'dn' => 'DC=foo.bar',
                    'samaccountname' => ['foo.bar'],
                    'mail' => ['test@mail.com'],
                    'givenname' => ['foo'],
                    'sn' => ['bar'],
                    'memberof' => ['role1', 'role2'],
                ],
                'expected result' => [
                    'id' => 'DC=foo.bar',
                    'user_name' => 'foo.bar',
                    'first_name' => 'foo',
                    'last_name' => 'bar',
                    'email' => 'test@mail.com',
                    'groups' => [
                        'role1', 'role2'
                    ],
                ],
            ]
        ];
    }

    /**
     * @dataProvider objectStateProvider
     *
     * @param array $arguments
     * @param array $expected
     *
     * @return void
     */
    public function testInitialObjectState(array $arguments, array $expected): void
    {
        $user = new User($arguments);
        self::assertSame($expected, $user->toArray());
    }
}
