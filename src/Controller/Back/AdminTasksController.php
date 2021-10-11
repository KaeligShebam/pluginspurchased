<?php

namespace App\Controller\Back;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\File;
use App\Entity\Task;
use App\Entity\Quote;
use App\Entity\Appointment;
use Psr\Log\LoggerInterface;
use App\Repository\TaskRepository;
use App\Repository\QuoteRepository;
use App\Form\Back\Task\AdminTaskAddType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentRepository;
use App\Form\Back\Task\AdminTaskModifyType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Back\Quote\AdminTaskQuoteAddType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Back\Quote\AdminTaskQuoteModifyType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\Back\Appointment\AdminTaskAppointmentAddType;
use App\Form\Back\Appointment\AdminTaskAppointmentModifyType;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTasksController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
    $this->entityManager = $entityManager;
    }
    /**
     * @Route("/admin/liste-des-taches/", name="task_list_admin")
     */
    public function listTask(TaskRepository $taskAdmin, AppointmentRepository $appointmentAdmin, QuoteRepository $quoteAdmin): Response
    {

        return $this->render('back/task/list.html.twig', [
            'task' => $taskAdmin->findBy([], ['position' => 'ASC']),
            'appointment' => $appointmentAdmin->findBy([], ['hoursappointment' => 'ASC']),
            'quote' => $quoteAdmin->findBy([], ['position' => 'ASC']),
        ]);
    }

    // ------------------------------
    // --------- Priority 1 ---------
    // ------------------------------

    /**
     * @Route("/admin/liste-des-taches/ajouter", name="task_list_add_admin")
     */
    public function taskP1(Request $request): Response {
        $taskAdd = new Task();
        $form = $this->createForm(AdminTaskAddType::class, $taskAdd);
        $notification = null;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($taskAdd);
            $this->entityManager->flush();
            $notification = 'La tâche a bien été ajoutée';
            $taskAdd = new Task();
            $form = $this->createForm(AdminTaskAddType::class, $taskAdd);
        }
            return $this->render('back/task/add.html.twig', [
                'form_task_add_admin' => $form->createView(),
                'notification' => $notification
            ]);
    }

    /**
     * @Route("/admin/liste-des-taches/{id}/modifier", name="task_list_modify_admin")
     */
    public function modifyTask(Request $request, Task $taskModify): Response
    {
        $form = $this->createForm(AdminTaskModifyType::class, $taskModify);
        $notification = null;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $taskModify = $form->getData();
            $this->entityManager->persist($taskModify);
            $this->entityManager->flush();
            $notification = 'Tâche P1 mise à jour !';
            $form = $this->createForm(AdminTaskModifyType::class, $taskModify);
        }
        return $this->render('back/task/modify.html.twig',[
            'form_task_modify_admin' => $form->createView(),
            'notification' => $notification,
            'task' => $taskModify
        ]);   
    }
    
    /**
     * @Route("/admin/liste-des-taches/{id}/supprimer", name="task_list_detete_admin")
     * @param Task $task
     * return RedirectResponse
     */
    public function deleteTask(Task $task): RedirectResponse {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute("task_list_admin");
    }

    /**
     * @Route("/admin/liste-des-taches/basculement-vers-p1/{id}", name="task_change_to_p1_admin")
     * return RedirectResponse
     */
    public function changeTaskToP1Admin(Task $task): Response
    {

        $rep = $this->getDoctrine()
            ->getRepository(Task::class)
            ->setChangeTaskForP1($task->getId());

        return $this->redirectToRoute("task_list_admin");
    }

    /**
     * @Route("/admin/liste-des-taches/basculement-vers-p2/{id}", name="task_change_to_p2_admin")
     * return RedirectResponse
     */
    public function changeTaskToP2(Task $task): Response
    {

        $rep = $this->getDoctrine()
            ->getRepository(Task::class)
            ->setChangeTaskForP2($task->getId());

        return $this->redirectToRoute("task_list_admin");
    }

    /**
     * @Route("/basculement-vers-p1/{id}", name="task_change_to_p1_front")
     * return RedirectResponse
     */
    public function changeTaskToP1Front(Task $task): Response
    {

        $rep = $this->getDoctrine()
            ->getRepository(Task::class)
            ->setChangeTaskForP1($task->getId());

        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/basculement-vers-p2/{id}", name="task_change_to_p2_front")
     * return RedirectResponse
     */
    public function changeTaskToP2Front(Task $task): Response
    {

        $rep = $this->getDoctrine()
            ->getRepository(Task::class)
            ->setChangeTaskForP2($task->getId());

        return $this->redirectToRoute("home");
    }


    // ------------------------------
    // -------- Appointment ---------
    // ------------------------------

    /**
    * @Route("/admin/liste-des-taches/rendez-vous/ajouter", name="task_appointment_add_admin")
    */
    public function addTaskAppointment(Request $request): Response {
        $appointmentAdd = new Appointment();
        $form = $this->createForm(AdminTaskAppointmentAddType::class, $appointmentAdd);
        $notification = null;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($appointmentAdd);
            $this->entityManager->flush();
            $notification = 'Le rendez-vous a bien été ajoutée';
            $appointmentAdd = new Appointment();
            $form = $this->createForm(AdminTaskAppointmentAddType::class, $appointmentAdd);
        }
            return $this->render('back/appointment/add.html.twig', [
                'form_appointment_add_admin' => $form->createView(),
                'notification' => $notification
            ]);
        }

    /**
     * @Route("/admin/liste-des-taches/rendez-vous/{id}/modifier", name="task_appointment_modify_admin")
     */
    public function modifyAppointment(Request $request, Appointment $appointmentModify): Response
    {
        $form = $this->createForm(AdminTaskAppointmentModifyType::class, $appointmentModify);
        $notification = null;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $appointmentModify = $form->getData();
            $this->entityManager->persist($appointmentModify);
            $this->entityManager->flush();
            $notification = 'Le rendez-vous a bien été mise à jour !';
            $form = $this->createForm(AdminTaskAppointmentModifyType::class, $appointmentModify);
        }
        return $this->render('back/appointment/modify.html.twig',[
            'form_appointment_modify_admin' => $form->createView(),
            'notification' => $notification,
            'appointment' => $appointmentModify
        ]);   
    }

    /**
     * @Route("/admin/liste-des-taches/rendez-vous/{id}/supprimer", name="task_appointment_detete_admin")
     * @param Appointment $rendezvousDelete
     * return RedirectResponse
     */
    public function deleteQuote(Appointment $appointmentDelete): RedirectResponse {
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($appointmentDelete);
        $em->flush();

        return $this->redirectToRoute("task_list_admin");
        ;   
    }

    // ------------------------------
    // ----------- Quote ------------
    // ------------------------------

   /**
    * @Route("/admin/liste-des-taches/devis/ajouter", name="task_quote_add_admin")
    */
    public function addTaskQuote(Request $request): Response {
        $quoteAdd = new Quote();
        $form = $this->createForm(AdminTaskQuoteAddType::class, $quoteAdd);
        $notification = null;
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($quoteAdd);
            $this->entityManager->flush();
            $notification = 'Le devis a bien été ajouté';
            $quoteAdd = new Quote();
            $form = $this->createForm(AdminTaskQuoteAddType::class, $quoteAdd);
        }
            return $this->render('back/quote/add.html.twig', [
                'form_quote_add_admin' => $form->createView(),
                'notification' => $notification
            ]);
        }

    /**
    * @Route("/admin/liste-des-taches/devis/{id}/modifier", name="task_quote_modify_admin")
    */
    public function modifyQuote(Request $request, Quote $quoteModify): Response
    {
        $form = $this->createForm(AdminTaskQuoteModifyType::class, $quoteModify);
        $notification = null;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $quoteModify = $form->getData();
            $this->entityManager->persist($quoteModify);
            $this->entityManager->flush();
            $notification = 'Le devis a bien été mis à jour !';
            $form = $this->createForm(AdminTaskQuoteModifyType::class, $quoteModify);
        }
        return $this->render('back/quote/modify.html.twig',[
            'form_quote_modify_admin' => $form->createView(),
            'notification' => $notification,
            'quote' => $quoteModify
        ]);   
    }

    /**
    * @Route("/admin/liste-des-taches/devis/{id}/supprimer", name="task_quote_detete_admin")
    * @param Quote $quoteDelete
    * return RedirectResponse
    */
    public function deleteAppointment(Quote $quoteDelete): RedirectResponse {
        $em = $this->getDoctrine()->getManager();
        $em->remove($quoteDelete);
        $em->flush();

        return $this->redirectToRoute("task_list_admin");
        ;   
    }

    // -------------------------------------------
    // ----------- Dowlonad All Tasks ------------
    // -------------------------------------------

    /**
     *@Route("/admin/liste-des-taches/bouton-archiver/", name="task_btn_archived_admin")
     */
    public function archivedBtn(TaskRepository $taskAdmin, AppointmentRepository $appointmentAdmin, QuoteRepository $quoteAdmin, LoggerInterface $logger, $length = 2, $characters = 'abcdefghijklmnopqrstuvwxyz0123456789'): RedirectResponse
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Gotham');
        $pdfOptions->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->setPaper('A3', 'landscape');
        $html = $this->renderView('back/task/download.html.twig', [
            'task' => $taskAdmin->findAll(),
            'appointment' => $appointmentAdmin->findBy([], ['hoursappointment' => 'DESC']),
            'quote' => $quoteAdmin->findAll(),
        ]);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $output = $dompdf->output();

        $image = new File;
        $charactersLength = strlen($characters);
        $randomString = '';
        for (
            $i = 0;
            $i < $length;
            $i++
        ) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $path = $this->getParameter('download_task_directory');

        $dateFile = date("d-m-y");
        $fileName = 'liste-des-taches-du-' . $dateFile . '-' . $randomString . '.pdf';

        $fsObject = new Filesystem();

        try {
            if (!$fsObject->exists($path)) {
                $fsObject->mkdir($path);
            }
            $file = $path . $fileName;
            if (!$fsObject->exists($file)) {
                $fsObject->touch($file);
                $fsObject->chmod($file, 0777);
                $fsObject->dumpFile($file, $output);
            }
        } catch (IOExceptionInterface $exception) {
            $logger->error("Impossible de créer le fichier");
        }

        $image->setName($fileName);
        $this->entityManager->persist($image);
        $this->entityManager->flush();


        $remove = $this->getDoctrine()
            ->getRepository(Appointment::class)
            ->setRemoveAppointment();

        $remove2 = $this->getDoctrine()
            ->getRepository(Task::class)
            ->setRemoveTask();

        $remove3 = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->setRemoveQuote();   

        return $this->redirectToRoute("task_list_admin");
    }


    /**
     * @Route("/admin/liste-des-taches/reorder", name="admin_reorder_row")
     */
    public function reorderTaskP1Row(Request $request, TaskRepository $taskRow, AppointmentRepository $appointmentRow, QuoteRepository $quoteRow)
    {
        $cpt = 0;
        switch ($request->request->get("context")) {
            case '1':
                foreach (json_decode($request->request->get("table"), true /* est-ce que je veux un tableau assoc oui (par défaut false) */) as $row) {
                    $task = $taskRow->find($row['id']); //on récupère la task
                    $task->setPosition($cpt); //on definit la position
                    $cpt++; //on ajoute une rangée
                }
                break;

            case '2':
                foreach (json_decode($request->request->get("table"), true) as $row) {
                    $appt = $appointmentRow->find($row['id']);
                    $appt->setPosition($cpt);
                    $cpt++;
                }
                break;

            case '3':
                foreach (json_decode($request->request->get("table"), true) as $row) {
                    $quote = $quoteRow->find($row['id']);
                    $quote->setPosition($cpt);
                    $cpt++;
                }
                break;
        }

        $this->entityManager->flush();
        return new JsonResponse([
            'data' => gettype($request->request->get("context"))
        ]);
    }

}