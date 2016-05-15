<?php

namespace Mynix\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EntityEditController extends GenericController {
	private $is_readonly;
	
	/**
	 * Constructor
	 *
	 * @param bool $read_only
	 *        	When true renders a readonly form, otherwise a read-write form
	 */
	public function __construct($read_only = false) {
		$this->is_readonly = $read_only;
	}
	
	/**
	 * Returns the screen buttons
	 * 
	 * @param string $entity
	 *        	The current entity
	 * @param string $pk
	 *        	The primary key value (empty on a new record)
	 * @return arrayy
	 */
	protected function getButtons($entity, $pk) {
		$args = array (
				'entity' => $entity,
				'_theme' => $this->getCurrentTheme () 
		);
		
		empty ( $pk ) || $args ['id'] = $pk;
		
		$buttons = array (
				'back' => $this->generateUrl ( 'browse_entity', $args ) 
		);
		
		return $buttons;
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
			$item = new $class ();
		} else {
			// in case of an existent post
			$item = $this->getPost ( $id );
		}
		
		// check if the current user has edit permission
		$this->denyAccessUnlessGranted ( $acl, $item );
		
		$annotation = $this->getEntityAnnotations ( 'Mynix\DemoBundle\Annotation\EntityAnnotation' );
		
		$callback = array (
				$item,
				'get' . ucfirst ( $annotation->pk ) 
		);
		
		$form = $this->getPostForm ( $item );
		
		// no error => submit the form
		$form->handleRequest ( $request );
		
		if ($form->isSubmitted () && $form->isValid ()) {
			$token = $request->request->get ( $token_id );
			
			if (! $this->isTokenValid ( $token, $token_id ))
				throw new AccessDeniedHttpException ( 'Invalid token ' . $token );
			
			$translator = $this->get ( 'translator' );
			$this->addFlash ( 'notice', $translator->trans ( 'post_saved' ) );
			
			$em = $this->get ( 'doctrine.orm.entity_manager' );
			$em->persist ( $item );
			$em->flush ();
			
			$pk = $annotation && is_callable ( $callback ) ? call_user_func ( $callback ) : '';
			
			return $this->redirectToRoute ( 'edit_entity', array (
					'id' => $pk,
					'entity' => $entity,
					'success' => 'saved' 
			) );
		}
		
		$pk = $annotation && is_callable ( $callback ) ? call_user_func ( $callback ) : '';
		
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
		
		$html = $templating->render ( 'MynixDemoBundle:entity:edit.html.twig', $options );
		
		return new Response ( $html );
	}
}