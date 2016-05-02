<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Annotation\LookupAnnotation;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GenericController extends Controller {
	/**
	 * The default entity class name for the current controller
	 *
	 * @var string
	 */
	private $entity;
	
	/**
	 * The browser accepted language code
	 *
	 * @var string
	 */
	protected $accepted_lang;
	
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\DependencyInjection\ContainerAwareInterface::setContainer()
	 */
	public function setContainer(ContainerInterface $container = null) {
		parent::setContainer ( $container );
		
		$lang_autodetect = $this->get ( 'lang.autodetect' );
		
		$this->accepted_lang = null;
		
		if ($lang_autodetect) {
			$this->accepted_lang = $lang_autodetect->getPreferedLanguage ();
		}
	}
	
	/**
	 * Checks if the entity exists
	 *
	 * @param string $entity_name        	
	 */
	private function validateEntity($entity_name = null) {
		$entity_name = empty ( $entity_name ) ? $this->entity : $entity_name;
		if (empty ( $entity_name ) || ! class_exists ( 'AppBundle\Entity\\' . $entity_name )) {
			$entity_status = ! get_class ( $this ) ? 'empty' : 'invalid';
			throw new \Exception ( sprintf ( 'The entity "<code>%s</code>" specified for the class %s is %s.', basename ( $entity_name ), get_class ( $this ), $entity_status ) );
		}
	}
	
	/**
	 * Returns the annotations for the specified field name of the current entity.
	 *
	 * @param string $field_name        	
	 * @param string $annotation_name        	
	 * @return array
	 */
	private function getFieldAnnotations($field_name, $annotation_name) {
		$reflectionProperty = new \ReflectionProperty ( 'AppBundle\Entity\\' . $this->entity, $field_name );
		
		$reader = $this->get ( 'annotation_reader' );
		
		return $reader->getPropertyAnnotation ( $reflectionProperty, $annotation_name );
	}
	
	/**
	 * Returns the field type class name for the specified field of the current entity.
	 *
	 * @param string $field_name        	
	 * @return mixed|NULL
	 */
	private function getFieldType($field_name) {
		
		// attempt to get the field type by type annotation helper
		$annotation = $this->getFieldAnnotations ( $field_name, 'AppBundle\Annotation\FormFieldTypeAnnotation' );
		
		if ($annotation && $annotation->field_type) {
			$class = 'Symfony\Component\Form\Extension\Core\Type\\' . $annotation->field_type . 'Type';
			return $class;
		}
		
		// attempt to get the field type by choice annotation helper
		$choices = $this->getFieldChoices ( $field_name );
		
		if ($choices) {
			return ChoiceType::class;
		}
		
		// this field is not a choice type or has no helper annotation
		return null;
	}
	
	/**
	 * Returns the choices options set as annotation for the specified field of the current entity.
	 *
	 * @param string $field_name        	
	 * @return mixed|NULL
	 */
	private function getFieldChoices($field_name) {
		
		// (1) attempt to get the choices from field's choice annotation
		$choiceAnnotation = $this->getFieldAnnotations ( $field_name, 'Symfony\Component\Validator\Constraints\Choice' );
		
		if ($choiceAnnotation && is_array ( $choiceAnnotation->choices )) {
			return array_combine ( $choiceAnnotation->choices, $choiceAnnotation->choices );
		}
		
		// (2) attempt to get the choices from field's lookup anootation
		$lookupAnnotation = $this->getFieldAnnotations ( $field_name, 'AppBundle\Annotation\LookupAnnotation' );
		
		if ($lookupAnnotation) {
			$target_entity = $lookupAnnotation->target_entity;
			$lookup_property = $lookupAnnotation->lookup_property;
			$lookup_function = $lookupAnnotation->lookup_function;
			$property_exists = ! empty ( $target_entity ) && property_exists ( $target_entity, $lookup_property );
			$function_exists = ! empty ( $target_entity ) && method_exists ( $target_entity, $lookup_function );
			
			if (! empty ( $target_entity ) && class_exists ( $target_entity ) && ($property_exists || $function_exists)) {
				$items = array ();
				$lookup_criteria = $lookupAnnotation->lookup_criteria;
				$lookup_orderby = $lookupAnnotation->lookup_orderBy;
				$lookup_limit = $lookupAnnotation->lookup_limit;
				$lookup_offset = $lookupAnnotation->lookup_offset;
				$this->entity_choices = $this->getDoctrine ()->getRepository ( $target_entity )->findBy ( $lookup_criteria, $lookup_orderby, $lookup_limit, $lookup_offset );
				
				foreach ( $this->entity_choices as $choice ) {
					$key = $choice->getId ();
					
					// get the choice value dinamically (by property or function)
					if ($property_exists) {
						$value = $choice->$lookup_property;
					} else {
						$value = call_user_func ( array (
								$choice,
								$lookup_function 
						) );
					}
					
					// add a new choice item (key => value)
					$items [$key] = $value;
				}
				
				return $items;
			}
		}
		
		// (3) this field is not a choice type or has no helper annotation
		return null;
	}
	
	/**
	 * Returns the ID of the form
	 *
	 * @return string
	 */
	protected function getEditFormId() {
		return str_replace ( '\\', '_', strtolower ( get_class ( $this ) ) );
	}
	
	/**
	 * Returns the post given by $id
	 *
	 * @param int $id
	 *        	The ID of the post to return
	 * @return object|NULL
	 */
	protected function getPost($id) {
		$this->validateEntity ();
		
		$post = $this->getDoctrine ()->getRepository ( 'AppBundle:' . $this->entity )->find ( $id );
		
		return $post;
	}
	
	/**
	 * Returns the post field names
	 *
	 * @param bool $include_id        	
	 * @return array
	 */
	protected function getPostFields($include_id = true) {
		$this->validateEntity ();
		
		$em = $this->get ( 'doctrine.orm.entity_manager' );
		
		$fields = $em->getClassMetadata ( 'AppBundle:' . $this->entity )->getFieldNames ();
		
		$annotation = $this->getEntityAnnotations ( 'AppBundle\Annotation\EntityAnnotation' );
		
		// remove the `id` field from result
		if (! $include_id) {
			$index = array_search ( $annotation->pk, $fields );
			
			if (false !== $index) {
				unset ( $fields [$index] );
			}
		}
		
		return $fields;
	}
	
	/**
	 * Returns the current entity annotations
	 *
	 * @param string $annotation_name        	
	 * @param string $entity_name        	
	 * @return array
	 */
	protected function getEntityAnnotations($annotation_name, $entity_name = null) {
		$entity_name = empty ( $entity_name ) ? $this->entity : $entity_name;
		
		$this->validateEntity ( $entity_name );
		
		$reflectionEntity = new \ReflectionClass ( 'AppBundle\Entity\\' . $entity_name );
		
		$reader = $this->get ( 'annotation_reader' );
		
		return $reader->getClassAnnotation ( $reflectionEntity, $annotation_name );
	}
	
	/**
	 * Creates the form associated with a $post
	 *
	 * @return \Symfony\Component\Form\Form
	 */
	protected function getPostForm($post) {
		$this->validateEntity ();
		
		$formBuilder = $this->createFormBuilder ( $post, array (
				'attr' => array (
						'class' => 'form-horizontal well bs-component block-shadow',
						'id' => $this->getEditFormId () 
				) 
		) );
		
		$fields = $this->getPostFields ( false );
		
		foreach ( $fields as $field_name ) {
			$options = array (
					'attr' => array (
							'class' => 'form-control' 
					) 
			);
			
			$field_options = $this->getFieldChoices ( $field_name );
			
			if ($field_options) {
				$options ['choices'] = array_flip ( $field_options );
			}
			
			$formBuilder->add ( $field_name, $this->getFieldType ( $field_name ), $options );
		}
		
		$form = $formBuilder->getForm ();
		
		return $form;
	}
	
	/**
	 * Translates the given string using the translator service
	 *
	 * @param string $string        	
	 * @param array $parameters        	
	 * @param string $domain        	
	 * @param string $locale        	
	 */
	protected function trans($string, $parameters = [], $domain = null, $locale = null) {
		$translator = $this->get ( 'translator' );
		
		return $translator->trans ( $string, $parameters, $domain, $locale );
	}
	
	/**
	 * Returns the entity's token Id.
	 *
	 * @param string $default_id
	 *        	The default Id. This overrides the class specific token Id.
	 * @return string
	 */
	protected function getTokenId($default_id = null) {
		return empty ( $default_id ) ? preg_replace ( '/[^\w\d]/', '', get_class ( $this ) ) : $default_id;
	}
	
	/**
	 * Check if the given token for the specified token id is valid.
	 *
	 * @param string $token
	 *        	The token string
	 * @param string $id
	 *        	The token identifier
	 * @return boolean
	 */
	protected function isTokenValid($token, $id = null) {
		return $this->isCsrfTokenValid ( $this->getTokenId ( $id ), $token );
	}
	
	/**
	 * Delete the post given by $id
	 *
	 * @param int $id
	 *        	The ID of the post to be deleted
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteEntityAction($id, $entity) {
		$this->setEntity ( $entity );
		
		$this->validateEntity ();
		
		$post = $this->getPost ( $id );
		
		// check if the current user has delete permission
		$this->denyAccessUnlessGranted ( 'delete', $post );
		
		$em = $this->get ( 'doctrine.orm.entity_manager' );
		
		$em->remove ( $post );
		
		$em->flush ();
		
		$this->addFlash ( 'notice', $this->trans ( 'post_deleted' ) );
		
		return $this->redirectToRoute ( 'browse_entity', array (
				'entity' => $this->entity 
		) );
	}
	
	/**
	 * Sets the current controller entity name.
	 * This should be called by any child instance *Action method.
	 *
	 * @param string $entity_name        	
	 */
	public function setEntity($entity_name) {
		$this->entity = $entity_name;
	}
}