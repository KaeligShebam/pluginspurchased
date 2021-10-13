<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Plugins;
use App\Repository\UserRepository;
use App\Repository\PluginsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Front\PluginsPurchasedAddType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request,PluginsRepository $pluginsList): Response
    {

        $pluginsPurchasedAdd = new Plugins();
        $form = $this->createForm(PluginsPurchasedAddType::class, $pluginsPurchasedAdd);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($pluginsPurchasedAdd);
            $this->entityManager->flush();
            return $this->redirectToRoute("home");
        }

        return $this->render('front/home/index.html.twig', [
            // 'notification' => $notification,
            'plugin' => $pluginsList->findBy([], ['name'=>'ASC']),
            'form_plugin_add_front' => $form->createView()
        ]);
    }

    /**
     * @Route("/plugins/supprimer/{id}", name="delete_plugin_front")
     */
    public function deleteTask(Plugins $pluginDelete): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pluginDelete);
        $em->flush();

        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(UserRepository $user, PluginsRepository $pluginsListAdmin): Response
    {


        return $this->render('front/admin/index.html.twig', [
            // 'notification' => $notification,
            'user' => $user->findAll(),
            'plugin' => $pluginsListAdmin->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * @Route("/utilisateur/supprimer/{id}", name="delete_user_front")
     */
    public function deleteUser(User $userDelete): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($userDelete);
        $em->flush();

        return $this->redirectToRoute("admin");
    }

}
