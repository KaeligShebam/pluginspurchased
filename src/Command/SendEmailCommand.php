<?php
//src/Command/SendEmailCommand.php
namespace App\Command;

use App\Entity\Plugins;
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
        $pluginExpiration = $this->pluginRepository->findExpirationDate();
        $pluginListCount = count($pluginExpiration);

        if($pluginListCount === 0){
            $io->note("Il n'y a aucun plugin qui arrivent à expiration");
        } else {
            $io->success("$pluginListCount plugin(s) et un mail envoyé !");
            $this->mailer->sendReminder($pluginExpiration);
        }
        
        return Command::SUCCESS;
    }

}