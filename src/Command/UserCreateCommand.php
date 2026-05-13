<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PasswordValidate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user-create',
    description: 'Create a new user.',
)]
class UserCreateCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;
    private PasswordValidate $passwordValidate;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        PasswordValidate $passwordValidate)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordValidate = $passwordValidate;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user')
            ->addArgument('role', InputArgument::OPTIONAL, 'The role of the user (e.g. ROLE_USER)', 'ROLE_USER')
        ;
    }

    protected function execute(
        InputInterface $input, 
        OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $role = $input->getArgument('role');

        $countuser = $this->userRepository->count([]);

        if($countuser >= 1) {
            $output->writeln("<error>Error: A user already exists!</error>");
            return Command::FAILURE;
        }

        if($this->passwordValidate::check($password)) {
            $output->writeln("<error>Error: The password must be between 6 and 255 characters long and contain at least one uppercase letter, one lowercase letter, one number, and may include the following special characters: $, @, #, + and -.</error>");
            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([$role]);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln("<info>Success: User <comment>$email</comment> has been created successfully!</info>");

        return Command::SUCCESS;
    }
}
