<?php

namespace App\Controller\Back;

use App\Entity\MonthlysSupport;
use App\Entity\User;
use App\Entity\Plugins;
use App\Entity\TicketsShebamWeb;
use App\Repository\UserRepository;
use App\Repository\PluginsRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        $em = $this->getDoctrine()->getManager();

        $repoPlugins = $em->getRepository(Plugins::class);
        $totalPlugins = $repoPlugins->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $repoMonthlysSupport = $em->getRepository(MonthlysSupport::class);
        $totalMonthlysSupport = $repoMonthlysSupport->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $repoTicketsShebamWeb = $em->getRepository(TicketsShebamWeb::class);
        $totalTicketsShebamWeb = $repoTicketsShebamWeb->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('back/index.html.twig', [
            'user' => $user->findBy([], ['lastname' => 'ASC']),
            'plugin' => $pluginsListAdmin->findBy([], ['name' => 'ASC']),
            'totalPlugins' => $totalPlugins,
            'totalMonthlysSupport' => $totalMonthlysSupport,
            'totalTicketsShebamWeb' => $totalTicketsShebamWeb,
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
