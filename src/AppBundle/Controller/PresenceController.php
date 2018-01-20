<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Presence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Presence controller.
 *
 * @Route("presence")
 */
class PresenceController extends Controller {

    /**
     * @Route("/teacher/{id}/{date}", name="presencesIndex")
     */
    public function indexAction(Request $request, $id, $date = 0) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $teacher = $em->getRepository('AppBundle:Teacher')->find($id);
        $minDate = $em->getRepository('AppBundle:SystemParameter')->findOneByName('min_date');
        $maxDate = $em->getRepository('AppBundle:SystemParameter')->findOneByName('max_date');
        $today = strtotime(date('Y-m-d'));
        $timestamp = strtotime(date('Y-m-d'));
        if (date('j', $timestamp) === '1') {
            $timestampMonday = strtotime(date('Y-m-d'));
            $monday = date('Y-m-d');
        } else {
            $timestampMonday = strtotime("last Monday");
            $monday = date('Y-m-d', $timestampMonday);
        }
        $timestamp = strtotime("-7 day", $timestampMonday);
        $mondayBefore = date('Y-m-d', $timestamp);
        if ($date != 0) {
            $days1 = 7 * ($date * 2);
            $days2 = 7 * (($date * 2) + 1);
            $timestamp = strtotime("-" . $days1 . "day", $timestampMonday);
            $monday = date('Y-m-d', $timestamp);
            $timestamp = strtotime("-" . $days2 . "day", $timestampMonday);
            $mondayBefore = date('Y-m-d', $timestamp);
        }
        return $this->render('default/Teacher/presences.html.twig', [
                    'teacher' => $teacher,
                    'monday' => $monday,
                    'mondayBefore' => $mondayBefore,
                    'date' => $date,
                    'minDate' => $minDate->getValue(),
                    'maxDate' => $maxDate->getValue()
        ]);
    }

    /**
     * @Route("/student/{id}/{date}", name="presencesStudentIndex")
     */
    public function indexStudentAction(Request $request, $id, $date = 0) {
        $this->denyAccessUnlessGranted('ROLE_STUDENT', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->find($id);
        $minDate = $em->getRepository('AppBundle:SystemParameter')->findOneByName('min_date');
        $maxDate = $em->getRepository('AppBundle:SystemParameter')->findOneByName('max_date');
        $today = strtotime(date('Y-m-d'));
        $timestamp = strtotime(date('Y-m-d'));
        if (date('j', $timestamp) === '1') {
            $timestampMonday = strtotime(date('Y-m-d'));
            $monday = date('Y-m-d');
        } else {
            $timestampMonday = strtotime("last Monday");
            $monday = date('Y-m-d', $timestampMonday);
        }
        $timestamp = strtotime("-7 day", $timestampMonday);
        $mondayBefore = date('Y-m-d', $timestamp);
        if ($date != 0) {
            $days1 = 7 * ($date * 2);
            $days2 = 7 * (($date * 2) + 1);
            $timestamp = strtotime("-" . $days1 . "day", $timestampMonday);
            $monday = date('Y-m-d', $timestamp);
            $timestamp = strtotime("-" . $days2 . "day", $timestampMonday);
            $mondayBefore = date('Y-m-d', $timestamp);
        }
        return $this->render('default/Student/presences.html.twig', [
                    'student' => $student,
                    'monday' => $monday,
                    'mondayBefore' => $mondayBefore,
                    'date' => $date,
                    'minDate' => $minDate->getValue(),
                    'maxDate' => $maxDate->getValue()
        ]);
    }

    /**
     * @Route("/presence/{date}/{schoolClassId}/{lessonId}", name="presence")
     */
    public function presenceAction(Request $request, $date, $schoolClassId, $lessonId) {
        $this->denyAccessUnlessGranted('ROLE_EDIT_PRESENCE', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $teacher = $user->getTeacher();

        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($schoolClassId);
        $lesson = $em->getRepository('AppBundle:Lesson')->find($lessonId);
        $presenceTypes = $em->getRepository('AppBundle:PresenceType')->findAll();
        $defaultPresence = $em->getRepository('AppBundle:SystemParameter')->findOneByName('default_presence');
        $presenceType = $em->getRepository('AppBundle:PresenceType')->find($defaultPresence->getValue());
        foreach ($schoolClass->getStudents() as $student) {
            $presences = $student->getPresences();
            $flag = 0;
            foreach ($presences as $presence) {
                $presenceDate = $presence->getDate();
                if ($presenceDate->format('Y-m-d') == date('Y-m-d', strtotime($date)) && $presence->getLesson() == $lesson) {
                    $flag = 1;
                }
            }
            if ($flag != 1) {
                $newPresence = new Presence();
                $newPresence->setStudent($student);
                $newPresence->setLesson($lesson);
                $newPresence->setChecked(true);
                $newPresence->setPresenceType($presenceType);
                $newPresence->setDate(new \DateTime($date));
                $em->persist($newPresence);
                $em->persist($student);
                $em->flush();
                $em->refresh($student);
            }
        }
        return $this->render('default/Teacher/presence.html.twig', [
                    'schoolClass' => $schoolClass,
                    'lesson' => $lesson,
                    'date' => $date,
                    'presenceType' => $presenceType,
                    'teacher' => $teacher,
                    'presenceTypes' => $presenceTypes
        ]);
    }

    /**
     * @Route("/updatePresence/{presenceId}/{typeId}/", name="updatePresence")
     */
    public function updatePresenceAction(Request $request, $presenceId, $typeId) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $presence = $em->getRepository('AppBundle:Presence')->find($presenceId);
        $presenceType = $em->getRepository('AppBundle:PresenceType')->find($typeId);
        $presence->setPresenceType($presenceType);
        $em->persist($presence);
        $em->flush();
        $response = new JsonResponse($presence);

        return $response;
    }

    /**
     * Get grades types
     *
     * @Route("/presenceGetTypes", name="presence_get_types")
     * @Method("GET")
     */
    public function presenceGetTypesAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository('AppBundle:PresenceType')->findAll();
        $arr = [];
        foreach ($types as $type) {
            if ($type->getForParent() == 1) {
                $arr[] = array('id' => $type->getId(), 'label' => $type->getLabel());
            }
        }
        $response = new JsonResponse($arr);

        return $response;
    }

    /**
     * Adds grade
     *
     * @Route("/presencechange", name="presence_change")
     * @Method("POST")
     */
    public function presenceChangeAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_PARENT', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $studentId = $request->request->get('student');
        $presenceId = $request->request->get('presence');
        $typeId = $request->request->get('type');
        $date = $request->request->get('date');

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getStudentParent()->getStudent()->getId() == $studentId) {
            $presence = $em->getRepository('AppBundle:Presence')->find($presenceId);
            $type = $em->getRepository('AppBundle:PresenceType')->find($typeId);

            $presence->setPresenceType($type);

            $em->persist($presence);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('presencesStudentIndex', array('id' => $studentId, 'date' => $date)), 301);
    }

}
