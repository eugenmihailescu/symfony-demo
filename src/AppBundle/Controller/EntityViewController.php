<?php

namespace AppBundle\Controller;

use AppBundle\Controller\GenericController;
use Symfony\Component\HttpFoundation\Response;

final class EntityViewController extends GenericController {
	/**
	 * Render the View page of the post given by $id
	 *
	 * @param string $entity
	 *        	The entity name to query
	 * @param int $id
	 *        	The ID of the post to render
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function viewEntityAction($entity, $id) {
		$post = $this->getPost ( $entity, $id );
		
		// check if the current user has view permission
		$this->denyAccessUnlessGranted ( 'view', $post );
		
		$templating = $this->get ( 'templating' );
		
		$annotation = $this->getEntityAnnotations ( $entity, 'AppBundle\Annotation\EntityAnnotation' );
		
		if ($annotation) {
			$html = $templating->render ( 'AppBundle:entity:view.html.twig', array (
					'item' => $post,
					'fields' => $this->getPostFields ( $entity ),
					'routes' => array (
							'back' => $this->generateUrl ( 'browse_entity', array (
									'entity' => $entity 
							) ),
							'edit' => $this->generateUrl ( 'edit_entity', array (
									'entity' => $entity,
									'id' => $id 
							) ),
							'delete' => $this->generateUrl ( 'delete_entity', array (
									'entity' => $entity,
									'id' => $id 
							) ) 
					) 
			) );
		} else {
			$annotation = 'EntityAnnotationn';
			$message = $this->trans ( 'entity.annotation.error.reason', array (
					'entity.name' => $entity,
					'annotation.name' => $annotation 
			) );
			$message .= '<br><br>' . $this->trans ( 'entity.annotation.error.hint', array (
					'entity.name' => $entity,
					'annotation.name' => $annotation 
			) );
			throw new \Exception ( $message );
		}
		
		return new Response ( $html );
	}
}