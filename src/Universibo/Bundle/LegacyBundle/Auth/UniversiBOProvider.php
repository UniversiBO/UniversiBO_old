<?php
namespace Universibo\Bundle\LegacyBundle\Auth;

use Universibo\Bundle\CoreBundle\Entity\UserRepository;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

class UniversiBOProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;

    public function __construct(UserRepository $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof UniversiBOToken;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->find($token->getId());

        if ($user === false) {
            throw new AuthenticationException('User not found!');
        }
        $token = new UniversiBOToken($user->getRoles());
        $token->setUser($user);
        $token->setAuthenticated(true);

        return $token;
    }
}
