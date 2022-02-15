<?php
//src/Command/SendEmailCommand.php
namespace App\Command;

use App\Service\SendMonthlysSupportMail;
use Symfony\Component\Mime\Address;
use App\Repository\MonthlysSupportRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMonthlysSupportEmailCommand extends Command
{

    protected static $defaultName = 'reminder-monthlys-support';
    /**
     * @var MonthlysSupportRepository
     */
    private $monthlysSupportRepository;
    /**
     * @var Mailer
     */
    private $mailer;

    protected function configure(): void
    {
        $this
            ->setHelp('Cette commande envoie un mail de rappel à la date d\'expiration');
    }

    public function __construct(MonthlysSupportRepository $monthlysSupportRepository, SendMonthlysSupportMail $mailer)
    {
        parent::__construct();
        $this->monthlysSupportRepository = $monthlysSupportRepository;
        $this->mailer = $mailer;
    }
 
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);
        $monthlysSupportExpiration = $this->monthlysSupportRepository->findMonthlysSupportExpirationDate();
        $monthlysSupportListCount = count($monthlysSupportExpiration);

        if($monthlysSupportListCount === 0){
            $io->note("Il n'y a aucun contrat mensuel qui arrivent à expiration");
        } else {
            $io->success("$monthlysSupportListCount accompagnement(s) mensuel(s) et un mail envoyé !");
            $this->mailer->sendReminderMonthlysSupport($monthlysSupportExpiration);
        }
        
        return Command::SUCCESS;
    }

}