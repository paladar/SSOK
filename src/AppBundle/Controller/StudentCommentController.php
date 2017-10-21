<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StudentComment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Studentcomment controller.
 *
 * @Route("studentcomment")
 */
class StudentCommentController extends Controller
{
    /**
     * Lists all studentComment entities.
     *
     * @Route("/", name="studentcomment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $studentComments = $em->getRepository('AppBundle:StudentComment')->findAll();

        return $this->render('studentcomment/index.html.twig', array(
            'studentComments' => $studentComments,
        ));
    }

    /**
     * Creates a new studentComment entity.
     *
     * @Route("/new", name="studentcomment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $studentComment = new Studentcomment();
        $form = $this->createForm('AppBundle\Form\StudentCommentType', $studentComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($studentComment);
            $em->flush();

            return $this->redirectToRoute('studentcomment_show', array('id' => $studentComment->getId()));
        }

        return $this->render('studentcomment/new.html.twig', array(
            'studentComment' => $studentComment,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a studentComment entity.
     *
     * @Route("/{id}", name="studentcomment_show")
     * @Method("GET")
     */
    public function showAction(StudentComment $studentComment)
    {
        $deleteForm = $this->createDeleteForm($studentComment);

        return $this->render('studentcomment/show.html.twig', array(
            'studentComment' => $studentComment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing studentComment entity.
     *
     * @Route("/{id}/edit", name="studentcomment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, StudentComment $studentComment)
    {
        $deleteForm = $this->createDeleteForm($studentComment);
        $editForm = $this->createForm('AppBundle\Form\StudentCommentType', $studentComment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('studentcomment_edit', array('id' => $studentComment->getId()));
        }

        return $this->render('studentcomment/edit.html.twig', array(
            'studentComment' => $studentComment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a studentComment entity.
     *
     * @Route("/{id}", name="studentcomment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, StudentComment $studentComment)
    {
        $form = $this->createDeleteForm($studentComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($studentComment);
            $em->flush();
        }

        return $this->redirectToRoute('studentcomment_index');
    }

    /**
     * Creates a form to delete a studentComment entity.
     *
     * @param StudentComment $studentComment The studentComment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(StudentComment $studentComment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('studentcomment_delete', array('id' => $studentComment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
