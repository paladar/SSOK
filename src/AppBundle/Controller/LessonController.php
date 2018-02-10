<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Lesson controller.
 *
 * @Route("lesson")
 */
class LessonController extends Controller {

    /**
     * Adds lesson
     *
     * @Route("/lessonadd", name="schoolclass_lesson_add")
     * @Method("POST")
     */
    public function lessonAddAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $teacherId = $request->request->get('teacher');
        $subjectId = $request->request->get('subject');
        $start = $request->request->get('start');
        $day = $request->request->get('day');
        $classId = $request->request->get('sclass');
        $formattedStart = \DateTime::createFromFormat('H:i', $start);

        $teacher = $em->getRepository('AppBundle:Teacher')->find($teacherId);
        $subject = $em->getRepository('AppBundle:Subject')->find($subjectId);
        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($classId);

        $lesson = new Lesson();
        $lesson->setTeacher($teacher);
        $teacher->addLesson($lesson);
        $lesson->setSubject($subject);
        $subject->addLesson($lesson);
        $lesson->setStart($formattedStart);
        $lesson->setDay($day);
        $lesson->setSchoolClass($schoolClass);
        $schoolClass->addLesson($lesson);

        $em->persist($lesson);
        $em->flush();

        return $this->redirect($this->generateUrl('schoolclass_plan', array('id' => $classId)), 301);
    }

    /**
     * Removes lesson
     *
     * @Route("/lessondelete/{lesson}/{class}", name="schoolclass_lesson_delete")
     * @Method("GET")
     */
    public function lessonDeleteAction(Request $request, $lesson, $class) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $lessonToDelete = $em->getRepository('AppBundle:Lesson')->find($lesson);

        $em->remove($lessonToDelete);
        $em->flush();

        return $this->redirect($this->generateUrl('schoolclass_plan', array('id' => $class)), 301);
    }

    /**
     * Finds and displays a schoolClass entity.
     *
     * @Route("/{id}/lessonedit", name="schoolclass_lesson_edit")
     * @Method("POST")
     */
    public function lessonEditAction(Request $request, $id) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $teacherId = $request->request->get('teacher');
        $subjectId = $request->request->get('subject');
        $start = $request->request->get('start');
        $day = $request->request->get('day');
        $classId = $request->request->get('sclass');
        $formattedStart = \DateTime::createFromFormat('H:i', $start);

        $teacher = $em->getRepository('AppBundle:Teacher')->find($teacherId);
        $subject = $em->getRepository('AppBundle:Subject')->find($subjectId);
        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($classId);

        $lesson = $em->getRepository('AppBundle:Lesson')->find($id);
        $lesson->setTeacher($teacher);
        $lesson->setSubject($subject);
        $lesson->setStart($formattedStart);
        $lesson->setDay($day);
        $lesson->setSchoolClass($schoolClass);

        $em->persist($lesson);
        $em->flush();

        return $this->redirect($this->generateUrl('schoolclass_plan', array('id' => $classId)), 301);
    }

}
