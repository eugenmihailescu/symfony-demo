<?php

namespace AppBundle\Annotation;

use Doctrine\ORM\Mapping\Annotation;

class GenericAnnotation implements Annotation {
	public function __construct($options = []) {
		if (isset ( $options ['value'] )) {
			$options ['propertyName'] = $options ['value'];
			unset ( $options ['value'] );
		}
		
		foreach ( $options as $key => $value ) {
			if (! property_exists ( $this, $key )) {
				throw new \InvalidArgumentException ( sprintf ( 'Property "%s" does not exist', $key ) );
			}
			
			$this->$key = $value;
		}
	}
	public function validateProperty($properties) {
		is_string ( $properties ) && $properties = array (
				$properties 
		);
		
		foreach ( $properties as $property ) {
			if (is_array ( $property )) {
				$empty = true;
				foreach ( $property as $p ) {
					$empty = $empty && empty ( $this->$p );
				}
				if ($empty) {
					throw new \InvalidArgumentException ( sprintf ( '%s annotation should specify at least the `%s` field', get_class ( $this ), implode ( ' or ', $property ) ) );
				}
			} elseif (empty ( $this->$property )) {
				throw new \InvalidArgumentException ( sprintf ( '%s annotation should specify the `%s` field', get_class ( $this ), $property ) );
			}
		}
	}
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Doctrine\Common\Annotations\Annotation::__get()
	 */
	public function __get($name) {
		return $this->$name;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Doctrine\Common\Annotations\Annotation::__set()
	 */
	public function __set($name, $value) {
		$this->$name = $name;
	}
}