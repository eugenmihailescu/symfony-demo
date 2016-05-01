<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EntityViewController extends EntityEditController {
	protected function getButtons($entity, $pk) {
		$buttons = array (
				'back' => $this->generateUrl ( 'browse_entity', array (
						'entity' => $entity 
				) ),
				'edit' => $this->generateUrl ( 'edit_entity', array (
						'entity' => $entity,
						'id' => $pk 
				) ),
				'delete' => $this->generateUrl ( 'delete_entity', array (
						'entity' => $entity,
						'id' => $pk 
				) ) 
		);
		
		return $buttons;
	}
	public function __construct($read_only = false) {
		$read_only = true;
		
		parent::__construct ( $read_only );
	}
	/**
	 * Render the View page of the post given by $id
	 *
	 * @param string $entity
	 *        	The entity name to query
	 * @param int $id
	 *        	The ID of the post to render
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function viewEntityAction($entity, $id, Request $request) {
		return $this->editEntityAction ( $entity, $id, 'view', $request );
	}
}