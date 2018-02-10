<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StudentComment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Studentcomment controller.
 *
 * @Route("studentcomment")
 */
class StudentCommentController extends Controller {

    /**
     * @Route("/subjects/{id}", name="commentIndexSubjects")
     */
    public function indexSubjectsAction(Request $request, $id) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $teacher = $em->getRepository('AppBundle:Teacher')->find($id);
        $subjects = $teacher->getSubjects();
        $count = count($subjects);
        if ($count == 1) {
            return $this->redirectToRoute('indexClass', array('id' => $teacher->getId(), 'subjectId' => $subjects[0]->getId()));
        }
        return $this->render('default/Teacher/subjectsComments.html.twig', [
                    'subjects' => $subjects,
        ]);
    }

    /**
     * @Route("/class/{id}/{subjectId}", name="commentIndexClass")
     */
    public function indexClassAction(Request $request, $id, $subjectId) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $teacher = $em->getRepository('AppBundle:Teacher')->find($id);
        $subject = $em->getRepository('AppBundle:Subject')->find($subjectId);
        $lessons = $em->getRepository('AppBundle:Lesson')->findBy(
                ['subject' => $subject, 'teacher' => $teacher]
        );
        $schoolClasses = [];
        foreach ($lessons as $lesson) {
            $flag = 0;
            foreach ($schoolClasses as $sc) {
                if ($lesson->getSchoolClass()->getNumber() == $sc->getNumber() && $lesson->getSchoolClass()->getLetter() == $sc->getLetter()) {
                    $flag = 1;
                }
            }
            if ($flag == 0) {
                $schoolClasses[] = $lesson->getSchoolClass();
            }
        }
        return $this->render('default/Teacher/classesComments.html.twig', [
                    'schoolClasses' => $schoolClasses,
                    'subject' => $subject
        ]);
    }

    /**
     * @Route("/comment/{id}/{subjectId}", name="commentIndex")
     */
    public function indexGradesAction(Request $request, $id, $subjectId) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $teacher = $user->getTeacher();
        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($id);
        $subject = $em->getRepository('AppBundle:Subject')->find($subjectId);

        $students = $schoolClass->getStudents();
        return $this->render('default/Teacher/comments.html.twig', [
                    'students' => $students,
                    'teacher' => $teacher,
                    'schoolClass' => $schoolClass,
                    'subject' => $subject
        ]);
    }

    /**
     * Adds comment
     *
     * @Route("/commentadd", name="comment_add")
     * @Method("POST")
     */
    public function commentAddAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $studentId = $request->request->get('student');
        $subjectId = $request->request->get('subject');
        $schoolClassId = $request->request->get('schoolclass');
        $teacherId = $request->request->get('teacher');
        $value = $request->request->get('value');

        $teacher = $em->getRepository('AppBundle:Teacher')->find($teacherId);
        $student = $em->getRepository('AppBundle:Student')->find($studentId);
        $subject = $em->getRepository('AppBundle:Subject')->find($subjectId);

        $comment = new StudentComment();
        $comment->setStudent($student);
        $comment->setTeacher($teacher);
        $comment->setValue($value);

        $em->persist($comment);
        $em->flush();

        return $this->redirect($this->generateUrl('commentIndex', array('id' => $schoolClassId, 'subjectId' => $subject->getId())), 301);
    }

    /**
     * Removes comment
     *
     * @Route("/comment/{id}/{subject}/{schoolClass}", name="comment_delete")
     * @Method("GET")
     */
    public function commentDeleteAction(Request $request, $id, $subject, $schoolClass) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $commentToDelete = $em->getRepository('AppBundle:StudentComment')->find($id);

        $em->remove($commentToDelete);
        $em->flush();

        return $this->redirect($this->generateUrl('commentIndex', array('id' => $schoolClass, 'subjectId' => $subject)), 301);
    }

    /**
     * @Route("student/comments/{id}", name="commentStudentIndex")
     */
    public function indexCommentsStudentAction(Request $request, $id) {
        $this->denyAccessUnlessGranted('ROLE_STUDENT', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->find($id);
        
        return $this->render('default/Student/comments.html.twig', [
                    'student' => $student,
        ]);
    }

}
