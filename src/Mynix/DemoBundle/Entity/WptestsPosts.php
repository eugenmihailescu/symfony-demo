<?php

namespace Mynix\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\Entity;
use Mynix\DemoBundle\Annotation\LookupAnnotation;
use Mynix\DemoBundle\Annotation\FormFieldTypeAnnotation;
use Mynix\DemoBundle\Annotation\EntityAnnotation;
use Mynix\DemoBundle\Validator\Constraints\ForeignKey;

/**
 * WptestsPosts
 *
 * @ORM\Table(name="wptests_posts", indexes={@ORM\Index(name="post_name", columns={"post_name"}),
 * @ORM\Index(name="type_status_date", columns={"post_type", "post_status", "post_date", "ID"}),
 * @ORM\Index(name="post_parent", columns={"post_parent"}), @ORM\Index(name="post_author", columns={"post_author"})})
 * @ORM\Entity
 * @EntityAnnotation(pk="id",columns={"postTitle"},alias="articole")
 */
class WptestsPosts {
	/**
	 * @ORM\ManyToOne(targetEntity="WptestsUsers",inversedBy="posts")
	 * @ORM\JoinColumn(name="post_author", referencedColumnName="ID")
	 */
	private $author;
	
	/**
	 * @ORM\Column(name="post_author", type="bigint", nullable=false)
	 * @LookupAnnotation(target_entity="Mynix\DemoBundle\Entity\WptestsUsers", lookup_function="getUserNicename")
	 *
	 * @var integer
	 */
	private $postAuthor = '0';
	
	/**
	 *
	 * @ORM\Column(name="post_date", type="datetime", nullable=false)
	 * @Assert\NotBlank()
	 *
	 * @var \DateTime
	 */
	private $postDate = new \DateTime();
	
	/**
	 *
	 * @ORM\Column(name="post_date_gmt", type="datetime", nullable=false)
	 * @Assert\NotBlank()
	 *
	 * @var \DateTime
	 */
	private $postDateGmt = new \DateTime();
	
	/**
	 *
	 * @ORM\Column(name="post_content", type="text", nullable=false)
	 * @Assert\NotBlank()
	 *
	 * @var string
	 */
	private $postContent;
	
	/**
	 *
	 * @ORM\Column(name="post_title", type="text", length=65535, nullable=false)
	 * @Assert\NotBlank()
	 *
	 * @var string
	 */
	private $postTitle;
	
	/**
	 *
	 * @ORM\Column(name="post_excerpt", type="text", length=65535, nullable=false)
	 *
	 * @var string
	 */
	private $postExcerpt;
	
	/**
	 *
	 * @ORM\Column(name="post_status", type="string", length=20, nullable=false)
	 * @Assert\Choice(choices = {"publish", "future", "draft", "pending", "private", "trash", "auto-draft", "inherit"}, message = "wptestsposts.poststatus.choice")
	 *
	 * @var string
	 */
	private $postStatus = 'publish';
	
	/**
	 *
	 * @ORM\Column(name="comment_status", type="string", length=20, nullable=false)
	 * @Assert\Choice(choices={"open","close"})
	 *
	 * @var string
	 */
	private $commentStatus = 'open';
	
	/**
	 *
	 * @ORM\Column(name="ping_status", type="string", length=20, nullable=false)
	 * @Assert\Choice(choices={"open","close"})
	 *
	 * @var string
	 */
	private $pingStatus = 'open';
	
	/**
	 *
	 * @ORM\Column(name="post_password", type="string", length=20, nullable=false)
	 * @FormFieldTypeAnnotation(field_type="Password")
	 *
	 * @var string
	 */
	private $postPassword = '';
	
	/**
	 *
	 * @ORM\Column(name="post_name", type="string", length=200, nullable=false)
	 * @Assert\NotBlank()
	 *
	 * @var string
	 */
	private $postName = '';
	
	/**
	 *
	 * @ORM\Column(name="to_ping", type="text", length=65535, nullable=false)
	 *
	 * @var string
	 */
	private $toPing;
	
	/**
	 *
	 * @ORM\Column(name="pinged", type="text", length=65535, nullable=false)
	 *
	 * @var string
	 */
	private $pinged;
	
	/**
	 *
	 * @ORM\Column(name="post_modified", type="datetime", nullable=false)
	 * @Assert\NotBlank()
	 *
	 * @var \DateTime
	 */
	private $postModified = new \DateTime();
	
	/**
	 *
	 * @ORM\Column(name="post_modified_gmt", type="datetime", nullable=false)
	 * @Assert\NotBlank()
	 *
	 * @var \DateTime
	 */
	private $postModifiedGmt = new \DateTime();
	
	/**
	 *
	 * @ORM\Column(name="post_content_filtered", type="text", nullable=false)
	 *
	 * @var string
	 */
	private $postContentFiltered;
	
	/**
	 *
	 * @ORM\Column(name="post_parent", type="bigint", nullable=false)
	 * @ForeignKey(name="postParent",message="wptestsposts.postparent.foreignkey")
	 *
	 * @var integer
	 */
	private $postParent = '0';
	
