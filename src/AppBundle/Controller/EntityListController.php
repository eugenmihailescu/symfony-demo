<?php

namespace AppBundle\Controller;

use AppBundle\Controller\GenericController;
use Symfony\Component\HttpFoundation\Response;

final class EntityListController extends GenericController {
	public function browseEntitiesAction($page, $limit) {
		
		$db = new \PDO('pgsql:dbname=dbuqge8db9v8q3;host=ec2-54-228-246-206.eu-west-1.compute.amazonaws.com;user=xvlixttfszxopd;password=AhjY6QDATJCBOa7E_qBJIbFa5Y');
		
		$sql = 'SELECT * FROM wptests_posts';
		foreach ($db->query($sql) as $row) {
			echo $row['post_title'], '<br>',$row['post_date'] ,'<br>';
		}
		
		// check if the current user has list permission
		$this->denyAccessUnlessGranted ( 'list' );
		
		$templating = $this->get ( 'templating' );
		
		// retrieve all installed Doctrine Entity classes
		$entities = array (
				[ ] 
		);
		$em = $this->getDoctrine ()->getManager ();
		$meta = $em->getMetadataFactory ()->getAllMetadata ();
		foreach ( $meta as $m ) {
			$parts = explode ( '\\', $m->getName () );
			$key = end ( $parts );
			
			// dinamically get value by entity annotation (if any)
			$annotation = $this->getEntityAnnotations ( $key, 'AppBundle\Annotation\EntityAnnotation' );
			if ($annotation && ! empty ( $annotation->alias ))
				$entities [0] [] = array (
						$key,
						ucfirst ( $annotation->alias ) 
				);
			else
				$entities [0] [] = array (
						$key,
						$key 
				);
		}
		
		$entities_count = count ( $entities [0] );
		$page_count = ceil ( $entities_count / $limit ); // the total number of pages
		
		$offset = $limit * ($page - 1); // the index of first record on page
		                                
		// filter out entities not within the current page
		$i = 0;
		foreach ( array_keys ( $entities [0] ) as $key ) {
			if ($i < $offset || $i >= $offset + $limit) {
				unset ( $entities [0] [$key] );
			}
			$i ++;
		}
		
		$grid_columns = array (
				$this->trans ( 'entity_name' ) 
		);
		
		$html = $templating->render ( 'AppBundle:entity:list.html.twig', array (
				'data' => $entities,
				'pages' => $page_count,
				'page' => $page,
				'limit' => $limit,
				'route' => '/entities',
				'data_route' => '/entity/browse',
				'entity' => '',
				'columns' => $grid_columns 
		) );
		
		return new Response ( $html );
	}
}