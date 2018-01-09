<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grade;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Grade controller.
 *
 * @Route("grade")
 */
class GradeController extends Controller {

    /**
     * @Route("/subjects/{id}", name="gradeIndexSubjects")
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
        return $this->render('default/Teacher/subjects.html.twig', [
                    'subjects' => $subjects,
        ]);
    }

    /**
     * @Route("/class/{id}/{subjectId}", name="gradeIndexClass")
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
        return $this->render('default/Teacher/classes.html.twig', [
                    'schoolClasses' => $schoolClasses,
                    'subject' => $subject
        ]);
    }

    /**
     * @Route("/grades/{id}/{subjectId}", name="gradeIndex")
     */
    public function indexGradesAction(Request $request, $id, $subjectId) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($id);
        $subject = $em->getRepository('AppBundle:Subject')->find($subjectId);
        $students = $schoolClass->getStudents();
        return $this->render('default/Teacher/grades.html.twig', [
                    'students' => $students,
                    'subject' => $subject,
                    'schoolClass' => $schoolClass
        ]);
    }

    /**
     * Get grades types
     *
     * @Route("/gradeGetTypes", name="grade_get_types")
     * @Method("GET")
     */
    public function gradeGetTypesAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository('AppBundle:GradeType')->findAll();
        $arr = [];
        foreach ($types as $type) {
            $arr[] = array('id' => $type->getId(), 'label' => $type->getLabel());
        }
        $response = new JsonResponse($arr);

        return $response;
    }

    /**
     * Adds grade
     *
     * @Route("/gradeadd", name="grade_add")
     * @Method("POST")
     */
    public function gradeAddAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $studentId = $request->request->get('student');
        $subjectId = $request->request->get('subject');
        $schoolClassId = $request->request->get('schoolclass');
        $typeId = $request->request->get('type');
        $value = $request->request->get('value');

        $student = $em->getRepository('AppBundle:Student')->find($studentId);
        $subject = $em->getRepository('AppBundle:Subject')->find($subjectId);
        $type = $em->getRepository('AppBundle:GradeType')->find($typeId);

        $grade = new Grade();
        $grade->setStudent($student);
        $grade->setSubject($subject);
        $grade->setValue($value);
        $grade->setGradeType($type);

        $em->persist($grade);
        $em->flush();

        return $this->redirect($this->generateUrl('gradeIndex', array('id' => $schoolClassId, 'subjectId' => $subject->getId())), 301);
    }

    /**
     * Removes grade
     *
     * @Route("/gradedelete/{id}/{subject}/{schoolClass}", name="grade_delete")
     * @Method("GET")
     */
    public function lessonDeleteAction(Request $request, $id, $subject, $schoolClass) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $gradeToDelete = $em->getRepository('AppBundle:Grade')->find($id);

        $em->remove($gradeToDelete);
        $em->flush();

        return $this->redirect($this->generateUrl('gradeIndex', array('id' => $schoolClass, 'subjectId' => $subject)), 301);
    }
    
        /**
     * @Route("student/grades/{id}", name="gradeStudentIndex")
     */
    public function indexGradesStudentAction(Request $request, $id) {
        $this->denyAccessUnlessGranted('ROLE_STUDENT', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->find($id);
        $schoolClass = $student->getSchoolClass();
        $grades = $student->getGrades();
        $subjects = [];
        foreach ($schoolClass->getLessons() as $lesson) {
            $flag = 0;
            foreach ($subjects as $subject) {
                if ($lesson->getSubject()->getTitle() == $subject->getTitle()) {
                    $flag = 1;
                }
            }
            if ($flag == 0) {
                $subjects[] = $lesson->getSubject();
            }
        }
        return $this->render('default/Student/grades.html.twig', [
                    'student' => $student,
                    'subjects' => $subjects,
                    'grades' => $grades,
        ]);
    }

}
