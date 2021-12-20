<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Entity\Plugins;
use App\Repository\UserRepository;
use App\Repository\PluginsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Back\PluginsPurchasedModifyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(UserRepository $user, PluginsRepository $pluginsListAdmin): Response
    {
        return $this->render('back/index.html.twig', [
            // 'notification' => $notification,
            'user' => $user->findAll(),
            'plugin' => $pluginsListAdmin->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * @Route("/admin/plugin/modifier/id={id}/{name}", name="modify_plugin_back")
     */
    public function modifyTaskP1Cw(Request $request, Plugins $pluginsModify): Response
    {
        $form = $this->createForm(PluginsPurchasedModifyType::class, $pluginsModify);
        $notification = null;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pluginsModify = $form->getData();
            $this->entityManager->persist($pluginsModify);
            $this->entityManager->flush();
            $notification = 'Le plugin a été mise à jour !';
            $form = $this->createForm(PluginsPurchasedModifyType::class, $pluginsModify);
        }
        return $this->render('back/plugins/modify.html.twig', [
            'form_plugin_modify_back' => $form->createView(),
            'notification' => $notification,
            'plugins' => $pluginsModify
        ]);
    }

    /**
     * @Route("/admin/utilisateur/supprimer/", name="delete_user_back")
     */
    public function deleteUser(User $userDelete): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($userDelete);
        $em->flush();

        return $this->redirectToRoute("admin");
    }
}
