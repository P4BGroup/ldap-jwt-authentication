P4BGroup Authentication
---

JWT Authentication service using LDAP connections for Phalcon Projects

[![Latest Stable Version](https://poser.pugx.org/p4bgroup/ldap-jwt-authentication/v/stable)](https://packagist.org/packages/p4bgroup/ldap-jwt-authentication) [![License](https://poser.pugx.org/p4bgroup/ldap-jwt-authentication/license)](https://packagist.org/packages/p4bgroup/ldap-jwt-authentication)  [![Maintainability](https://api.codeclimate.com/v1/badges/69d207e90a5a0382b6a7/maintainability)](https://codeclimate.com/github/P4BGroup/ldap-jwt-authentication/maintainability) [![Total Downloads](https://poser.pugx.org/p4bgroup/ldap-jwt-authentication/downloads)](https://packagist.org/packages/p4bgroup/ldap-jwt-authentication) [![Test Coverage](https://api.codeclimate.com/v1/badges/69d207e90a5a0382b6a7/test_coverage)](https://codeclimate.com/github/P4BGroup/ldap-jwt-authentication/test_coverage) [![Build Status](https://travis-ci.com/P4BGroup/ldap-jwt-authentication.svg?branch=master)](https://travis-ci.com/P4BGroup/ldap-jwt-authentication) [![composer.lock](https://poser.pugx.org/p4bgroup/ldap-jwt-authentication/composerlock)](https://packagist.org/packages/p4bgroup/ldap-jwt-authentication)

Usage
--- 

```php
$ldap = new Laminas\Ldap\Ldap([..]);
$authService = new AuthenticationService($ldap);
$jwtTokensService = new JWTTokensService($algorithm, $encodeKey, $decodeKey);

/** @param P4BGroup\Authentication\User $user */
$user = $authService->authenticateUser($username, $password, $userBaseDn);

$claims = new P4BGroup\Authentication\Claims();
$claims->setData($user->toArray())
->setSubject(Claims::SUBJECT_REFRESH)
->setExpirationTime(new DateTime("+10 minutes"))

$accessToken = $jwtTokensService->encode($claims);
$refreshToken = $jwtTokensService->encode($claims->setSubject(Claims::SUBJECT_REFRESH)->setExpirationTime(new DateTime("+1 hour"));
```

