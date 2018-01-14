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
     * @Route("/{id}/{date}", name="presencesIndex")
     */
    public function indexAction(Request $request, $id, $date = 0) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $teacher = $user->getTeacher();
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
                    'date' => $date
        ]);
    }

    /**
     * @Route("/presence/{date}/{schoolClassId}/{lessonId}", name="presence")
     */
    public function presenceAction(Request $request, $date, $schoolClassId, $lessonId) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($schoolClassId);
        $lesson = $em->getRepository('AppBundle:Lesson')->find($lessonId);
        $presenceTypes = $em->getRepository('AppBundle:PresenceType')->findAll();
        $presenceType = $em->getRepository('AppBundle:PresenceType')->findOneByCountAsAbsence(0);
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
                $newPresence->setPresenceType($presenceType);
                $newPresence->setDate(new \DateTime($date));
                $em->persist($newPresence);
                $em->persist($student);
                $em->flush();
                $em->refresh();
            }
        }

        return $this->render('default/Teacher/presence.html.twig', [
                    'schoolClass' => $schoolClass,
                    'lesson' => $lesson,
                    'date' => $date,
                    'presenceType' => $presenceType,
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

}