	/**
	 *
	 * @ORM\Column(name="guid", type="string", length=255, nullable=false)
	 *
	 * @var string
	 */
	private $guid = '';
	
	/**
	 *
	 * @ORM\Column(name="menu_order", type="integer", nullable=false)
	 *
	 * @var integer
	 */
	private $menuOrder = '0';
	
	/**
	 *
	 * @ORM\Column(name="post_type", type="string", length=20, nullable=false)
	 * @Assert\Choice({"post","page","attachment","revision","nav_menu_item"},message="wptestsposts.postType.choice")
	 *
	 * @var string
	 */
	private $postType = 'post';
	
	/**
	 *
	 * @ORM\Column(name="post_mime_type", type="string", length=100, nullable=false)
	 * @Assert\NotBlank()
	 *
	 * @var string
	 */
	private $postMimeType = '';
	
	/**
	 *
	 * @ORM\Column(name="comment_count", type="bigint", nullable=false)
	 *
	 * @var integer
	 */
	private $commentCount = '0';
	
	/**
	 *
	 * @ORM\Column(name="ID", type="bigint")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 * @var integer
	 */
	private $id;
	public function __construct() {
		$this->postDate = new \DateTime ();
		
		$this->postDateGmt = new \DateTime ();
		
		$this->postModified = new \DateTime ();
		
		$this->postModifiedGmt = new \DateTime ();
	}
	
	/**
	 * Set postAuthor
	 *
	 * @param integer $postAuthor        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostAuthor($postAuthor) {
		$this->postAuthor = $postAuthor;
		
		return $this;
	}
	
	/**
	 * Get postAuthor
	 *
	 * @return integer
	 */
	public function getPostAuthor() {
		return $this->postAuthor;
	}
	
	/**
	 * Set postDate
	 *
	 * @param \DateTime $postDate        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostDate($postDate) {
		$this->postDate = $postDate;
		
		return $this;
	}
	
	/**
	 * Get postDate
	 *
	 * @return \DateTime
	 */
	public function getPostDate() {
		return $this->postDate;
	}
	
	/**
	 * Set postDateGmt
	 *
	 * @param \DateTime $postDateGmt        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostDateGmt($postDateGmt) {
		$this->postDateGmt = $postDateGmt;
		
		return $this;
	}
	
	/**
	 * Get postDateGmt
	 *
	 * @return \DateTime
	 */
	public function getPostDateGmt() {
		return $this->postDateGmt;
	}
	
	/**
	 * Set postContent
	 *
	 * @param string $postContent        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostContent($postContent) {
		$this->postContent = $postContent;
		
		return $this;
	}
	
	/**
	 * Get postContent
	 *
	 * @return string
	 */
	public function getPostContent() {
		return $this->postContent;
	}
	
	/**
	 * Set postTitle
	 *
	 * @param string $postTitle        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostTitle($postTitle) {
		$this->postTitle = $postTitle;
		
		return $this;
	}
	
	/**
	 * Get postTitle
	 *
	 * @return string
	 */
	public function getPostTitle() {
		return $this->postTitle;
	}
	
	/**
	 * Set postExcerpt
	 *
	 * @param string $postExcerpt        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostExcerpt($postExcerpt) {
		$this->postExcerpt = $postExcerpt;
		
		return $this;
	}
	
	/**
	 * Get postExcerpt
	 *
	 * @return string
	 */
	public function getPostExcerpt() {
		return $this->postExcerpt;
	}
	
	/**
	 * Set postStatus
	 *
	 * @param string $postStatus        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostStatus($postStatus) {
		$this->postStatus = $postStatus;
		
		return $this;
	}
	
	/**
	 * Get postStatus
	 *
	 * @return string
	 */
	public function getPostStatus() {
		return $this->postStatus;
	}
	
	/**
	 * Set commentStatus
	 *
	 * @param string $commentStatus        	
	 *
	 * @return WptestsPosts
	 */
	public function setCommentStatus($commentStatus) {
		$this->commentStatus = $commentStatus;
		
		return $this;
	}
	
	/**
	 * Get commentStatus
	 *
	 * @return string
	 */
	public function getCommentStatus() {
		return $this->commentStatus;
	}
	
	/**
	 * Set pingStatus
	 *
	 * @param string $pingStatus        	
	 *
	 * @return WptestsPosts
	 */
	public function setPingStatus($pingStatus) {
		$this->pingStatus = $pingStatus;
		
		return $this;
	}
	
	/**
	 * Get pingStatus
	 *
	 * @return string
	 */
	public function getPingStatus() {
		return $this->pingStatus;
	}
	
	/**
	 * Set postPassword
	 *
	 * @param string $postPassword        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostPassword($postPassword) {
		$this->postPassword = $postPassword;
		
		return $this;
	}
	
	/**
	 * Get postPassword
	 *
	 * @return string
	 */
	public function getPostPassword() {
		return $this->postPassword;
	}
	
