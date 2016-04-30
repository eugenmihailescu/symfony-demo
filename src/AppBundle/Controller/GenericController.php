<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Common\Annotations\AnnotationReader;
use AppBundle\Annotation\LookupAnnotation;

class GenericController extends Controller {
	
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
	 * @param string $entity
	 *        	The entity name to query
	 * @param int $id
	 *        	The ID of the post to return
	 * @return object|NULL
	 */
	protected function getPost($entity, $id) {
		$post = $this->getDoctrine ()->getRepository ( 'AppBundle:' . $entity )->find ( $id );
		
		return $post;
	}
	
	/**
	 * Returns the post field names
	 *
	 * @param bool $include_id        	
	 * @return array
	 */
	protected function getPostFields($entity, $include_id = true) {
		$em = $this->getDoctrine ()->getManager ();
		
		$fields = $em->getClassMetadata ( 'AppBundle:' . $entity )->getFieldNames ();
		
		// remove the `id` field from result
		if (! $include_id) {
			$index = array_search ( 'id', $fields );
			
			if (false !== $index) {
				unset ( $fields [$index] );
			}
		}
		
		return $fields;
	}
	protected function getEntityAnnotations($entity, $annotation_name) {
		$reader = new AnnotationReader ();
		
		$reflectionEntity = new \ReflectionClass ( 'AppBundle\Entity\\' . $entity );
		
		return $reader->getClassAnnotation ( $reflectionEntity, $annotation_name );
	}
	
	/**
	 *
	 * @param unknown $field_name        	
	 * @param unknown $annotation_name        	
	 * @return Ambiguous
	 */
	private function getFieldAnnotations($entity, $field_name, $annotation_name) {
		$reader = new AnnotationReader ();
		
		$reflectionProperty = new \ReflectionProperty ( 'AppBundle\Entity\\' . $entity, $field_name );
		
		return $reader->getPropertyAnnotation ( $reflectionProperty, $annotation_name );
	}
	
	/**
	 *
	 * @param string $field_name        	
	 * @return mixed|NULL
	 */
	private function getFieldType($entity, $field_name) {
		
		// attempt to get the field type by type annotation helper
		$annotation = $this->getFieldAnnotations ( $entity, $field_name, 'AppBundle\Annotation\FormFieldTypeAnnotation' );
		if ($annotation && $annotation->field_type) {
			$class = 'Symfony\Component\Form\Extension\Core\Type\\' . $annotation->field_type . 'Type';
			return $class;
		}
		
		// attempt to get the field type by choice annotation helper
		$choices = $this->getFieldChoices ( $entity, $field_name );
		
		if ($choices) {
			return ChoiceType::class;
		}
		
		// this field is not a choice type or has no helper annotation
		return null;
	}
	
	/**
	 *
	 * @param string $field_name        	
	 * @return mixed|NULL
	 */
	private function getFieldChoices($entity, $field_name) {
		
		// (1) attempt to get the choices from field's choice annotation
		$choiceAnnotation = $this->getFieldAnnotations ( $entity, $field_name, 'Symfony\Component\Validator\Constraints\Choice' );
		
		if ($choiceAnnotation && is_array ( $choiceAnnotation->choices )) {
			return array_combine ( $choiceAnnotation->choices, $choiceAnnotation->choices );
		}
		
		// (2) attempt to get the choices from field's lookup anootation
		$lookupAnnotation = $this->getFieldAnnotations ( $entity, $field_name, 'AppBundle\Annotation\LookupAnnotation' );
		
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
				$entity_choices = $this->getDoctrine ()->getRepository ( $target_entity )->findBy ( $lookup_criteria, $lookup_orderby, $lookup_limit, $lookup_offset );
				
				foreach ( $entity_choices as $choice ) {
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
	 * Creates the form associated with a $post
	 *
	 * @param Entity $entity
	 *        	The entity object
	 * @return \Symfony\Component\Form\Form
	 */
	protected function getPostForm($entity, $post) {
		$formBuilder = $this->createFormBuilder ( $post, array (
				'attr' => array (
						'class' => 'form-horizontal well bs-component block-shadow',
						'id' => $this->getEditFormId () 
				) 
		) );
		
		$fields = $this->getPostFields ( $entity, false );
		
		foreach ( $fields as $field_name ) {
			$options = array (
					'attr' => array (
							'class' => 'form-control' 
					) 
			);
			
			$field_options = $this->getFieldChoices ( $entity, $field_name );
			
			if ($field_options) {
				$options ['choices'] = array_flip ( $field_options );
			}
			
			$formBuilder->add ( $field_name, $this->getFieldType ( $entity, $field_name ), $options );
		}
		
		$form = $formBuilder->getForm ();
		
		return $form;
	}
	protected function trans($string, $parameters = [], $domain = null, $locale = null) {
		$translator = $this->get ( 'translator' );
		
		return $translator->trans ( $string, $parameters, $domain, $locale );
	}
	
	/**
	 * Delete the post given by $id
	 *
	 * @param int $id
	 *        	The ID of the post to be deleted
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteEntityAction($entity, $id) {
		$post = $this->getPost ( $entity, $id );
		
		// check if the current user has delete permission
		$this->denyAccessUnlessGranted ( 'delete', $post );
		
		$em = $this->getDoctrine ()->getManager ();
		
		$em->remove ( $post );
		
		$em->flush ();
		
		$this->addFlash ( 'notice', $this->trans ( 'post_deleted' ) );
		
		return $this->redirectToRoute ( 'browse_entity', array (
				'entity' => $entity 
		) );
	}
}