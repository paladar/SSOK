<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SchoolClass;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Schoolclass controller.
 *
 * @Route("schoolclass")
 */
class SchoolClassController extends Controller {

    /**
     *
     * @Route("/teachers", name="schoolclass_teachers")
     * @Method("GET")
     */
    public function teachersAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $id = $request->query->get('id');

        $teachers = $em->getRepository('AppBundle:Teacher')->findAll();

        $arr = [];
        foreach ($teachers as $teacher) {
            foreach ($teacher->getSubjects() as $subject) {
                if ($subject->getId() == $id) {
                    $arr[] = array('id' => $teacher->getId(), 'name' => $teacher->getFirstname() . ' ' . $teacher->getSurname());
                    break;
                }
            }
        }
        $response = new JsonResponse($arr);

        return $response;
    }

    /**
     * Lists all schoolClass entities.
     *
     * @Route("/list", name="schoolclass_index")
     * @Method("GET")
     */
    public function indexAction() {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $schoolClasses = $em->getRepository('AppBundle:SchoolClass')->findAll();

        return $this->render('schoolclass/index.html.twig', array(
                    'schoolClasses' => $schoolClasses,
        ));
    }

    /**
     * Creates a new schoolClass entity.
     *
     * @Route("/new", name="schoolclass_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $schoolClass = new Schoolclass();
        $form = $this->createForm('AppBundle\Form\SchoolClassType', $schoolClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($schoolClass);
            $em->flush();

            return $this->redirectToRoute('schoolclass_show', array('id' => $schoolClass->getId()));
        }

        return $this->render('schoolclass/new.html.twig', array(
                    'schoolClass' => $schoolClass,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a schoolClass entity.
     *
     * @Route("/{id}/show", name="schoolclass_show")
     * @Method("GET")
     */
    public function showAction(SchoolClass $schoolClass) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        return $this->render('schoolclass/show.html.twig', array(
                    'schoolClass' => $schoolClass,
        ));
    }

    /**
     * Displays a form to edit an existing schoolClass entity.
     *
     * @Route("/{id}/edit", name="schoolclass_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SchoolClass $schoolClass) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($schoolClass);
        $editForm = $this->createForm('AppBundle\Form\SchoolClassType', $schoolClass);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('schoolclass_edit', array('id' => $schoolClass->getId()));
        }

        return $this->render('schoolclass/edit.html.twig', array(
                    'schoolClass' => $schoolClass,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a schoolClass entity.
     *
     * @Route("/{id}", name="schoolclass_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SchoolClass $schoolClass) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $form = $this->createDeleteForm($schoolClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($schoolClass);
            $em->flush();
        }

        return $this->redirectToRoute('schoolclass_index');
    }

    /**
     * Creates a form to delete a schoolClass entity.
     *
     * @param SchoolClass $schoolClass The schoolClass entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SchoolClass $schoolClass) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('schoolclass_delete', array('id' => $schoolClass->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    /**
     * Finds and displays a schoolClass entity.
     *
     * @Route("/{id}/plan", name="schoolclass_plan")
     * @Method("GET")
     */
    public function planAction(SchoolClass $schoolClass) {
        $em = $this->getDoctrine()->getManager();
        $subjects = $em->getRepository('AppBundle:Subject')->findAll();

        return $this->render('schoolclass/plan.html.twig', array(
                    'schoolClass' => $schoolClass,
                    'subjects' => $subjects
        ));
    }

}
