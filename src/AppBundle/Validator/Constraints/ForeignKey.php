<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ForeignKey extends Constraint {
	public $name = null;
	public $message = null;
	public function validatedBy() {
		return get_class ( $this ) . 'Validator';
	}
}