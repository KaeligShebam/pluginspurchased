<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\Back\User\AdminUserAddType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Back\User\AdminUserModifyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminUserController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/admin/utilisateurs", name="user_list_admin")
     */
    public function index(UserRepository $userAdmin): Response
    {
        return $this->render('back/user/list.html.twig', [
            'user' => $userAdmin->findBy(array(), array('firstname' => 'ASC')),
        ]);
    }

    /**
     * @Route("/admin/utilisateurs/{id}/supprimer", name="user_admin_delete")
     * @param User $user
     * return RedirectResponse
     */
    public function deleteUser(User $user): RedirectResponse {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute("user_list_admin");
    }
        /**
     * @Route("/admin/utilisateurs/ajouter", name="user_add_admin")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $encoder){
        $user = new User();
        $form = $this->createForm(AdminUserAddType:: class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $user = new User();
                $form = $this->createForm(AdminUserAddType:: class, $user);
                return $this->redirectToRoute("user_list_admin");
            }
        return $this->render('back/user/add.html.twig', [
            'form_admin_user_add' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/utilisateurs/{id}/modifier", name="user_modify_admin")
     */
    public function modifyUser(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(AdminUserModifyType:: class, $user);
        $notification = null;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notification = 'Informations mises à jour !';
                $user = new User();
                $user = $form->getData();
                $form = $this->createForm(AdminUserModifyType:: class, $user);
            
    }
        return $this->render('back/user/modify.html.twig',[
            'form_user_modify_admin' => $form->createView(),
            'notification' => $notification
        ]);   
    }
}
