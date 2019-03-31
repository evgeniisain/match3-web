<?php

namespace App\Command;

use App\Entity\User;
use App\Service\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class CreateUserCommand extends Command
{
    const ARG_USERNAME = 'username';
    const ARG_PASSWORD = 'password';

    protected static $defaultName = 'app:create-user';

    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument(static::ARG_USERNAME, InputArgument::REQUIRED)
            ->addArgument(static::ARG_PASSWORD, InputArgument::REQUIRED)
            ->setDescription('Создание нового пользователя.')
            ->setHelp('Создание нового пользователя.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->userService->createUser(
                $input->getArgument(static::ARG_USERNAME),
                $input->getArgument(static::ARG_PASSWORD)
            );
        }
        catch (Throwable $exception) {
            $output->writeln($exception->getMessage());

            return;
        }

        $output->writeln('Пользователь добавлен.');
    }
}