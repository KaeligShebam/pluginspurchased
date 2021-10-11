<?php

namespace App\Controller\Back;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\File;
use Psr\Log\LoggerInterface;
use App\Repository\FileRepository;
use App\Repository\TaskRepository;
use App\Repository\QuoteRepository;
use App\Repository\Task2Repository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDownloadController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("admin/telechargement", name="download_list_admin")
     */
    public function listDownload(FileRepository $downloadAdmin): Response
    {
        return $this->render('back/file/index.html.twig', [
            'file' =>$downloadAdmin->findBy(array(), array('created_at' => 'desc')),
        ]);
    }

    /**
     * @Route("/admin/telechargement/{id}/supprimer", name="download_detete_admin")
     * @param File $downloadDelete
     * return RedirectResponse
     */

    public function deleteStatus(File $downloadDelete): RedirectResponse
    {
        $fileName = $this->getParameter('download_task_directory') . '/' . $downloadDelete->getName();
        if(file_exists($fileName)){
            unlink($fileName);
            }
        $em = $this->getDoctrine()->getManager();
        $em->remove($downloadDelete);
        $em->flush();
        return $this->redirectToRoute("download_list_admin");
    }
}
