<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Ramsey\Uuid\Uuid;

class CreateUserCommand extends Command
{

    protected static $defaultName = 'app:user:create';

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'admin\'s email'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'admin\'s password'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('password');

        if (!\is_string($email)) {
            $output->writeln('<error>Por favor, especifica un email válido</error>');
            return Command::FAILURE;
        }

        if (!\is_string($plainPassword)) {
            $output->writeln('<error>Por favor, especifica una contraseña válida</error>');
            return Command::FAILURE;
        }

        $user = new User(
            Uuid::uuid4(),
            $email
        );
        $password = $this->userPasswordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($password);
        $this->userRepository->save($user);

        $output->writeln(sprintf('Created user with email: <comment>%s</comment>', $email));
        return Command::SUCCESS;
    }
}
