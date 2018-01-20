<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $newses = $em->getRepository('AppBundle:Information')->findAll();
        $allReplecements = $em->getRepository('AppBundle:Replecement')->findAll();
        $today = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $replecements = [];
        foreach ($allReplecements as $rep) {
            if ($rep->getDate() >= $today) {
                $replecements[] = $rep;
            }
        }
        // replace this example code with whatever you need
        return $this->render('default/main.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                    'user' => $user,
                    'newses' => $newses,
                    'replecements' => $replecements
        ]);
    }

    /**
     * @Route("/students/password/export", name="students-password-export")
     */
    public function studentsPasswordExportAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $passwords = [];
        $students = $em->getRepository('AppBundle:Student')->findAll();
        foreach ($students as $student) {
            $username = $student->getUser()->getUsername();
            $parentUsername = $student->getStudentParent()->getUser()->getUsername();
            $password = $em->getRepository('AppBundle:Password')->findOneByUsername($username);
            $parentPassword = $em->getRepository('AppBundle:Password')->findOneByUsername($parentUsername);
            $passwords[] = [$password, $parentPassword];
        }
        $html = $this->render('default/Pdf/studentPasswordExport.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'passwords' => $passwords,
        ]);

        return new PdfResponse(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html), 'uczniowie-hasla.pdf'
        );
    }

    /**
     * @Route("/teachers/password/export", name="teachers-password-export")
     */
    public function teachersPasswordExportAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $passwords = [];
        $teachers = $em->getRepository('AppBundle:Teacher')->findAll();
        foreach ($teachers as $teacher) {
            $username = $teacher->getUser()->getUsername();
            $password = $em->getRepository('AppBundle:Password')->findOneByUsername($username);
            $passwords[] = $password;
        }
        $html = $this->render('default/Pdf/teachersPasswordExport.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'passwords' => $passwords,
        ]);

        return new PdfResponse(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html), 'nauczycielee-hasla.pdf'
        );
    }

    /**
     * @Route("/newses/{page}", name="newses")
     */
    public function newsesAction(Request $request, $page = 1) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $newses = $em->getRepository('AppBundle:Information')->findAllOrdered();
        $paginateNewses = [];
        foreach ($newses as $key => $news) {
            if ($key >= (10 * ($page - 1)) && $key < (10 * $page)) {
                $paginateNewses[] = $news;
            }
        }
        $pages = (intval(count($newses)) / 10) + 1;
        return $this->render('default/newses.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                    'user' => $user,
                    'newses' => $paginateNewses,
                    'pages' => $pages,
                    'page' => $page
        ]);
    }

    /**
     * @Route("/news/{id}", name="news")
     */
    public function newsAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppBundle:Information')->find($id);
        return $this->render('default/news.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                    'news' => $news
        ]);
    }

    /**
     * @Route("/newyear", name="new_year")
     */
    public function newYearAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $newses = $em->getRepository('AppBundle:Information')->findAll();

        $lastClass = $em->getRepository('AppBundle:SystemParameter')->findOneByName('last_class');
        $schoolClasses = $em->getRepository('AppBundle:SchoolClass')->findAll();
        foreach ($schoolClasses as $sc) {
            if ($sc->getNumber() >= $lastClass->getValue()) {
                $em->remove($sc);
            } else {
                $number = $sc->getNumber();
                $number = $number + 1;
                $sc->setNumber($number);
            }
        }
        $lessons = $em->getRepository('AppBundle:Lesson')->findAll();
        foreach ($lessons as $l) {
            $em->remove($l);
        }
        $grades = $em->getRepository('AppBundle:Grade')->findAll();
        foreach ($grades as $g) {
            $em->remove($g);
        }
        $presences = $em->getRepository('AppBundle:Presence')->findAll();
        foreach ($presences as $p) {
            $em->remove($p);
        }
        $replecements = $em->getRepository('AppBundle:Replecement')->findAll();
        foreach ($replecements as $r) {
            $em->remove($r);
        }
        $comments = $em->getRepository('AppBundle:StudentComment')->findAll();
        foreach ($comments as $c) {
            $em->remove($c);
        }

        $em->flush();
        return $this->redirect($this->generateUrl('homepage', [
                            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                            'user' => $user,
                            'newses' => $newses,
                            'replecements' => $replecements
                        ]), 301);
    }

    /**
     * @Route("/myClass", name="my_class")
     */
    public function myClassAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $teacher = $user->getTeacher();
        $schoolClass = $teacher->getClass();
        return $this->render('default/Teacher/myClass.html.twig', [
                    'teacher' => $teacher,
                    'schoolClass' => $schoolClass
        ]);
    }

    /**
     * @Route("/myClass/comments/{id}", name="my_class_comments")
     */
    public function myClassCommentAction(Request $request, $id) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($id);
        return $this->render('default/Teacher/myClassComments.html.twig', [
                    'schoolClass' => $schoolClass
        ]);
    }

    /**
     * @Route("/myClass/subjects/{id}", name="my_class_subjects")
     */
    public function mySubjectsAction(Request $request, $id) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($id);
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
        return $this->render('default/Teacher/myClassSubjects.html.twig', [
                    'subjects' => $subjects,
                    'schoolClass' => $schoolClass,
        ]);
    }

    /**
     * @Route("/myClass/grades/{id}/{schoolClassId}", name="my_class_grades")
     */
    public function myClassGradesAction(Request $request, $id, $schoolClassId) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($schoolClassId);
        $subject = $em->getRepository('AppBundle:Subject')->find($id);
        return $this->render('default/Teacher/myClassGrades.html.twig', [
                    'subject' => $subject,
                    'schoolClass' => $schoolClass
        ]);
    }

    /**
     * @Route("/myClass/presences/{id}/{date}", name="my_class_presences")
     */
    public function myClassPresencesAction(Request $request, $id, $date = 0) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $teacher = $user->getTeacher();
        $schoolClass = $em->getRepository('AppBundle:SchoolClass')->find($id);
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
        return $this->render('default/Teacher/myClassPresences.html.twig', [
                    'teacher' => $teacher,
                    'monday' => $monday,
                    'mondayBefore' => $mondayBefore,
                    'date' => $date,
                    'minDate' => $minDate->getValue(),
                    'maxDate' => $maxDate->getValue(),
                    'schoolClass' => $schoolClass
        ]);
    }

    /**
     * @Route("/contact/teachers", name="teacher_contact")
     */
    public function teacherContactAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_STUDENT', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $teachers = $em->getRepository('AppBundle:Teacher')->findAll();
        return $this->render('default/Student/contact.html.twig', [
                    'teachers' => $teachers,
        ]);
    }

    /**
     * @Route("/contact/students", name="student_contact")
     */
    public function studentContactAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $students = $em->getRepository('AppBundle:Student')->findAll();
        return $this->render('default/Teacher/contact.html.twig', [
                    'students' => $students,
        ]);
    }

}
