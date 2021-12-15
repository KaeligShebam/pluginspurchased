<?php
//src/Command/SendEmailCommand.php
namespace App\Command;

use App\Service\SendMail;
use Symfony\Component\Mime\Address;
use App\Repository\PluginsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends Command
{

    protected static $defaultName = 'date-achat';
    /**
     * @var PluginsRepository
     */
    private $pluginRepository;
    /**
     * @var Mailer
     */
    private $mailer;

    protected function configure(): void
    {
        $this
            ->setHelp('Cette commande envoie un mail de rappel à la date d\'expiration');
    }

    public function __construct(PluginsRepository $pluginRepository, SendMail $mailer)
    {
        parent::__construct();
        $this->pluginRepository = $pluginRepository;
        
        $this->mailer = $mailer;
    }
 
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);

        $plugins = $this->pluginRepository->findAll();
        $pluginDate = $this->pluginRepository->findBirthdayUsers();
        $pluginDateCount = count($pluginDate);

        if($pluginDateCount === 0){
            $io->progressStart($pluginDateCount);
            $io->note("Il n'y a aucun plugin qui arrivent à expiration");
            $io->success("$pluginDateCount mail envoyé");
        } elseif($pluginDateCount === 1) {
            $io->progressStart($pluginDateCount);
            $io->success("$pluginDateCount mail envoyé");
            $this->mailer->sendReminder();
        }
        $io->progressFinish();
        
        return Command::SUCCESS;
    }

}
