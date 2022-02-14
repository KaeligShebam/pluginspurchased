<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use App\Repository\YearsContractsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendYearsContractsMail
{
    private $mailer;
    /**
     * @var YearsRepository
     */

    public function __construct(MailerInterface $mailer, YearsContractsRepository $yearsRepository)
    {
        $this->mailer = $mailer;
        $this->yearsRepository = $yearsRepository;
    }

    public function sendReminderYearsContracts(array $yearsList)
    {
        $message = (new  TemplatedEmail())
            ->to('support@shebam.fr')
            ->from(new Address('support@shebam.fr', 'Support WEB SHEBAM'))
            ->subject('Information - Rappels ')
            ->htmlTemplate('front/mails/mail-years-contracts.html.twig')
            ->context([
                'years' => $yearsList
            ]);
        $this->mailer->send($message);
    }
}
