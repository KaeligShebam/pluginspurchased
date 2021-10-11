<?php

namespace App\Controller\Front;

use App\Repository\TaskRepository;
use App\Repository\QuoteRepository;
use App\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArchivedTasksController extends AbstractController
{
    /**
     * @Route("/taches-archivees", name="tasks_archived_home")
     */
    public function index(TaskRepository $tasksHomeArchived, AppointmentRepository $appointmentHomeArchived, QuoteRepository $quoteHomeArchived): Response
    {
        return $this->render('front/tasks_archived/index.html.twig', [
            'task' => $tasksHomeArchived->findBy([], ['position' => 'ASC']),
            'appointment' => $appointmentHomeArchived->findBy([], ['position' => 'ASC', 'hoursappointment' => 'DESC']),
            'quote' => $quoteHomeArchived->findBy([], ['position' => 'ASC']),
        ]);
    }

}