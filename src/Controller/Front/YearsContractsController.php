<?php

namespace App\Controller\Front;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class YearsContractsController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/contrats-tickets", name="yearscontracts")
     */
    public function index(): Response
    {
        return $this->render('front/yearscontract/index.html.twig');

    }

}
