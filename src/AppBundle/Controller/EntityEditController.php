<?php

namespace AppBundle\Controller;

use AppBundle\Controller\GenericController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EntityEditController extends GenericController {
	/**
	 * Render the Edit page of the post given by $id
	 *
	 * @param int $id
	 *        	The ID of the post to render
	 * @param Request $request
	 *        	Is the HTTP REQUEST passed automatically by Symfony
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editEntityAction($entity, $id, Request $request) {
		if ($id < 0) {
			// in case of a new post
			$class = "AppBundle\Entity\\" . $entity;
			$post = new $class ();
		} else {
			// in case of an existent post
			$post = $this->getPost ( $entity, $id );
		}
		
		// check if the current user has edit permission
		$this->denyAccessUnlessGranted ( 'edit', $post );
		
		$form = $this->getPostForm ( $entity, $post );
		
		// no error => submit the form
		$form->handleRequest ( $request );
		
		if ($form->isSubmitted () && $form->isValid ()) {
			$translator = $this->get ( 'translator' );
			$this->addFlash ( 'notice', $translator->trans ( 'post_saved' ) );
			
			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist ( $post );
			$em->flush ();
			
			return $this->redirectToRoute ( 'edit_entity', array (
					'id' => $post->getId (),
					'entity' => $entity,
					'success' => 'saved' 
			) );
		}
		
		// output the edit form
		$templating = $this->get ( 'templating' );
		
		$annotation = $this->getEntityAnnotations ( $entity, 'AppBundle\Annotation\EntityAnnotation' );
		
		$html = $templating->render ( 'AppBundle:entity:edit.html.twig', array (
				'form' => $form->createView (),
				'form_id' => $this->getEditFormId (),
				'fields' => $this->getPostFields ( $entity, false ),
				'id' => $annotation ? call_user_func ( array (
						$post,
						'get' . ucfirst ( $annotation->pk ) 
				) ) : '',
				'routes' => array (
						'browse' => $this->generateUrl ( 'browse_entity', array (
								'entity' => $entity 
						) ) 
				) 
		) );
		
		return new Response ( $html );
	}
}