<?php

namespace P4BGroup\Authentication;

use Exception;
use Zend\Ldap\Filter\AndFilter;
use Zend\Ldap\Filter\OrFilter;
use Zend\Ldap\Ldap;

class AuthenticationService
{
    /**
     * @var Ldap
     */
    private $ldapConnection;

    /**
     * @param Ldap $ldapConnection
     */
    public function __construct(Ldap $ldapConnection)
    {
        $this->ldapConnection = $ldapConnection;
    }

    /**
     * Authenticate a user. check the credentials and return the logged in user
     *
     * @param string $username
     * @param string $password
     * @param string|null $baseDn
     *
     * @return User
     * @throws AuthenticationException
     *
     */
    public function authenticateUser(string $username, string $password, string $baseDn = null): User
    {
        $filters = new AndFilter([
            'objectclass=user',
            new OrFilter([
                'sAMAccountName=' . $username,
                'mail=' . $username,
                'mailNickname=' . $username,
                'userPrincipalName=' . $username,
            ])
        ]);

        try {
            if ($baseDn !== null) {
                $this->ldapConnection->setOptions(array_merge($this->ldapConnection->getOptions(), [
                    'baseDn' => $baseDn
                ]));
            }

            $users = $this->ldapConnection->search($filters, $baseDn, Ldap::SEARCH_SCOPE_ONE);

            $this->ldapConnection->bind($users->dn(), $password);
            $user = $this->ldapConnection->getEntry($this->ldapConnection->getBoundUser());
        } catch (Exception $exception) {
            throw new AuthenticationException('AUTHENTICATION_FAILED', 400, $exception);
        }

        if (empty($user ?? [])) {
            throw new AuthenticationException('USER_INVALID');
        }

        return new User($user);
    }
}
