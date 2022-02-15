<?php

namespace App\Controller\Front;

use App\Entity\TicketsShebamWeb;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TicketsShebamWebRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\Front\TicketsShebamWeb\TicketsShebamWebAddType;
use App\Form\Front\TicketsShebamWeb\TicketsShebamWebModifyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketsShebamWEBController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/tickets-web-shebam", name="tickets_shebam_web")
     */
    public function index(Request $request, TicketsShebamWebRepository $ticketsShebamWeb): Response
    {
        $ticketsShebamWebForm = new TicketsShebamWeb();
        $form = $this->createForm(TicketsShebamWebAddType::class, $ticketsShebamWebForm);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ticketsShebamWebForm);
            $this->entityManager->flush();
            return $this->redirectToRoute("tickets_shebam_web");
        }

        return $this->render('front/tickets_shebam_web/index.html.twig', [
            'tickets' => $ticketsShebamWeb->findBy([], ['customer' => 'ASC']),
            'form_tickets_shebam_web_add_front' => $form->createView()
        ]);
    }

    /**
     * @Route("/tickets-web-shebam/modifier/id={id}", name="modify_tickets_shebam_web_front")
     */
    public function modifyTaskP1Cw(Request $request, TicketsShebamWeb $TicketsShebamWebModify): Response
    {
        $form = $this->createForm(TicketsShebamWebModifyType::class, $TicketsShebamWebModify);
        $notification = null;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $TicketsShebamWebModify = $form->getData();
            $this->entityManager->persist($TicketsShebamWebModify);
            $this->entityManager->flush();
            $notification = 'Le contrat annuel a été mise à jour !';
            $form = $this->createForm(TicketsShebamWebModifyType::class, $TicketsShebamWebModify);
        }
        return $this->render('front/tickets_shebam_web/modify.html.twig', [
            'form_tickets_shebam_web_modify_front' => $form->createView(),
            'notification' => $notification,
            'tickets' => $TicketsShebamWebModify
        ]);
    }

    /**
     * @Route("/tickets-web-shebam/supprimer/{id}", name="delete_tickets_shebam_web_front")
     */
    public function deleteMonthsContracts(TicketsShebamWeb $ticketsShebamWebDelete): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($ticketsShebamWebDelete);
        $em->flush();

        return $this->redirectToRoute("tickets_shebam_web");
    }

}
