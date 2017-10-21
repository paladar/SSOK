<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Presence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Presence controller.
 *
 * @Route("presence")
 */
class PresenceController extends Controller
{
    /**
     * Lists all presence entities.
     *
     * @Route("/", name="presence_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $presences = $em->getRepository('AppBundle:Presence')->findAll();

        return $this->render('presence/index.html.twig', array(
            'presences' => $presences,
        ));
    }

    /**
     * Creates a new presence entity.
     *
     * @Route("/new", name="presence_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $presence = new Presence();
        $form = $this->createForm('AppBundle\Form\PresenceType', $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($presence);
            $em->flush();

            return $this->redirectToRoute('presence_show', array('id' => $presence->getId()));
        }

        return $this->render('presence/new.html.twig', array(
            'presence' => $presence,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a presence entity.
     *
     * @Route("/{id}", name="presence_show")
     * @Method("GET")
     */
    public function showAction(Presence $presence)
    {
        $deleteForm = $this->createDeleteForm($presence);

        return $this->render('presence/show.html.twig', array(
            'presence' => $presence,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing presence entity.
     *
     * @Route("/{id}/edit", name="presence_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Presence $presence)
    {
        $deleteForm = $this->createDeleteForm($presence);
        $editForm = $this->createForm('AppBundle\Form\PresenceType', $presence);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('presence_edit', array('id' => $presence->getId()));
        }

        return $this->render('presence/edit.html.twig', array(
            'presence' => $presence,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a presence entity.
     *
     * @Route("/{id}", name="presence_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Presence $presence)
    {
        $form = $this->createDeleteForm($presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($presence);
            $em->flush();
        }

        return $this->redirectToRoute('presence_index');
    }

    /**
     * Creates a form to delete a presence entity.
     *
     * @param Presence $presence The presence entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Presence $presence)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('presence_delete', array('id' => $presence->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
