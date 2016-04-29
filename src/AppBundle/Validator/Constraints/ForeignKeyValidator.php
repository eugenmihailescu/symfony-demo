<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 *
 * @author Eugen Mihailescu
 *        
 */
class ForeignKeyValidator extends ConstraintValidator {
	private $doctrine;
	private $message = '';
	public function __construct($doctrine) {
		$this->doctrine = $doctrine;
	}
	public function validate($value, Constraint $constraint) {
		if (! $value)
			return;
		
		$parent_post = $this->doctrine->getRepository ( 'AppBundle\Entity\WptestsPosts' )->find ( $value );
		
		if (NULL === $parent_post) {
			$this->context->buildViolation ( $constraint->message )->atPath ( $constraint->name )->addViolation ();
		}
	}
}