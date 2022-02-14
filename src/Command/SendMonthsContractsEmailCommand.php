<?php
//src/Command/SendEmailCommand.php
namespace App\Command;

use App\Service\SendMonthsContractsMail;
use Symfony\Component\Mime\Address;
use App\Repository\MonthsContractsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMonthsContractsEmailCommand extends Command
{

    protected static $defaultName = 'reminder-monthscontracts';
    /**
     * @var MonthsContractsRepository
     */
    private $monthsContractsRepository;
    /**
     * @var Mailer
     */
    private $mailer;

    protected function configure(): void
    {
        $this
            ->setHelp('Cette commande envoie un mail de rappel à la date d\'expiration');
    }

    public function __construct(MonthsContractsRepository $monthsContractsRepository, SendMonthsContractsMail $mailer)
    {
        parent::__construct();
        $this->monthsContractsRepository = $monthsContractsRepository;
        $this->mailer = $mailer;
    }
 
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);
        $monthsContractsExpiration = $this->monthsContractsRepository->findMonthsContractsExpirationDate();
        $monthsContractsListCount = count($monthsContractsExpiration);

        if($monthsContractsListCount === 0){
            $io->note("Il n'y a aucun contrat mensuel qui arrivent à expiration");
        } else {
            $io->success("$monthsContractsListCount contrat(s) mensuel(s) et un mail envoyé !");
            $this->mailer->sendReminderMonthsContracts($monthsContractsExpiration);
        }
        
        return Command::SUCCESS;
    }

}