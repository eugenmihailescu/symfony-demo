<?php

namespace Mynix\DemoBundle\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 *
 * @author Eugen Mihailescu
 *        
 */
final class FormFieldTypeAnnotation extends GenericAnnotation {
	/**
	 *
	 * @var string
	 */
	public $field_type = null;
	public function __construct($options = []) {
		parent::__construct ( $options );
		
		$this->validateProperty ( 'field_type' );
	}
}