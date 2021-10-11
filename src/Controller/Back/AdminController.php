<?php

namespace App\Controller\Back;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Quote;
use App\Entity\Task2;
use App\Entity\Customer;
use App\Entity\Appointment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        //Count Task
        $repoTask = $em->getRepository(Task::class);
        $totalTask = $repoTask->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        //Count Task P2
        $repoTaskP2 = $em->getRepository(Task2::class);
        $totalTaskP2 = $repoTaskP2->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        //Count Appointment
        $repoAppointment = $em->getRepository(Appointment::class);
        $totalAppointment = $repoAppointment->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        //Count Quote
        $repoQuote = $em->getRepository(Quote::class);
        $totalQuote = $repoQuote->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        //Count User
        $repoUser = $em->getRepository(User::class);
        $totalUser = $repoUser->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        //Count Customer
        $repoCustomer = $em->getRepository(Customer::class);
        $totalCustomer = $repoCustomer->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('back/index.html.twig', [
            'countTaskP1Board' => $totalTask, 
            'countTaskP2Board'=> $totalTaskP2,
            'countAppointment' => $totalAppointment,            
            'countQuote' => $totalQuote,
            'countUser' => $totalUser,
            'countCustomer' => $totalCustomer

        ]);
    }
}
