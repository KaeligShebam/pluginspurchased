<?php

namespace App\Controller\Front;

use App\Entity\YearsContracts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\Front\YearsContracts\YearsContractsAddType;
use App\Form\Front\YearsContracts\YearsContractsModifyType;
use App\Repository\YearsContractsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class YearsContractsController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/contrats-annuels", name="yearscontracts")
     */
    public function index(Request $request, YearsContractsRepository $yearsContracts): Response
    {
        $yearsContratcsForm = new YearsContracts();
        $form = $this->createForm(YearsContractsAddType::class, $yearsContratcsForm);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($yearsContratcsForm);
            $this->entityManager->flush();
            return $this->redirectToRoute("yearscontracts");
        }

        return $this->render('front/yearscontracts/index.html.twig', [
            'years' => $yearsContracts->findBy([], ['name' => 'ASC']),
            'form_years_contracts_add_front' => $form->createView()
        ]);
    }

    /**
     * @Route("/contrats-annuels/modifier/id={id}", name="modify_yearcontract_front")
     */
    public function modifyTaskP1Cw(Request $request, YearsContracts $yearsContractModify): Response
    {
        $form = $this->createForm(YearsContractsModifyType::class, $yearsContractModify);
        $notification = null;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $yearsContractModify = $form->getData();
            $this->entityManager->persist($yearsContractModify);
            $this->entityManager->flush();
            $notification = 'Le contrat annuel a été mise à jour !';
            $form = $this->createForm(YearsContractsModifyType::class, $yearsContractModify);
        }
        return $this->render('front/yearscontracts/modify.html.twig', [
            'form_years_contracts_modify_front' => $form->createView(),
            'notification' => $notification,
            'years' => $yearsContractModify
        ]);
    }

    /**
     * @Route("/contrats-annuels/supprimer/{id}", name="delete_yearcontract_front")
     */
    public function deleteMonthsContracts(YearsContracts $YearsContractsDelete): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($YearsContractsDelete);
        $em->flush();

        return $this->redirectToRoute("yearscontracts");
    }

}
