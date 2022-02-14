<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Repository\MonthsContractsRepository;
use Symfony\Component\Mailer\MailerInterface;

class SendMonthsContractsMail
{
    private $mailer;
    /**
     * @var MonthsContractsRepository
     */

    public function __construct(MailerInterface $mailer, MonthsContractsRepository $monthsContractsRepository )
    {
        $this->mailer = $mailer;
        $this->monthsContractsRepository = $monthsContractsRepository;
    }

    public function sendReminderMonthsContracts(array $MonsthsContractsList)
    { 
        $message = (new  TemplatedEmail())
            ->to('support@shebam.fr')
            ->from(new Address('support@shebam.fr', 'Support WEB SHEBAM'))
            ->subject('Information - Rappels ')
            ->htmlTemplate('front/mails/mail-months-contracts.html.twig')
            ->context([
                'months' => $MonsthsContractsList
            ]);
        $this->mailer->send($message);
    }

}