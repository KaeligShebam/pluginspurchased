<?php

namespace App\Controller\Front;

use App\Entity\Themes;
use App\Repository\ThemesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Front\Themes\ThemesAddType;
use App\Form\Front\Themes\ThemesModifyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ThemesController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/themes", name="themes")
     */
    public function index(Request $request, ThemesRepository $themes): Response
    {

        $themesAdd = new Themes();
        $form = $this->createForm(ThemesAddType::class, $themesAdd);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($themesAdd);
            $this->entityManager->flush();
            return $this->redirectToRoute("themes");
        }

        return $this->render('front/themes/index.html.twig', [
            // 'notification' => $notification,
            'themes' => $themes->findBy([], ['name'=>'ASC']),
            'form_themes_add_front' => $form->createView()
        ]);

    }

    /**
     * @Route("/themes/modifier/id={id}", name="modify_theme_front")
     */
    public function modifyTaskP1Cw(Request $request, Themes $themeModify): Response
    {
        $form = $this->createForm(ThemesModifyType::class, $themeModify);
        $notification = null;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $themeModify = $form->getData();
            $this->entityManager->persist($themeModify);
            $this->entityManager->flush();
            $notification = 'Le thème a été mise à jour !';
            $form = $this->createForm(ThemesModifyType::class, $themeModify);
        }
        return $this->render('front/themes/modify.html.twig', [
            'form_theme_modify_front' => $form->createView(),
            'notification' => $notification,
            'themes' => $themeModify
        ]);
    }

    /**
     * @Route("/theme/supprimer/{id}", name="delete_theme_front")
     */
    public function deleteTask(Themes $themeDelete): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($themeDelete);
        $em->flush();

        return $this->redirectToRoute("themes");
    }

}
