<?php

namespace AppBundle\Controller;

use AppBundle\Controller\GenericController;
use Symfony\Component\HttpFoundation\Response;

final class GridViewController extends GenericController {
	/**
	 *
	 * @param string $entity
	 *        	The entity name to query
	 * @param int $page
	 *        	The current page number
	 * @param int $limit
	 *        	The number of records per page
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function browseEntitiesAction($entity, $page, $limit) {
		$post_repository = $this->getDoctrine ()->getRepository ( 'AppBundle:' . $entity );
		
		$em = $this->getDoctrine ()->getManager ();
		
		$persister = $em->getUnitOfWork ()->getEntityPersister ( 'AppBundle:' . $entity );
		
		$page_count = ceil ( $persister->count () / $limit ); // the total number of pages
		
		$offset = $limit * ($page - 1); // the index of first record on page
		
		$posts = $post_repository->findBy ( array (), null, $limit, $offset ); // array of Posts
		
		$templating = $this->get ( 'templating' );
		
		$annotation = $this->getEntityAnnotations ( $entity, 'AppBundle\Annotation\EntityAnnotation' );
		
		if ($annotation) {
			$columns = array_merge ( array (
					$annotation->pk 
			), $annotation->columns );
			$data = array ();
			foreach ( $columns as $column ) {
				$function = 'get' . ucfirst ( $column );
				$data [] = array ();
				$i = count ( $data ) - 1;
				foreach ( $posts as $post ) {
					$value = $post->$function ();
					$data [$i] [] = array (
							$value,
							$value 
					);
				}
			}
			
			$html = $templating->render ( 'AppBundle:entity:browse.html.twig', array (
					'pk' => $annotation->pk,
					'columns' => $columns,
					'entity_alias' => $annotation->alias,
					'data' => $data,
					'data_route' => '/entity/view/' . $entity,
					'pages' => $page_count,
					'page' => $page,
					'limit' => $limit,
					'entity' => $entity,
					'route' => '/entity/browse/' 
			) );
		} else {
			$html = $templating->render ( 'AppBundle:error:entity_annotation.html.twig', array (
					'entity' => $entity,
					'annotation' => 'EntityAnnotationn' 
			) );
		}
		
		return new Response ( $html );
	}
}