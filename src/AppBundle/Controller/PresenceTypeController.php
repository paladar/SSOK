<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PresenceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Presencetype controller.
 *
 * @Route("presencetype")
 */
class PresenceTypeController extends Controller {

    /**
     * Lists all presenceType entities.
     *
     * @Route("/", name="presencetype_index")
     * @Method("GET")
     */
    public function indexAction() {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $presenceTypes = $em->getRepository('AppBundle:PresenceType')->findAll();

        return $this->render('presencetype/index.html.twig', array(
                    'presenceTypes' => $presenceTypes,
        ));
    }

    /**
     * Creates a new presenceType entity.
     *
     * @Route("/new", name="presencetype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $presenceType = new Presencetype();
        $form = $this->createForm('AppBundle\Form\PresenceTypeType', $presenceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($presenceType);
            $em->flush();

            return $this->redirectToRoute('presencetype_show', array('id' => $presenceType->getId()));
        }

        return $this->render('presencetype/new.html.twig', array(
                    'presenceType' => $presenceType,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a presenceType entity.
     *
     * @Route("/{id}", name="presencetype_show")
     * @Method("GET")
     */
    public function showAction(PresenceType $presenceType) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($presenceType);

        return $this->render('presencetype/show.html.twig', array(
                    'presenceType' => $presenceType,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing presenceType entity.
     *
     * @Route("/{id}/edit", name="presencetype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PresenceType $presenceType) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($presenceType);
        $editForm = $this->createForm('AppBundle\Form\PresenceTypeType', $presenceType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('presencetype_edit', array('id' => $presenceType->getId()));
        }

        return $this->render('presencetype/edit.html.twig', array(
                    'presenceType' => $presenceType,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a presenceType entity.
     *
     * @Route("/{id}", name="presencetype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PresenceType $presenceType) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $form = $this->createDeleteForm($presenceType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($presenceType);
            $em->flush();
        }

        return $this->redirectToRoute('presencetype_index');
    }

    /**
     * Creates a form to delete a presenceType entity.
     *
     * @param PresenceType $presenceType The presenceType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PresenceType $presenceType) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('presencetype_delete', array('id' => $presenceType->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
