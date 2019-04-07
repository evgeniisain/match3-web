<?php

namespace App\Controller;

use App\Entity\User;
use App\Model\RegisterForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController {
    const AUTH_USERNAME = 'username';
    const AUTH_PASSWORD = 'password';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getTokenBuilder(string $username): Builder {
        return (new Builder)
            ->set('username', $username)
            ->setExpiration(60 * 60 * 24)
        ;
    }

    protected function getTokenParser(string $data) {
        return new Parser();
    }

    /**
     * @Route(
     *     path="/user/auth",
     *     name="user/auth",
     *     methods={"POST"}
     * )
     */
    protected function auth(Request $request)
    {
        $username = $request->get(static::AUTH_USERNAME);
        $password = $request->get(static::AUTH_PASSWORD);

        // validate

        // todo USE AUTH SERVICE

        // check user for auth
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findByUsername($username);

        if (null === $user) {
            return $this->json([], Response::HTTP_FORBIDDEN);
        }

        // todo hash -> PasswordEncoderInterface
        if ($user->getPassword() !== hash('sha1', $password)) {
            return $this->json([], Response::HTTP_FORBIDDEN);
        }

        // generate token and cache it (symfony/cache, setup memcache config)
        $tokenBuider = $this->getTokenBuilder($username);

        // generate token

        return $this->json(['token' => $tokenBuider->getToken()]);
    }

    /**
     * @Route(
     *     path="/user/check-auth",
     *     name="user/check-auth",
     *     methods={"GET"}
     * )
     */
    protected function checkAuth(Request $request)
    {
        $tokenString = $request->get('token');

        // Validate data

        // todo Validate data

        // parse token
        $this->getTokenParser($tokenString);
    }

    /**
     * @Route(
     *     path="/user/register",
     *     name="user/register",
     *     methods={"POST"}
     * )
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param PasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function register(Request $request, ValidatorInterface $validator, PasswordEncoderInterface $passwordEncoder)
    {
        $form = new RegisterForm($request);
        if (false === $form->validate($validator)) {
            return $this->json([], Response::HTTP_FORBIDDEN);
        }

        $user = new User;
        $user->setUsername($form->username);
        $user->setPassword($passwordEncoder->encodePassword($form->password, ''));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_CREATED);
    }
}