<?php

namespace App\Command;

use App\Repository\PluginsRepository;
use App\Service\Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DateAchatCommand extends Command
{
    protected static $defaultName = 'date-achat';
    // protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var PluginsRepository
     */
    private $pluginsRepository;
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(PluginsRepository $pluginsRepository, Mailer $mailer)
    {
        parent::__construct(null);
        $this->pluginsRepository = $pluginsRepository;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Date Purchased Reminder');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $plugin = $this->pluginsRepository->findAll();
        $expirationDate = $this->pluginsRepository->findBirthdayUsers();
        $expirationDateCount = count($expirationDate);

        if (count($plugin) === 0) {
            $io->error("There isn't any users in user table.");
            return -1;
        }

        if ($expirationDateCount === 0) {
            $io->note("Il y a aucun rappel de plugin à acheter");
            return 0;
        }

        $io->progressStart($expirationDateCount);
        foreach ($expirationDate as $expirationDate) {
            $io->progressAdvance();

            foreach ($plugin as $plugins) {
                // Skip birthday user reminder, it's his/her birthday!
                if ($plugins->getId() === $expirationDate->getId()) {
                    continue;
                }

                $this->mailer->sendReminder($expirationDate, $plugins);
            }

        }
        $io->progressFinish();
        $io->success("$expirationDateCount email(s) de renouvellement a été envoyé(s) !");

        return 0;
    }
}
