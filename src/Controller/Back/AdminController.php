<?php

namespace App\Controller\Back;

use App\Entity\MonthsContracts;
use App\Entity\User;
use App\Entity\Plugins;
use App\Entity\YearsContracts;
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
        $em = $this->getDoctrine()->getManager();

        $repoPlugins = $em->getRepository(Plugins::class);
        $totalPlugins = $repoPlugins->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $repoMonthsContacts = $em->getRepository(MonthsContracts::class);
        $totalMonthsContracts = $repoMonthsContacts->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $repoYearsContacts = $em->getRepository(YearsContracts::class);
        $totalYearsContracts = $repoYearsContacts->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('back/index.html.twig', [
            'user' => $user->findAll(),
            'plugin' => $pluginsListAdmin->findBy([], ['name' => 'ASC']),
            'totalPlugins' => $totalPlugins,
            'totalMonthsContracts' => $totalMonthsContracts,
            'totalYearsContracts' => $totalYearsContracts,
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
