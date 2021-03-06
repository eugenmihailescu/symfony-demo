<?php

namespace Mynix\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mynix\DemoBundle\Annotation\EntityAnnotation;

/**
 * WptestsLinks
 *
 * @ORM\Table(name="wptests_links", indexes={@ORM\Index(name="link_visible", columns={"link_visible"})})
 * @ORM\Entity
 * @EntityAnnotation(pk="linkId",columns={"linkName","linkUrl"},alias="linkuri")
 */
class WptestsLinks {
	/**
	 *
	 * @var string @ORM\Column(name="link_url", type="string", length=255, nullable=false)
	 */
	private $linkUrl = '';
	
	/**
	 *
	 * @var string @ORM\Column(name="link_name", type="string", length=255, nullable=false)
	 */
	private $linkName = '';
	
	/**
	 *
	 * @var string @ORM\Column(name="link_image", type="string", length=255, nullable=false)
	 */
	private $linkImage = '';
	
	/**
	 *
	 * @var string @ORM\Column(name="link_target", type="string", length=25, nullable=false)
	 */
	private $linkTarget = '';
	
	/**
	 *
	 * @var string @ORM\Column(name="link_description", type="string", length=255, nullable=false)
	 */
	private $linkDescription = '';
	
	/**
	 *
	 * @var string @ORM\Column(name="link_visible", type="string", length=20, nullable=false)
	 */
	private $linkVisible = 'Y';
	
	/**
	 *
	 * @var integer @ORM\Column(name="link_owner", type="bigint", nullable=false)
	 */
	private $linkOwner = '1';
	
	/**
	 *
	 * @var integer @ORM\Column(name="link_rating", type="integer", nullable=false)
	 */
	private $linkRating = '0';
	
	/**
	 *
	 * @var \DateTime @ORM\Column(name="link_updated", type="datetime", nullable=false)
	 */
	private $linkUpdated;
	
	/**
	 *
	 * @var string @ORM\Column(name="link_rel", type="string", length=255, nullable=false)
	 */
	private $linkRel = '';
	
	/**
	 *
	 * @var string @ORM\Column(name="link_notes", type="text", length=16777215, nullable=false)
	 */
	private $linkNotes;
	
	/**
	 *
	 * @var string @ORM\Column(name="link_rss", type="string", length=255, nullable=false)
	 */
	private $linkRss = '';
	
	/**
	 *
	 * @var integer @ORM\Column(name="link_id", type="bigint")
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $linkId;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->linkUpdated = new \DateTime ();
	}
	
	/**
	 * Set linkUrl
	 *
	 * @param string $linkUrl        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkUrl($linkUrl) {
		$this->linkUrl = $linkUrl;
		
		return $this;
	}
	
	/**
	 * Get linkUrl
	 *
	 * @return string
	 */
	public function getLinkUrl() {
		return $this->linkUrl;
	}
	
	/**
	 * Set linkName
	 *
	 * @param string $linkName        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkName($linkName) {
		$this->linkName = $linkName;
		
		return $this;
	}
	
	/**
	 * Get linkName
	 *
	 * @return string
	 */
	public function getLinkName() {
		return $this->linkName;
	}
	
	/**
	 * Set linkImage
	 *
	 * @param string $linkImage        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkImage($linkImage) {
		$this->linkImage = $linkImage;
		
		return $this;
	}
	
	/**
	 * Get linkImage
	 *
	 * @return string
	 */
	public function getLinkImage() {
		return $this->linkImage;
	}
	
	/**
	 * Set linkTarget
	 *
	 * @param string $linkTarget        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkTarget($linkTarget) {
		$this->linkTarget = $linkTarget;
		
		return $this;
	}
	
	/**
	 * Get linkTarget
	 *
	 * @return string
	 */
	public function getLinkTarget() {
		return $this->linkTarget;
	}
	
	/**
	 * Set linkDescription
	 *
	 * @param string $linkDescription        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkDescription($linkDescription) {
		$this->linkDescription = $linkDescription;
		
		return $this;
	}
	
	/**
	 * Get linkDescription
	 *
	 * @return string
	 */
	public function getLinkDescription() {
		return $this->linkDescription;
	}
	
	/**
	 * Set linkVisible
	 *
	 * @param string $linkVisible        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkVisible($linkVisible) {
		$this->linkVisible = $linkVisible;
		
		return $this;
	}
	
	/**
	 * Get linkVisible
	 *
	 * @return string
	 */
	public function getLinkVisible() {
		return $this->linkVisible;
	}
	
	/**
	 * Set linkOwner
	 *
	 * @param integer $linkOwner        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkOwner($linkOwner) {
		$this->linkOwner = $linkOwner;
		
		return $this;
	}
	
	/**
	 * Get linkOwner
	 *
	 * @return integer
	 */
	public function getLinkOwner() {
		return $this->linkOwner;
	}
	
	/**
	 * Set linkRating
	 *
	 * @param integer $linkRating        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkRating($linkRating) {
		$this->linkRating = $linkRating;
		
		return $this;
	}
	
	/**
	 * Get linkRating
	 *
	 * @return integer
	 */
	public function getLinkRating() {
		return $this->linkRating;
	}
	
	/**
	 * Set linkUpdated
	 *
	 * @param \DateTime $linkUpdated        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkUpdated($linkUpdated) {
		$this->linkUpdated = $linkUpdated;
		
		return $this;
	}
	
	/**
	 * Get linkUpdated
	 *
	 * @return \DateTime
	 */
	public function getLinkUpdated() {
		return $this->linkUpdated;
	}
	
	/**
	 * Set linkRel
	 *
	 * @param string $linkRel        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkRel($linkRel) {
		$this->linkRel = $linkRel;
		
		return $this;
	}
	
	/**
	 * Get linkRel
	 *
	 * @return string
	 */
	public function getLinkRel() {
		return $this->linkRel;
	}
	
	/**
	 * Set linkNotes
	 *
	 * @param string $linkNotes        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkNotes($linkNotes) {
		$this->linkNotes = $linkNotes;
		
		return $this;
	}
	
	/**
	 * Get linkNotes
	 *
	 * @return string
	 */
	public function getLinkNotes() {
		return $this->linkNotes;
	}
	
	/**
	 * Set linkRss
	 *
	 * @param string $linkRss        	
	 *
	 * @return WptestsLinks
	 */
	public function setLinkRss($linkRss) {
		$this->linkRss = $linkRss;
		
		return $this;
	}
	
	/**
	 * Get linkRss
	 *
	 * @return string
	 */
	public function getLinkRss() {
		return $this->linkRss;
	}
	
	/**
	 * Get linkId
	 *
	 * @return integer
	 */
	public function getLinkId() {
		return $this->linkId;
	}
}
