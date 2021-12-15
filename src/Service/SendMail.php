<?php

namespace App\Service;

use App\Entity\Plugins;
use Symfony\Component\Mime\Address;
use App\Repository\PluginsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMail
{
    private $mailer;
    /**
     * @var PluginsRepository
     */

    public function __construct(MailerInterface $mailer, PluginsRepository $pluginRepository )
    {
        $this->mailer = $mailer;
        $this->pluginRepository = $pluginRepository;
    }

    public function sendReminder(array $pluginsList)
    { 
        $message = (new  TemplatedEmail())
            ->to('support@shebam.fr')
            ->from(new Address('support@shebam.fr', 'Support WEB SHEBAM'))
            ->subject('Information - Rappel ')
            ->htmlTemplate('front/home/mail.html.twig')
            ->context([
                'plugins' => $pluginsList
            ]);
        $this->mailer->send($message);
    }

}