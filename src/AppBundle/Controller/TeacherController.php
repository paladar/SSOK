<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Teacher;
use AppBundle\Entity\User;
use AppBundle\Entity\Password;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Teacher controller.
 *
 * @Route("teacher")
 */
class TeacherController extends Controller {

    /**
     * Lists all teacher entities.
     *
     * @Route("/", name="teacher_index")
     * @Method("GET")
     */
    public function indexAction() {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
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
    public function newAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $teacher = new Teacher();
        $user = new User();
        $password = new Password();
        $parentPassword = new Password();
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $username = 'nauczyciel.' . strtolower($request->get('appbundle_teacher')['firstName']) . strtolower($request->get('appbundle_teacher')['surname']);

        $i = 1;
        do {
            $check = $em->getRepository('AppBundle:User')->findOneByUsername($username);
            $username = $username . $i;
            $i++;
        } while ($check != '');


        $randomString = '';
        for ($i = 0; $i < 12; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $user->setUsername($username);
        $user->setPlainPassword($randomString);
        $password->setUsername($username);
        $password->setPassword($randomString);
        $user->setEnabled(true);
        if ($request->request->get('appbundle_teacher')['email'] != '') {
            $user->setEmail($request->request->get('appbundle_teacher')['email']);
        } else {
            $user->setEmail($username);
        }

        $teacher->setUser($user);
        $user->setTeacher($teacher);
        $user->setRoles(array('ROLE_TEACHER'));
        $form = $this->createForm('AppBundle\Form\TeacherType', $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subjects = $request->request->get('appbundle_teacher')['subjects'];
            foreach ($subjects as $id) {
                $subject = $em->getRepository('AppBundle:Subject')->find($id);
                $teacher->addSubject($subject);
                $subject->addTeacher($teacher);
            }
            $em->persist($password);
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
    public function showAction(Teacher $teacher) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        return $this->render('teacher/show.html.twig', array(
                    'teacher' => $teacher,
        ));
    }

    /**
     * Displays a form to edit an existing teacher entity.
     *
     * @Route("/{id}/edit", name="teacher_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Teacher $teacher) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($teacher);
        $editForm = $this->createForm('AppBundle\Form\TeacherType', $teacher);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $subjects = $request->request->get('appbundle_teacher')['subjects'];
            foreach ($subjects as $id) {
                $subject = $em->getRepository('AppBundle:Subject')->find($id);
                $teacher->addSubject($subject);
                $subject->addTeacher($teacher);
            }
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
    public function deleteAction(Request $request, Teacher $teacher) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
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
     * Finds and displays a teacher default password.
     *
     * @Route("/{id}/password", name="teacher_password")
     * @Method("GET")
     */
    public function passwordAction(Teacher $teacher) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $username = $teacher->getUser()->getUsername();
        $passwordObj = $em->getRepository('AppBundle:Password')->findOneByUsername($username);
        $password = $passwordObj->getPassword();

        return $this->render('teacher/password.html.twig', array(
                    'teacher' => $teacher,
                    'username' => $username,
                    'password' => $password
        ));
    }

    /**
     * Finds and displays a teacher default password.
     *
     * @Route("/{id}/resetpassword", name="teacher_reset_password")
     * @Method("GET")
     */
    public function resetPasswordAction(Teacher $teacher) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $user = $teacher->getUser();
        $username = $teacher->getUser()->getUsername();
        $passwordObj = $em->getRepository('AppBundle:Password')->findOneByUsername($username);
        $password = $passwordObj->getPassword();
        $user->setPlainPassword($password);
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updatePassword($user);
        $em->persist($user);
        $em->persist($teacher);
        $em->flush();

        return $this->render('teacher/password.html.twig', array(
                    'teacher' => $teacher,
                    'username' => $username,
                    'password' => $password
        ));
    }

    /**
     * Creates a form to delete a teacher entity.
     *
     * @param Teacher $teacher The teacher entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Teacher $teacher) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('teacher_delete', array('id' => $teacher->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
