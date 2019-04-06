P4BGroup Authentication
---

JWT Authentication service using LDAP connections for Phalcon Projects


Usage
--- 

```php
$ldap = new Zend\Ldap\Ldap([..]);
$authService = new AuthenticationService($ldap);
$jwtTokensService = new JWTTokensService();

/** @param P4BGroup\Authentication\User $user */
$user = $authService->authenticateUser($username, $password, $userBaseDn);

$claims = new P4BGroup\Authentication\Claims();
$claims->setData($user->toArray())
->setSubject(Claims::SUBJECT_REFRESH)
->setExpirationTime(new DateTime(+10 minutes))

$accessToken = $jwtTokensService->encode($claims);
$refreshToken = $jwtTokensService->encode($claims->withSubject(Claims::SUBJECT_REFRESH)->withExpirationTime(new DateTime("+1 hour"));
```

Q&A
---
Q: In claims there are 2 methods to set subject - withSubject and setSubject. What is the difference?  
A: The difference is that "with*" methods will create a new reference of the claims object.
