<?php
//src/Command/SendEmailCommand.php
namespace App\Command;

use App\Entity\Plugins;
use App\Service\SendPluginsMail;
use Symfony\Component\Mime\Address;
use App\Repository\ThemesRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendThemesEmailCommand extends Command
{

    protected static $defaultName = 'reminder-themes';
    /**
     * @var ThemesRepository
     */
    private $themesRepository;
    /**
     * @var Mailer
     */
    private $mailer;

    protected function configure(): void
    {
        $this
            ->setHelp('Cette commande envoie un mail de rappel à la date d\'expiration');
    }

    public function __construct(ThemesRepository $themesRepository, SendPluginsMail $mailer)
    {
        parent::__construct();
        $this->themesRepository = $themesRepository;
        $this->mailer = $mailer;
    }
 
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);
        $themesExpiration = $this->themesRepository->findThemesExpirationDate();
        $themesListCount = count($themesExpiration);

        if($themesListCount === 0){
            $io->note("Il n'y a aucun theme qui arrivent à expiration");
        } else {
            $io->success("$themesListCount themes(s) et un mail envoyé !");
            $this->mailer->sendReminder($themesExpiration);
        }
        
        return Command::SUCCESS;
    }

}