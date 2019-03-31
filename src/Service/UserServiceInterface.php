<?php


namespace App\Service;

use App\Entity\User;

interface UserServiceInterface
{
    /**
     * Создание нового пользователя.
     *
     * @param string $username Имя пользователя.
     * @param string $password Пароль.
     *
     * @return User
     */
    public function createUser(string $username, string $password): User;
}