<?php

namespace P4BGroup\Authentication;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Zend\Ldap\Collection;
use Zend\Ldap\Filter\AbstractLogicalFilter;
use Zend\Ldap\Ldap;

class AuthenticationServiceTest extends TestCase
{
    /**
     * @var MockObject|Ldap
     */
    private $ldapService;
    /**
     * @var AuthenticationService
     */
    private $authService;

    /**
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        $this->ldapService = $this->createMock(Ldap::class);
        $this->authService = new AuthenticationService($this->ldapService);
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testAuthenticateUser(): void
    {
        $collection = $this->createMock(Collection::class);
        
        $this->ldapService
            ->expects(self::once())
            ->method('setOptions')
            ->with(['baseDn' => 'foo.bar.dn']);

        $this->ldapService
            ->expects(self::once())
            ->method('search')
            ->with(
                self::isInstanceOf(AbstractLogicalFilter::class),
                'foo.bar.dn',
                Ldap::SEARCH_SCOPE_ONE
            )
            ->willReturn($collection);

        $this->ldapService
            ->expects(self::once())
            ->method('getEntry')
            ->with(null, [], false)
            ->willReturn(['foo' => 'bar']);

        $this->authService->authenticateUser('foo.bar.username', 'foo.bar.password', 'foo.bar.dn');
    }
}
