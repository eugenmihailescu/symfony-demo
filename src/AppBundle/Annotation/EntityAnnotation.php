<?php

namespace AppBundle\Annotation;

use AppBundle\Annotation\GenericAnnotation;

/**
 * Use this annotation for Entity classes to customize the integration of the entity within the application.
 * @Annotation
 * @Target("CLASS")
 *
 * @author Eugen Mihailescu
 *        
 */
final class EntityAnnotation extends GenericAnnotation {
	/**
	 * The alias that can be used instead of entity name
	 *
	 * @var string
	 */
	public $alias = null;
	/**
	 * The entity public property that points to the entity's primary key
	 *
	 * @var string
	 */
	public $pk = null;
	/**
	 * An array of entity's public properties that represents fields.
	 * There are used when browsing entity's records.
	 *
	 * @var array
	 */
	public $columns = [ ];
	/**
	 * The entity's public function that is called in order to determine if it is allowed to EDIT the current entity or not.
	 *
	 * @var string
	 */
	public $can_edit = null;
	/**
	 * The entity's public function that is called in order to determine if it is allowed to VIEW a record columns of the current entity or not.
	 *
	 * @var string
	 */
	public $can_view = null;
	/**
	 * The entity's public function that is called in order to determine if it is allowed to DELETE records of current entity or not.
	 *
	 * @var string
	 */
	public $can_delete = null;
	public function __construct($options) {
		parent::__construct ( $options );
		
		$this->validateProperty ( array (
				'pk',
				'columns' 
		) );
	}
}