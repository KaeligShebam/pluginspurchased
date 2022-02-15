<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use App\Repository\TicketsShebamWebRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendTicketsShebamWebMail
{
    private $mailer;
    /**
     * @var TicketsShebamWebRepository
     */

    public function __construct(MailerInterface $mailer, TicketsShebamWebRepository $TicketsShebamWebRepository)
    {
        $this->mailer = $mailer;
        $this->TicketsShebamWebRepository = $TicketsShebamWebRepository;
    }

    public function sendReminderTicketsShebamWeb(array $ticketsList)
    {
        $message = (new  TemplatedEmail())
            ->to('support@shebam.fr')
            ->from(new Address('support@shebam.fr', 'Support WEB SHEBAM'))
            ->subject('Information de Rappel ')
            ->htmlTemplate('front/mails/mail-tickets-web-shebam.html.twig')
            ->context([
                'tickets' => $ticketsList
            ]);
        $this->mailer->send($message);
    }
}
