<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Teacher controller.
 *
 * @Route("teacher")
 */
class TeacherController extends Controller
{
    /**
     * Lists all teacher entities.
     *
     * @Route("/", name="teacher_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $teachers = $em->getRepository('AppBundle:Teacher')->findAll();

        return $this->render('teacher/index.html.twig', array(
            'teachers' => $teachers,
        ));
    }

    /**
     * Creates a new teacher entity.
     *
     * @Route("/new", name="teacher_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $teacher = new Teacher();
        $form = $this->createForm('AppBundle\Form\TeacherType', $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($teacher);
            $em->flush();

            return $this->redirectToRoute('teacher_show', array('id' => $teacher->getId()));
        }

        return $this->render('teacher/new.html.twig', array(
            'teacher' => $teacher,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a teacher entity.
     *
     * @Route("/{id}", name="teacher_show")
     * @Method("GET")
     */
    public function showAction(Teacher $teacher)
    {
        $deleteForm = $this->createDeleteForm($teacher);

        return $this->render('teacher/show.html.twig', array(
            'teacher' => $teacher,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing teacher entity.
     *
     * @Route("/{id}/edit", name="teacher_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Teacher $teacher)
    {
        $deleteForm = $this->createDeleteForm($teacher);
        $editForm = $this->createForm('AppBundle\Form\TeacherType', $teacher);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('teacher_edit', array('id' => $teacher->getId()));
        }

        return $this->render('teacher/edit.html.twig', array(
            'teacher' => $teacher,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a teacher entity.
     *
     * @Route("/{id}", name="teacher_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Teacher $teacher)
    {
        $form = $this->createDeleteForm($teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($teacher);
            $em->flush();
        }

        return $this->redirectToRoute('teacher_index');
    }

    /**
     * Creates a form to delete a teacher entity.
     *
     * @param Teacher $teacher The teacher entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Teacher $teacher)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('teacher_delete', array('id' => $teacher->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
