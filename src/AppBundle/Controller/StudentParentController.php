<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StudentParent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Studentparent controller.
 *
 * @Route("studentparent")
 */
class StudentParentController extends Controller
{
    /**
     * Lists all studentParent entities.
     *
     * @Route("/", name="studentparent_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $studentParents = $em->getRepository('AppBundle:StudentParent')->findAll();

        return $this->render('studentparent/index.html.twig', array(
            'studentParents' => $studentParents,
        ));
    }

    /**
     * Creates a new studentParent entity.
     *
     * @Route("/new", name="studentparent_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $studentParent = new Studentparent();
        $form = $this->createForm('AppBundle\Form\StudentParentType', $studentParent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($studentParent);
            $em->flush();

            return $this->redirectToRoute('studentparent_show', array('id' => $studentParent->getId()));
        }

        return $this->render('studentparent/new.html.twig', array(
            'studentParent' => $studentParent,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a studentParent entity.
     *
     * @Route("/{id}", name="studentparent_show")
     * @Method("GET")
     */
    public function showAction(StudentParent $studentParent)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        return $this->render('studentparent/show.html.twig', array(
            'studentParent' => $studentParent,
        ));
    }

    /**
     * Displays a form to edit an existing studentParent entity.
     *
     * @Route("/{id}/edit", name="studentparent_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, StudentParent $studentParent)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($studentParent);
        $editForm = $this->createForm('AppBundle\Form\StudentParentType', $studentParent);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('studentparent_edit', array('id' => $studentParent->getId()));
        }

        return $this->render('studentparent/edit.html.twig', array(
            'studentParent' => $studentParent,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a studentParent entity.
     *
     * @Route("/{id}", name="studentparent_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, StudentParent $studentParent)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $form = $this->createDeleteForm($studentParent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($studentParent);
            $em->flush();
        }

        return $this->redirectToRoute('studentparent_index');
    }

    /**
     * Creates a form to delete a studentParent entity.
     *
     * @param StudentParent $studentParent The studentParent entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(StudentParent $studentParent)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('studentparent_delete', array('id' => $studentParent->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
