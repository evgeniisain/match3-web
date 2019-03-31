<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

class UserController extends AbstractController {
    const AUTH_USERNAME = 'username';
    const AUTH_PASSWORD = 'password';

    protected $authenticationProvider;

    protected function __construct(AuthenticationProviderInterface $authenticationProvider)
    {
        $this->authenticationProvider = $authenticationProvider;
    }

    /**
     * @Route(
     *     path="/user/auth",
     *     name="user/auth",
     *     methods={"POST"}
     * )
     */
    protected function auth()
    {
        $username = $this->get(static::AUTH_USERNAME);
        $password = $this->get(static::AUTH_PASSWORD);

        // todo
    }
}