<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SystemParameter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Systemparameter controller.
 *
 * @Route("systemparameter")
 */
class SystemParameterController extends Controller
{
    /**
     * Lists all systemParameter entities.
     *
     * @Route("/", name="systemparameter_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $systemParameters = $em->getRepository('AppBundle:SystemParameter')->findAll();

        return $this->render('systemparameter/index.html.twig', array(
            'systemParameters' => $systemParameters,
        ));
    }

    /**
     * Displays a form to edit an existing systemParameter entity.
     *
     * @Route("/{id}/edit", name="systemparameter_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SystemParameter $systemParameter)
    {
        $editForm = $this->createForm('AppBundle\Form\SystemParameterType', $systemParameter);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('systemparameter_edit', array('id' => $systemParameter->getId()));
        }

        return $this->render('systemparameter/edit.html.twig', array(
            'systemParameter' => $systemParameter,
            'edit_form' => $editForm->createView(),
        ));
    }
}