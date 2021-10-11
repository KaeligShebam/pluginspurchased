<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Back\Customer\AdminCustomerAddType;
use App\Form\Back\Customer\AdminCustomerModifyType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCustomerController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
    $this->entityManager = $entityManager;
    }
    /**
     * @Route("/admin/client", name="customer_list_admin")
     */
    public function listCustomers(CustomerRepository $customerAdmin): Response
    {
        //Count Customer
        $em = $this->getDoctrine()->getManager();
        $repoCustomer = $em->getRepository(Customer::class);
        $totalCustomer = $repoCustomer->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('back/customer/list.html.twig', [
            'customer' => $customerAdmin->findBy(array(), array('name' => 'ASC')),
            'totalCustomer' => $totalCustomer
        ]);
    }

    /**
     * @Route("/admin/client/ajouter", name="customer_add_admin")
     */
    public function index(Request $request): Response {
        $customerAdd = new Customer();
        $form = $this->createForm(AdminCustomerAddType::class, $customerAdd);
        $notification = null;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($customerAdd);
            $this->entityManager->flush();
            $notification = 'Le client a bien été ajouté';
            $customerAdd = new Customer();
            $form = $this->createForm(AdminCustomerAddType::class, $customerAdd);
        }
            return $this->render('back/customer/add.html.twig', [
                'form_admin_customer_add' => $form->createView(),
                'notification' => $notification
            ]);
        }

    /**
     * @Route("/admin/client/{id}/supprimer", name="customer_detete_admin")
     * @param Customer $customerDelete
     * return RedirectResponse
     */
    public function deleteStatut(Customer $customerDelete): RedirectResponse {
        $em = $this->getDoctrine()->getManager();
        $em->remove($customerDelete);
        $em->flush();
        return $this->redirectToRoute("customer_list_admin");
    }

    /**
     * @Route("/admin/client/{id}/modifier", name="customer_modify_admin")
     */
    public function modifyTask(Request $request, Customer $customerModify): Response
    {
        $form = $this->createForm(AdminCustomerModifyType::class, $customerModify);
        $notification = null;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $customerModify = $form->getData();
            $this->entityManager->persist($customerModify);
            $this->entityManager->flush();
            $notification = 'Client mise à jour !';
            $form = $this->createForm(AdminCustomerModifyType::class, $customerModify);
        }
        return $this->render('back/customer/modify.html.twig',[
            'form_customer_modify_admin' => $form->createView(),
            'notification' =>$notification,
            'customer' => $customerModify
        ]);   
    }
    
}
