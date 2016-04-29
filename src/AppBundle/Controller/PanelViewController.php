<?php

namespace AppBundle\Controller;

use AppBundle\Controller\GenericController;
use Symfony\Component\HttpFoundation\Response;

final class PanelViewController extends GenericController {
	/**
	 * Render the View page of the post given by $id
	 *
	 * @param string $entity
	 *        	The entity name to query
	 * @param int $id
	 *        	The ID of the post to render
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function previewEntityAction($entity, $id) {
		$post = $this->getPost ( $entity, $id );
		
		// check if the current user has view permission
		$this->denyAccessUnlessGranted ( 'view', $post );
		
		$templating = $this->get ( 'templating' );
		
		$annotation = $this->getEntityAnnotations ( $entity, 'AppBundle\Annotation\EntityAnnotation' );
		
		if ($annotation) {
			$html = $templating->render ( 'AppBundle:entity:view.html.twig', array (
					'pk' => $annotation->pk,
					'item' => $post,
					'fields' => $this->getPostFields ( $entity ),
					'entity' => $entity 
			) );
		} else {
			$html = $templating->render ( 'AppBundle:error:entity_annotation.html.twig', array (
					'entity' => $entity,
					'annotation' => 'EntityAnnotation' 
			) );
		}
		
		return new Response ( $html );
	}
}