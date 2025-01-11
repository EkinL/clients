<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-app-secret',
    description: 'Generate a new app secret',
)]
class GenerateAppSecretCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('length', InputArgument::OPTIONAL, 'Length of the secret', 32)
            ->addOption('uppercase', null, InputOption::VALUE_NONE, 'Include uppercase letters')
            ->addOption('numbers', null, InputOption::VALUE_NONE, 'Include numbers')
            ->addOption('symbols', null, InputOption::VALUE_NONE, 'Include symbols')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $length = (int) $input->getArgument('length');
        $includeUppercase = $input->getOption('uppercase');
        $includeNumbers = $input->getOption('numbers');
        $includeSymbols = $input->getOption('symbols');

        $characters = 'abcdefghijklmnopqrstuvwxyz';
        if ($includeUppercase) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($includeNumbers) {
            $characters .= '0123456789';
        }
        if ($includeSymbols) {
            $characters .= '!@#$%^&*()_+-=[]{}|;:,.<>?';
        }

        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $characters[random_int(0, strlen($characters) - 1)];
        }

        // Update the APP_SECRET in the .env file
        $envFilePath = dirname(__DIR__, 2) . '/.env';
        if (file_exists($envFilePath)) {
            $envContent = file_get_contents($envFilePath);
            $envContent = preg_replace('/^APP_SECRET=.*$/m', 'APP_SECRET=' . $secret, $envContent);
            file_put_contents($envFilePath, $envContent);
            $io->success('Generated app secret and updated .env file: ' . $secret);
        } else {
            $io->error('.env file not found.');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}