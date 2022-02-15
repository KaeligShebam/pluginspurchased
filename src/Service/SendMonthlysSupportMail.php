<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Repository\MonthlysSupportRepository;
use Symfony\Component\Mailer\MailerInterface;

class SendMonthlysSupportMail
{
    private $mailer;
    /**
     * @var MonthlysSupportRepository
     */

    public function __construct(MailerInterface $mailer, MonthlysSupportRepository $monthlysSupportRepository )
    {
        $this->mailer = $mailer;
        $this->monthlysSupportRepository = $monthlysSupportRepository;
    }

    public function sendReminderMonthlysSupport(array $monthlysSupportList)
    { 
        $message = (new  TemplatedEmail())
            ->to('support@shebam.fr')
            ->from(new Address('support@shebam.fr', 'Support WEB SHEBAM'))
            ->subject('Information de rappel ')
            ->htmlTemplate('front/mails/mail-monthlys-support.html.twig')
            ->context([
                'monthlys' => $monthlysSupportList
            ]);
        $this->mailer->send($message);
    }

}