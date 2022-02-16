<?php

namespace App\Controller\Front;

use App\Entity\Plugins;
use App\Form\ChangeCoordsType;
use App\Entity\MonthlysSupport;
use App\Entity\TicketsShebamWeb;
use App\Repository\TaskRepository;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte", name="account")
     */

    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(ChangeCoordsType::class, $user);

        $notification = null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $old_pwd = $form->get('old_password')->getData();
            if ($encoder->isPasswordValid($user, $old_pwd)){
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_pwd);

                $user->setPassword($password);
                $this->entityManager->flush();
                $notification = 'Les coordonnées ont été changées';
            } else {
                $notification = 'L\'ancien mot de passe n\'est pas correct';
            }
        }

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
        return $this->render('front/account/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'totalPlugins' => $totalPlugins,
            'totalMonthlysSupport' => $totalMonthlysSupport,
            'totalTicketsShebamWeb' => $totalTicketsShebamWeb,
        ]);

    }
}
