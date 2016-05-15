<?php

// src/Mynix\DemoBundle/Security/PostVoter.php
namespace Mynix\DemoBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\User;
use Doctrine\Common\Annotations\AnnotationReader;
use Mynix\DemoBundle\Controller\ExceptionController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class EntityVoter extends Voter {
	private $twig;
	private $debug;
	
	// these strings are just invented: you can use anything
	const VIEW = 'view';
	const EDIT = 'edit';
	const DELETE = 'delete';
	const LIST = 'list';
	
	/**
	 * The number of URL's path parts that must be read in order to get to the entity name part
	 *
	 * @var integer
	 */
	const ENTITY_PATH_LEVEL = 5;
	private $entity = null;
	private $can_edit = null;
	private $can_view = null;
	private $can_delete = null;
	private function getEntityAnnotations($entity, $annotation_name) {
		$reader = new AnnotationReader ();
		
		$reflectionEntity = new \ReflectionClass ( $entity );
		
		return $reader->getClassAnnotation ( $reflectionEntity, $annotation_name );
	}
	public function __construct(\Twig_Environment $twig, $debug, RequestStack $request) {
		$this->twig = $twig;
		$this->debug = $debug;
		
		if (isset ( $_SERVER ['REQUEST_URI'] ))
			$uri = $_SERVER ['REQUEST_URI'];
		else
			$uri = '';
		
		$matches = null;
		
		// check if the current route is either a edit|view|delete route
		if (! preg_match ( '/((\/[a-z]\w*){' . self::ENTITY_PATH_LEVEL . '}(\/\d+)*)/i', $uri, $matches )) {
			return;
		}
		
		// retrieve the metadata from current route entity
		$parts = explode ( '/', $matches [0] );
		
		$this->entity = 'Mynix\DemoBundle\Entity\\' . $parts [self::ENTITY_PATH_LEVEL];
		
		try {
			$annotations = $this->getEntityAnnotations ( $this->entity, 'Mynix\DemoBundle\Annotation\EntityAnnotation' );
		} catch ( \Exception $e ) {
			$ec = new ExceptionController ( $this->twig, $this->debug );
			return $ec->showAction ( $request->request, $e );
		}
		if ($annotations) {
			$this->can_edit = $annotations->can_edit;
			$this->can_view = $annotations->can_view;
			$this->can_delete = $annotations->can_delete;
		}
	}
	protected function supports($attribute, $subject) {
		// if the attribute isn't one we support, return false
		if (! in_array ( $attribute, array (
				self::VIEW,
				self::EDIT,
				self::DELETE,
				self::LIST 
		) )) {
			return false;
		}
		
		// only vote on Post objects inside this voter
		if ($subject && get_class ( $subject ) != $this->entity) {
			return false;
		}
		
		return true;
	}
	protected function voteOnAttribute($attribute, $subject, TokenInterface $token) {
		$user = $token->getUser ();
		
		if (! $user instanceof User) {
			// the user must be logged in; if not, deny access
			return false;
		}
		
		switch ($attribute) {
			case self::VIEW :
				return $this->canView ( $subject, $user );
			case self::EDIT :
				return $this->canEdit ( $subject, $user );
			case self::DELETE :
				return $this->canDelete ( $subject, $user );
			case self::LIST :
				return $this->canList ( $subject, $user );
		}
		
		throw new \LogicException ( 'This code should not be reached!' );
	}
	private function is_admin($user) {
		return in_array ( 'ROLE_ADMIN', $user->getRoles () );
	}
	private function canView($subject, User $user) {
		// if they can edit, they can view
		if ($this->canEdit ( $subject, $user )) {
			return true;
		}
		
		if (method_exists ( $subject, $this->can_view )) {
			return call_user_func ( array (
					$subject,
					$this->can_view 
			) );
		}
		
		return true;
	}
	private function canEdit($subject, User $user) {
		if (! $this->is_admin ( $user ))
			return false;
			
			// the user can edit only if is admin
		if (method_exists ( $subject, $this->can_edit )) {
			return call_user_func ( array (
					$subject,
					$this->can_edit 
			), $user );
		}
		
		return true;
	}
	private function canDelete($subject, User $user) {
		if (! $this->is_admin ( $user ))
			return false;
			
			// if they can edit, they can delete
		if ($this->canEdit ( $subject, $user )) {
			return true;
		}
		
		if (method_exists ( $subject, $this->can_delete )) {
			return call_user_func ( array (
					$subject,
					$this->can_delete 
			) );
		}
		
		return true;
	}
	private function canList($subjet, User $user) {
		return true;
	}
}