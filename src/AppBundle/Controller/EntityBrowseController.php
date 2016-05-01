<?php

namespace AppBundle\Controller;

use AppBundle\Controller\GenericController;
use Symfony\Component\HttpFoundation\Response;

final class EntityBrowseController extends GenericController {
	/**
	 * Browse the entity's items
	 *
	 * @param string $entity
	 *        	The entity name to query
	 * @param int $page
	 *        	The current page number
	 * @param int $limit
	 *        	The number of records per page
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function browseEntityAction($entity, $page, $limit) {
		$this->setEntity($entity);
		
		$post_repository = $this->getDoctrine ()->getRepository ( 'AppBundle:' . $entity );
		
		$em = $this->get('doctrine.orm.entity_manager');
		
		$persister = $em->getUnitOfWork ()->getEntityPersister ( 'AppBundle:' . $entity );
		
		$page_count = ceil ( $persister->count () / $limit ); // the total number of pages
		
		$offset = $limit * ($page - 1); // the index of first record on page
		
		$posts = $post_repository->findBy ( array (), null, $limit, $offset ); // array of Posts
		
		$templating = $this->get ( 'templating' );
		
		$annotation = $this->getEntityAnnotations (  'AppBundle\Annotation\EntityAnnotation' );
		
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
					'pages' => $page_count,
					'page' => $page,
					'limit' => $limit,
					'routes' => array (
							'browse' => $this->generateUrl ( 'browse_entity', array (
									'entity' => $entity 
							) ),
							// the record-specific route is rendered inside template
							'grid' => $this->generateUrl ( 'view_entity', array (
									'entity' => $entity 
							) ) . '/',
							'insrec' => $this->generateUrl ( 'edit_entity', array (
									'entity' => $entity 
							) ),
							'back' => $this->generateUrl ( 'entities_list' ) 
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