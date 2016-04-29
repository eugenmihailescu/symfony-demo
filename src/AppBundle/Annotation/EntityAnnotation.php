<?php

namespace AppBundle\Annotation;

use AppBundle\Annotation\GenericAnnotation;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * @author Eugen Mihailescu
 *        
 */
final class EntityAnnotation extends GenericAnnotation {
	public $alias = null;
	public $pk = null;
	public $columns = [ ];
	public $can_edit = null;
	public $can_view = null;
	public $can_delete = null;
	public function __construct($options) {
		parent::__construct ( $options );
		
		$this->validateProperty ( array (
				'pk',
				'columns' 
		) );
	}
}