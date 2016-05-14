<?php

namespace MynixSymfonyDemoBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 *
 * @author Eugen Mihailescu
 *        
 */
final class LookupAnnotation extends GenericAnnotation {
	/**
	 *
	 * @var string
	 */
	public $target_entity = null;
	/**
	 *
	 * @var string
	 */
	public $lookup_property = null;
	/**
	 *
	 * @var string
	 */
	public $lookup_function = null;
	/**
	 *
	 * @var array
	 */
	public $lookup_criteria = [ ];
	/**
	 *
	 * @var array
	 */
	public $lookup_orderBy = [ ];
	/**
	 *
	 * @var integer
	 */
	public $lookup_limit = null;
	/**
	 *
	 * @var integer
	 */
	public $lookup_offset = null;
	public function __construct($options = []) {
		parent::__construct ( $options );
		
		$this->validateProperty ( array (
				'target_entity',
				array (
						'lookup_property',
						'lookup_function' 
				) 
		) );
	}
}