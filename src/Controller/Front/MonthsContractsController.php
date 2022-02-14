<?php

namespace App\Controller\Front;

use App\Entity\MonthsContracts;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Front\MonthsContracts\MonthsContractsAddType;
use App\Form\Front\MonthsContracts\MonthsContractsModifyType;
use App\Repository\MonthsContractsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MonthsContractsController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/contrats-mensuels", name="monthscontracts")
     */
    public function index(Request $request, MonthsContractsRepository $monthsContracts): Response
    {
        $monthsContratcsForm = new MonthsContracts();
        $form = $this->createForm(MonthsContractsAddType::class, $monthsContratcsForm);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($monthsContratcsForm);
            $this->entityManager->flush();
            return $this->redirectToRoute("monthscontracts");
        }

        return $this->render('front/monthscontracts/index.html.twig', [
            'months' => $monthsContracts->findBy([], ['name' => 'ASC']),
            'form_months_contracts_add_front' => $form->createView()
        ]);
    }

    /**
     * @Route("/contrats-mensuels/modifier/id={id}", name="modify_monthcontract_front")
     */
    public function modifyTaskP1Cw(Request $request, MonthsContracts $monthContractModify): Response
    {
        $form = $this->createForm(MonthsContractsModifyType::class, $monthContractModify);
        $notification = null;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $monthContractModify = $form->getData();
            $this->entityManager->persist($monthContractModify);
            $this->entityManager->flush();
            $notification = 'Le contrat mensuel a été mise à jour !';
            $form = $this->createForm(MonthsContractsModifyType::class, $monthContractModify);
        }
        return $this->render('front/monthscontracts/modify.html.twig', [
            'form_months_contract_modify_front' => $form->createView(),
            'notification' => $notification,
            'months' => $monthContractModify
        ]);
    }

    /**
     * @Route("/contrats-mensuels/supprimer/{id}", name="delete_monthcontract_front")
     */
    public function deleteMonthsContracts(MonthsContracts $MonthsContractsDelete): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($MonthsContractsDelete);
        $em->flush();

        return $this->redirectToRoute("monthscontracts");
    }

}
