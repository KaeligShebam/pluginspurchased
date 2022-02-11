<?php

namespace App\Controller\Front;

use App\Entity\Plugins;
use App\Repository\PluginsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Front\PluginsPurchasedAddType;
use App\Form\Back\PluginsPurchasedModifyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PluginsController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/plugins", name="plugins")
     */
    public function index(Request $request, PluginsRepository $plugins): Response
    {

        $pluginsPurchasedAdd = new Plugins();
        $form = $this->createForm(PluginsPurchasedAddType::class, $pluginsPurchasedAdd);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($pluginsPurchasedAdd);
            $this->entityManager->flush();
            return $this->redirectToRoute("plugins");
        }

        return $this->render('front/plugins/index.html.twig', [
            // 'notification' => $notification,
            'plugin' => $plugins->findBy([], ['name'=>'ASC']),
            'form_plugin_add_front' => $form->createView()
        ]);

    }

    /**
     * @Route("/plugins/modifier/id={id}", name="modify_plugin_front")
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
        return $this->render('front/plugins/modify.html.twig', [
            'form_plugin_modify_back' => $form->createView(),
            'notification' => $notification,
            'plugins' => $pluginsModify
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

        return $this->redirectToRoute("plugins");
    }

}