	/**
	 * Set postName
	 *
	 * @param string $postName        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostName($postName) {
		$this->postName = $postName;
		
		return $this;
	}
	
	/**
	 * Get postName
	 *
	 * @return string
	 */
	public function getPostName() {
		return $this->postName;
	}
	
	/**
	 * Set toPing
	 *
	 * @param string $toPing        	
	 *
	 * @return WptestsPosts
	 */
	public function setToPing($toPing) {
		$this->toPing = $toPing;
		
		return $this;
	}
	
	/**
	 * Get toPing
	 *
	 * @return string
	 */
	public function getToPing() {
		return $this->toPing;
	}
	
	/**
	 * Set pinged
	 *
	 * @param string $pinged        	
	 *
	 * @return WptestsPosts
	 */
	public function setPinged($pinged) {
		$this->pinged = $pinged;
		
		return $this;
	}
	
	/**
	 * Get pinged
	 *
	 * @return string
	 */
	public function getPinged() {
		return $this->pinged;
	}
	
	/**
	 * Set postModified
	 *
	 * @param \DateTime $postModified        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostModified($postModified) {
		$this->postModified = $postModified;
		
		return $this;
	}
	
	/**
	 * Get postModified
	 *
	 * @return \DateTime
	 */
	public function getPostModified() {
		return $this->postModified;
	}
	
	/**
	 * Set postModifiedGmt
	 *
	 * @param \DateTime $postModifiedGmt        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostModifiedGmt($postModifiedGmt) {
		$this->postModifiedGmt = $postModifiedGmt;
		
		return $this;
	}
	
	/**
	 * Get postModifiedGmt
	 *
	 * @return \DateTime
	 */
	public function getPostModifiedGmt() {
		return $this->postModifiedGmt;
	}
	
	/**
	 * Set postContentFiltered
	 *
	 * @param string $postContentFiltered        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostContentFiltered($postContentFiltered) {
		$this->postContentFiltered = $postContentFiltered;
		
		return $this;
	}
	
	/**
	 * Get postContentFiltered
	 *
	 * @return string
	 */
	public function getPostContentFiltered() {
		return $this->postContentFiltered;
	}
	
	/**
	 * Set postParent
	 *
	 * @param integer $postParent        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostParent($postParent) {
		$this->postParent = $postParent;
		
		return $this;
	}
	
	/**
	 * Get postParent
	 *
	 * @return integer
	 */
	public function getPostParent() {
		return $this->postParent;
	}
	
	/**
	 * Set guid
	 *
	 * @param string $guid        	
	 *
	 * @return WptestsPosts
	 */
	public function setGuid($guid) {
		$this->guid = $guid;
		
		return $this;
	}
	
	/**
	 * Get guid
	 *
	 * @return string
	 */
	public function getGuid() {
		return $this->guid;
	}
	
	/**
	 * Set menuOrder
	 *
	 * @param integer $menuOrder        	
	 *
	 * @return WptestsPosts
	 */
	public function setMenuOrder($menuOrder) {
		$this->menuOrder = $menuOrder;
		
		return $this;
	}
	
	/**
	 * Get menuOrder
	 *
	 * @return integer
	 */
	public function getMenuOrder() {
		return $this->menuOrder;
	}
	
	/**
	 * Set postType
	 *
	 * @param string $postType        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostType($postType) {
		$this->postType = $postType;
		
		return $this;
	}
	
	/**
	 * Get postType
	 *
	 * @return string
	 */
	public function getPostType() {
		return $this->postType;
	}
	
	/**
	 * Set postMimeType
	 *
	 * @param string $postMimeType        	
	 *
	 * @return WptestsPosts
	 */
	public function setPostMimeType($postMimeType) {
		$this->postMimeType = $postMimeType;
		
		return $this;
	}
	
	/**
	 * Get postMimeType
	 *
	 * @return string
	 */
	public function getPostMimeType() {
		return $this->postMimeType;
	}
	
	/**
	 * Set commentCount
	 *
	 * @param integer $commentCount        	
	 *
	 * @return WptestsPosts
	 */
	public function setCommentCount($commentCount) {
		$this->commentCount = $commentCount;
		
		return $this;
	}
	
	/**
	 * Get commentCount
	 *
	 * @return integer
	 */
	public function getCommentCount() {
		return $this->commentCount;
	}
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	public function isPrivate() {
		return ! empty ( $this->postPassword );
	}
	
	/**
	 * Set author
	 *
	 * @param \Mynix\DemoBundle\Entity\WptestsUsers $author        	
	 *
	 * @return WptestsPosts
	 */
	public function setAuthor(\Mynix\DemoBundle\Entity\WptestsUsers $author = null) {
		$this->author = $author;
		
		return $this;
	}
	
	/**
	 * Get author
	 *
	 * @return \Mynix\DemoBundle\Entity\WptestsUsers
	 */
	public function getAuthor() {
		return $this->author;
	}
}
