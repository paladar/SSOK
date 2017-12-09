<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GradeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Gradetype controller.
 *
 * @Route("gradetype")
 */
class GradeTypeController extends Controller
{
    /**
     * Lists all gradeType entities.
     *
     * @Route("/", name="gradetype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $gradeTypes = $em->getRepository('AppBundle:GradeType')->findAll();

        return $this->render('gradetype/index.html.twig', array(
            'gradeTypes' => $gradeTypes,
        ));
    }

    /**
     * Creates a new gradeType entity.
     *
     * @Route("/new", name="gradetype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $gradeType = new Gradetype();
        $form = $this->createForm('AppBundle\Form\GradeTypeType', $gradeType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gradeType);
            $em->flush();

            return $this->redirectToRoute('gradetype_show', array('id' => $gradeType->getId()));
        }

        return $this->render('gradetype/new.html.twig', array(
            'gradeType' => $gradeType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a gradeType entity.
     *
     * @Route("/{id}", name="gradetype_show")
     * @Method("GET")
     */
    public function showAction(GradeType $gradeType)
    {
        $deleteForm = $this->createDeleteForm($gradeType);

        return $this->render('gradetype/show.html.twig', array(
            'gradeType' => $gradeType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing gradeType entity.
     *
     * @Route("/{id}/edit", name="gradetype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, GradeType $gradeType)
    {
        $deleteForm = $this->createDeleteForm($gradeType);
        $editForm = $this->createForm('AppBundle\Form\GradeTypeType', $gradeType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gradetype_edit', array('id' => $gradeType->getId()));
        }

        return $this->render('gradetype/edit.html.twig', array(
            'gradeType' => $gradeType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a gradeType entity.
     *
     * @Route("/{id}", name="gradetype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, GradeType $gradeType)
    {
        $form = $this->createDeleteForm($gradeType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($gradeType);
            $em->flush();
        }

        return $this->redirectToRoute('gradetype_index');
    }

    /**
     * Creates a form to delete a gradeType entity.
     *
     * @param GradeType $gradeType The gradeType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(GradeType $gradeType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gradetype_delete', array('id' => $gradeType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
