<?php

namespace Universibo\Bundle\ContentBundle\Controller;

use Universibo\Bundle\ContentBundle\Entity\NewsRepository;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Universibo\Bundle\ContentBundle\Entity\News;
use Universibo\Bundle\ContentBundle\Form\NewsType;

/**
 * News controller.
 *
 * @Route("/news")
 */
class NewsController extends Controller
{
    /**
     * Lists all News entities.
     *
     * @Route("/", name="news")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('UniversiboContentBundle:News')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}/show", name="news_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('UniversiboContentBundle:News')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new News entity.
     *
     * @Route("/new", name="news_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new News();
        $form   = $this->createForm(new NewsType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new News entity.
     *
     * @Route("/create", name="news_create")
     * @Method("post")
     * @Template("UniversiboContentBundle:News:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new News();
        $request = $this->getRequest();
        $form    = $this->createForm(new NewsType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            
            $entity->setUser($this->get('security.context')->getToken()->getUser());
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('news_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", name="news_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('UniversiboContentBundle:News')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $editForm = $this->createForm(new NewsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing News entity.
     *
     * @Route("/{id}/update", name="news_update")
     * @Method("post")
     * @Template("UniversiboContentBundle:News:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('UniversiboContentBundle:News')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $editForm   = $this->createForm(new NewsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $entity->setUpdatedAt(new \DateTime());
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('news_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a News entity.
     *
     * @Route("/{id}/delete", name="news_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('UniversiboContentBundle:News')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find News entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('news'));
    }
    
    /**
     * @param Channel $channel
     * 
     * @Template()
     */
    public function boxAction(Channel $channel, $limit)
    {
        $repo = $this->get('universibo_content.repository.news');
        $news = $repo->findByChannel($channel, $limit);
        
        return array('news' => $news);
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
