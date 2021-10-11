<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteAccountController extends AbstractController
{
    /**
     * @Route("/delete/account/{userId}", name="delete_account")
     */
public function deleteUserAction(Request $request,$userId,
                                  EntityManagerInterface $em,
                                 TranslatorInterface $translator,
                                 FormErrorCollector $errorCollector,
                                 SessionInterface $session,
                                 TokenStorageInterface $tokenStorage)
{
    $user = $this->getUser();
    $deleteUserForm = $this->createForm(ConfirmPasswordType::class);

    $deleteUserForm->handleRequest($request);
    if($request->isXmlHttpRequest() && $user->getId() == $userId){

        if($deleteUserForm->isValid()){

            // force manual logout of logged in user    
            $this->get('security.token_storage')->setToken(null);

            $em->remove($user);
            $em->flush();

            $session->invalidate(0);

            return new JsonResponse(array(
                'status' => 'success',
                'message' => $translator->trans('USER_DELETED_SUCCESS')
            ));
        }else{

            $errors = $errorCollector->getErrors($deleteUserForm);

            return new JsonResponse(array(
                'status' => 'failure',
                'errors' => $errors
            ));
        }

    }else{

        return new JsonResponse('FORBIDEN');

    }
}
}
