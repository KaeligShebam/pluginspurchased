<?php

namespace App\Controller\Front;

use App\Entity\MonthlysSupport;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Front\MonthlysSupport\MonthlysSupportAddType;
use App\Form\Front\MonthlysSupport\MonthlysSupportModifyType;
use App\Repository\MonthlysSupportRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MonthlysSupportController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/accompagnements-mensuels", name="monthlys_support")
     */
    public function index(Request $request, MonthlysSupportRepository $monthlysSupport): Response
    {
        $monthlysSupportForm = new MonthlysSupport();
        $form = $this->createForm(MonthlysSupportAddType::class, $monthlysSupportForm);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($monthlysSupportForm);
            $this->entityManager->flush();
            return $this->redirectToRoute("monthlys_support");
        }

        return $this->render('front/monthlys-support/index.html.twig', [
            'monthlys' => $monthlysSupport->findBy([], ['customer' => 'ASC']),
            'form_monthlys_support_add_front' => $form->createView()
        ]);
    }

    /**
     * @Route("/accompagnements-mensuels/modifier/id={id}", name="modify_monthly_support_front")
     */
    public function modifyMonthlysSupport(Request $request, MonthlysSupport $monthlysSupportModify): Response
    {
        $form = $this->createForm(MonthlysSupportModifyType::class, $monthlysSupportModify);
        $notification = null;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $monthlysSupportModify = $form->getData();
            $this->entityManager->persist($monthlysSupportModify);
            $this->entityManager->flush();
            $notification = 'L\'accompagnement mensuel a été mise à jour !';
            $form = $this->createForm(MonthlysSupportModifyType::class, $monthlysSupportModify);
        }
        return $this->render('front/monthlys-support/modify.html.twig', [
            'form_monthlys_support_modify_front' => $form->createView(),
            'notification' => $notification,
            'monthlys' => $monthlysSupportModify
        ]);
    }

    /**
     * @Route("/accompagnements-mensuels/supprimer/{id}", name="delete_monthly_support_front")
     */
    public function deleteMonthlysSupport(MonthlysSupport $MonthlysSupportDelete): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($MonthlysSupportDelete);
        $em->flush();

        return $this->redirectToRoute("monthlys_support");
    }

}
