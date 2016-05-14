<?php

namespace Mynix\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Mynix\DemoBundle\GenericController;

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
		$this->setEntity ( $entity );
		
		$post_repository = $this->getDoctrine ()->getRepository ( 'Mynix\DemoBundle:' . $entity );
		
		$em = $this->get ( 'doctrine.orm.entity_manager' );
		
		$persister = $em->getUnitOfWork ()->getEntityPersister ( 'Mynix\DemoBundle:' . $entity );
		
		$page_count = ceil ( $persister->count () / $limit ); // the total number of pages
		
		$offset = $limit * ($page - 1); // the index of first record on page
		
		$posts = $post_repository->findBy ( array (), null, $limit, $offset ); // array of Posts
		
		$templating = $this->get ( 'templating' );
		
		$annotation = $this->getEntityAnnotations ( 'Mynix\DemoBundle\Annotation\EntityAnnotation' );
		
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
			
			$html = $templating->render ( 'Mynix\DemoBundle:entity:browse.html.twig', array (
					'pk' => $annotation->pk,
					'columns' => $columns,
					'entity_alias' => $annotation->alias,
					'data' => $data,
					'pages' => $page_count,
					'page' => $page,
					'limit' => $limit,
					'routes' => array (
							'browse' => $this->generateUrl ( 'browse_entity', array (
									'entity' => $entity,
									'_theme' => $this->getCurrentTheme () 
							) ),
							// the record-specific route is rendered inside template
							'grid' => $this->generateUrl ( 'view_entity', array (
									'entity' => $entity,
									'_theme' => $this->getCurrentTheme () 
							) ) . '/',
							'insrec' => $this->generateUrl ( 'edit_entity', array (
									'entity' => $entity,
									'_theme' => $this->getCurrentTheme () 
							) ),
							'back' => $this->generateUrl ( 'entities_list', array (
									'_theme' => $this->getCurrentTheme () 
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