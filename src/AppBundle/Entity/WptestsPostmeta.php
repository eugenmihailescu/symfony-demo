<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Annotation\EntityAnnotation;

/**
 * WptestsPostmeta
 *
 * @ORM\Table(name="wptests_postmeta", indexes={@ORM\Index(name="post_id", columns={"post_id"}),
 * @ORM\Index(name="meta_key", columns={"meta_key"})})
 * @ORM\Entity
 * @EntityAnnotation(pk="metaId", columns={"metaKey","metaValue","postId"}, alias="Metadata articole")
 */
class WptestsPostmeta {
	/**
	 *
	 * @var integer @ORM\Column(name="post_id", type="bigint", nullable=false)
	 */
	private $postId = '0';
	
	/**
	 *
	 * @var string @ORM\Column(name="meta_key", type="string", length=255, nullable=true)
	 */
	private $metaKey;
	
	/**
	 *
	 * @var string @ORM\Column(name="meta_value", type="text", nullable=true)
	 */
	private $metaValue;
	
	/**
	 *
	 * @var integer @ORM\Column(name="meta_id", type="bigint")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $metaId;
	
	/**
	 * Set postId
	 *
	 * @param integer $postId        	
	 *
	 * @return WptestsPostmeta
	 */
	public function setPostId($postId) {
		$this->postId = $postId;
		
		return $this;
	}
	
	/**
	 * Get postId
	 *
	 * @return integer
	 */
	public function getPostId() {
		return $this->postId;
	}
	
	/**
	 * Set metaKey
	 *
	 * @param string $metaKey        	
	 *
	 * @return WptestsPostmeta
	 */
	public function setMetaKey($metaKey) {
		$this->metaKey = $metaKey;
		
		return $this;
	}
	
	/**
	 * Get metaKey
	 *
	 * @return string
	 */
	public function getMetaKey() {
		return $this->metaKey;
	}
	
	/**
	 * Set metaValue
	 *
	 * @param string $metaValue        	
	 *
	 * @return WptestsPostmeta
	 */
	public function setMetaValue($metaValue) {
		$this->metaValue = $metaValue;
		
		return $this;
	}
	
	/**
	 * Get metaValue
	 *
	 * @return string
	 */
	public function getMetaValue() {
		return $this->metaValue;
	}
	
	/**
	 * Get metaId
	 *
	 * @return integer
	 */
	public function getMetaId() {
		return $this->metaId;
	}
}
