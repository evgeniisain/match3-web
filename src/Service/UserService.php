<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService implements UserServiceInterface
{
    protected $userRepository;
    protected $validator;

    protected function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function createUser(string $username, string $password): User
    {
        $user = new User;
        $user->setUsername($username);
        $user->setPassword($password);

        $violations = $this->validator->validate($user);
        if ($violations->count() > 0) {
            throw new Exception($violations->get(0)->getMessage());
        }

        $this->userRepository->save($user);

        return $user;
    }
}