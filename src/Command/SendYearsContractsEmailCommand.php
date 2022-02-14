<?php
//src/Command/SendEmailCommand.php
namespace App\Command;

use App\Entity\Plugins;
use App\Service\SendYearsContractsMail;
use Symfony\Component\Mime\Address;
use App\Repository\YearsContractsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendYearsContractsEmailCommand extends Command
{

    protected static $defaultName = 'reminder-yearscontracts';
    /**
     * @var YearsContractsRepository
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

    public function __construct(YearsContractsRepository $yearsContractsRepository, SendYearsContractsMail $mailer)
    {
        parent::__construct();
        $this->yearsContractsRepository = $yearsContractsRepository;
        $this->mailer = $mailer;
    }
 
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);
        $yearsContractsExpiration = $this->yearsContractsRepository->findYearsContractsExpirationDate();
        $yearsContractsListCount = count($yearsContractsExpiration);

        if($yearsContractsListCount === 0){
            $io->note("Il n'y a aucun contrat mensuel qui arrivent à expiration");
        } else {
            $io->success("$yearsContractsListCount contrat(s) annuel(s) et un mail envoyé !");
            $this->mailer->sendReminderYearsContracts($yearsContractsExpiration);
        }
        
        return Command::SUCCESS;
    }

}