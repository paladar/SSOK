<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use AppBundle\Entity\User;
use AppBundle\Entity\Password;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Student controller.
 *
 * @Route("student")
 */
class StudentController extends Controller {

    /**
     * Lists all student entities.
     *
     * @Route("/", name="student_index")
     * @Method("GET")
     */
    public function indexAction() {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $students = $em->getRepository('AppBundle:Student')->findAll();

        return $this->render('student/index.html.twig', array(
                    'students' => $students,
        ));
    }

    /**
     * Creates a new student entity.
     *
     * @Route("/new", name="student_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $student = new Student();
        $user = new User();
        $password = new Password();
        $parentPassword = new Password();
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $username = strtolower($request->get('appbundle_student')['firstName']) . strtolower($request->get('appbundle_student')['surname']);

        $i = 1;
        do {
            $check = $em->getRepository('AppBundle:User')->findOneByUsername($username);
            if ($check != null) {
                $username = $username . $i;
            }
            $i++;
        } while ($check != null);


        $randomString = '';
        for ($i = 0; $i < 12; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $user->setUsername($username);
        $user->setPlainPassword($randomString);
        $password->setUsername($username);
        $password->setPassword($randomString);
        if ($request->request->get('appbundle_student')['email'] != '') {
            $user->setEmail($request->request->get('appbundle_student')['email']);
        } else {
            $user->setEmail($username);
        }
        $student->setUser($user);
        $user->setStudent($student);
        $user->setRoles(array('ROLE_STUDENT'));
        $user->setEnabled(true);
        $form = $this->createForm('AppBundle\Form\StudentType', $student);
        $form->handleRequest($request);

        $parent = $student->getStudentParent();
        $parentUser = new User();
        $parentUser->setUsername('rodzic.' . $username);
        $randomString = '';
        for ($i = 0; $i < 12; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $parentUser->setPlainPassword($randomString);
        $parentPassword->setUsername('rodzic.' . $username);
        $parentPassword->setPassword($randomString);
        if ($request->request->get('appbundle_student')['studentParent']['Email'] != '') {
            $parentUser->setEmail($request->request->get('appbundle_student')['studentParent']['Email']);
        } else {
            $parentUser->setEmail('rodzic.' . $username);
        }
        $parent->setUser($parentUser);
        $parentUser->setStudentParent($parent);
        $parentUser->setRoles(array('ROLE_PARENT'));
        $parentUser->setEnabled(true);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($password);
            $em->persist($parentPassword);
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute('student_show', array('id' => $student->getId()));
        }

        return $this->render('student/new.html.twig', array(
                    'student' => $student,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a student entity.
     *
     * @Route("/{id}", name="student_show")
     * @Method("GET")
     */
    public function showAction(Student $student) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        return $this->render('student/show.html.twig', array(
                    'student' => $student,
        ));
    }

    /**
     * Displays a form to edit an existing student entity.
     *
     * @Route("/{id}/edit", name="student_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Student $student) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($student);
        $editForm = $this->createForm('AppBundle\Form\StudentType', $student);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_edit', array('id' => $student->getId()));
        }

        return $this->render('student/edit.html.twig', array(
                    'student' => $student,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a student entity.
     *
     * @Route("/{id}", name="student_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Student $student) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $form = $this->createDeleteForm($student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($student);
            $em->flush();
        }

        return $this->redirectToRoute('student_index');
    }

    /**
     * Creates a form to delete a student entity.
     *
     * @param Student $student The student entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Student $student) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('student_delete', array('id' => $student->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
