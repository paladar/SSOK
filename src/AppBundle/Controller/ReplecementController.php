<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Replecement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Replecement controller.
 *
 * @Route("replecement")
 */
class ReplecementController extends Controller
{
    /**
     * Lists all replecement entities.
     *
     * @Route("/", name="replecement_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $replecements = $em->getRepository('AppBundle:Replecement')->findAll();

        return $this->render('replecement/index.html.twig', array(
            'replecements' => $replecements,
        ));
    }

    /**
     * Creates a new replecement entity.
     *
     * @Route("/new", name="replecement_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $replecement = new Replecement();
        $form = $this->createForm('AppBundle\Form\ReplecementType', $replecement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($replecement);
            $em->flush();

            return $this->redirectToRoute('replecement_show', array('id' => $replecement->getId()));
        }

        return $this->render('replecement/new.html.twig', array(
            'replecement' => $replecement,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a replecement entity.
     *
     * @Route("/{id}", name="replecement_show")
     * @Method("GET")
     */
    public function showAction(Replecement $replecement)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        return $this->render('replecement/show.html.twig', array(
            'replecement' => $replecement,
        ));
    }

    /**
     * Displays a form to edit an existing replecement entity.
     *
     * @Route("/{id}/edit", name="replecement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Replecement $replecement)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($replecement);
        $editForm = $this->createForm('AppBundle\Form\ReplecementType', $replecement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('replecement_edit', array('id' => $replecement->getId()));
        }

        return $this->render('replecement/edit.html.twig', array(
            'replecement' => $replecement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a replecement entity.
     *
     * @Route("/{id}", name="replecement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Replecement $replecement)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $form = $this->createDeleteForm($replecement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($replecement);
            $em->flush();
        }

        return $this->redirectToRoute('replecement_index');
    }

    /**
     * Creates a form to delete a replecement entity.
     *
     * @param Replecement $replecement The replecement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Replecement $replecement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('replecement_delete', array('id' => $replecement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
