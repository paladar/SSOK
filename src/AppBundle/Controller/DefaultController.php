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
        // replace this example code with whatever you need
        return $this->render('default/main.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                    'user' => $user,
                    'newses' => $newses
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

}
