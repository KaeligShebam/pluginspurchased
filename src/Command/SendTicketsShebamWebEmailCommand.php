<?php
namespace App\Command;

use App\Entity\Plugins;
use App\Repository\TicketsShebamWebRepository;
use App\Service\SendTicketsShebamWebMail;
use Symfony\Component\Mime\Address;
use App\Repository\YearsContractsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendTicketsShebamWebEmailCommand extends Command
{

    protected static $defaultName = 'reminder-tickets-web-shebam';
    /**
     * @var TicketsShebamWebRepository
     */
    private $yearsContractsRepository;
    /**
     * @var Mailer
     */
    private $mailer;

    protected function configure(): void
    {
        $this
            ->setHelp('Cette commande envoie un mail de rappel à la date d\'expiration');
    }

    public function __construct(TicketsShebamWebRepository $ticketsShebamWebRepository, SendTicketsShebamWebMail $mailer)
    {
        parent::__construct();
        $this->yearsContractsRepository = $ticketsShebamWebRepository;
        $this->mailer = $mailer;
    }
 
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);
        $ticketsShebamWebExpiration = $this->yearsContractsRepository->findTicketsShebamWebExpirationDate();
        $ticketsShebamWebListCount = count($ticketsShebamWebExpiration);

        if($ticketsShebamWebListCount === 0){
            $io->note("Il n'y a aucun ticket WEB Shebam qui arrivent à expiration");
        } else {
            $io->success("$ticketsShebamWebListCount contrat(s) annuel(s) et un mail envoyé !");
            $this->mailer->sendReminderTicketsShebamWeb($ticketsShebamWebExpiration);
        }
        
        return Command::SUCCESS;
    }

}