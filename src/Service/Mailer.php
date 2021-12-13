<?php

namespace App\Service;

use App\Entity\Plugins;
use App\Repository\PluginsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private $mailer;
    /**
     * @var PluginsRepository
     */
    private $pluginsRepository;

    public function __construct(MailerInterface $mailer, PluginsRepository $pluginsRepository)
    {
        $this->mailer = $mailer;
        $this->pluginsRepository = $pluginsRepository;
    }

    public function sendReminder(Plugins $expirationDate)
    {

        $message = (new TemplatedEmail())
            ->from('kaelig@shebam.fr')
            ->to('support@shebam.fr')
            ->subject('1 week later'. $expirationDate->getExpirationdate().'date expiration')
            ->html('test date')
        ;

        $this->mailer->send($message);
    }
}