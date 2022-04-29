<?php

namespace App\Controller\Front;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReminderController extends AbstractController
{
    /**
     * @Route("/liste", name="reminder_list")
     */
    public function index(): Response
    {
        return $this->render('front/reminder/index.html.twig');

    }

}
