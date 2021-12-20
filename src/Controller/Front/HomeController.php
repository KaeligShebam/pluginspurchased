<?php

namespace App\Controller\Front;

use App\Entity\Plugins;
use App\Repository\PluginsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Front\PluginsPurchasedAddType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index(Request $request, PluginsRepository $plugins): Response
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
            'plugin' => $plugins->findBy([], ['name'=>'ASC']),
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

}
