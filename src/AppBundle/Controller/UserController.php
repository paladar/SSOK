<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

/**
 * Lesson controller.
 *
 * @Route("user")
 */
class UserController extends Controller {

    /**
     * Displays a form to edit an existing lesson entity.
     *
     * @Route("/panel/{id}", name="user_panel")
     * @Method({"GET", "POST"})
     */
    public function panelAction(Request $request, $id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getId() != $id) {
            throw new AccessDeniedException('Access Denied!');
        }

        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');
            $userManager->updatePassword($user);
            
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_panel', array('id' => $user->getId()));
        }

        return $this->render('user/plan.html.twig', array(
                    'user' => $user,
                    'edit_form' => $editForm->createView(),
        ));
    }

}
