<?php

namespace Mynix\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EntityEditController extends GenericController {
	private $is_readonly;
	protected function getButtons($entity, $pk) {
		$buttons = array (
				'back' => $this->generateUrl ( 'view_entity', array (
						'entity' => $entity,
						'id' => $pk,
						'_theme' => $this->getCurrentTheme () 
				) ) 
		);
		
		return $buttons;
	}
	public function __construct($read_only = false) {
		$this->is_readonly = $read_only;
	}
	/**
	 * Render the Edit page of the post given by $id
	 *
	 * @param int $id
	 *        	The ID of the post to render
	 * @param
	 *        	s string $acl Access control string (list|view|edit|delete)
	 * @param Request $request
	 *        	Is the HTTP REQUEST passed automatically by Symfony
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editEntityAction($entity, $id, $acl = 'edit', Request $request) {
		$this->setEntity ( $entity );
		
		$token_id = $this->getTokenId ();
		
		if ($id < 0) {
			// in case of a new post
			$class = "Mynix\DemoBundle\Entity\\" . $entity;
			$post = new $class ();
		} else {
			// in case of an existent post
			$post = $this->getPost ( $id );
		}
		
		// check if the current user has edit permission
		$this->denyAccessUnlessGranted ( $acl, $post );
		
		$annotation = $this->getEntityAnnotations ( 'Mynix\DemoBundle\Annotation\EntityAnnotation' );
		
		$pk = $annotation ? call_user_func ( array (
				$post,
				'get' . ucfirst ( $annotation->pk ) 
		) ) : '';
		
		$form = $this->getPostForm ( $post );
		
		// no error => submit the form
		$form->handleRequest ( $request );
		
		if ($form->isSubmitted () && $form->isValid ()) {
			$token = $request->request->get ( $token_id );
			
			if (! $this->isTokenValid ( $token, $token_id ))
				throw new AccessDeniedHttpException ( 'Invalid token ' . $token );
			
			$translator = $this->get ( 'translator' );
			$this->addFlash ( 'notice', $translator->trans ( 'post_saved' ) );
			
			$em = $this->get ( 'doctrine.orm.entity_manager' );
			$em->persist ( $post );
			$em->flush ();
			
			return $this->redirectToRoute ( 'edit_entity', array (
					'id' => $pk,
					'entity' => $entity,
					'success' => 'saved' 
			) );
		}
		
		// output the edit form
		$templating = $this->get ( 'templating' );
		
		$options = array (
				'form' => $form->createView (),
				'form_id' => $this->getEditFormId (),
				'fields' => $this->getPostFields ( false ),
				'id' => $pk,
				'is_readonly' => $this->is_readonly,
				'routes' => $this->getButtons ( $entity, $pk ) 
		);
		
		$this->is_readonly || $options ['token_id'] = $this->getTokenId ( $token_id );
		
		$html = $templating->render ( 'Mynix\DemoBundle:entity:edit.html.twig', $options );
		
		return new Response ( $html );
	}
}