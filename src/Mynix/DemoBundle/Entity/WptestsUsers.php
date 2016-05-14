<?php

namespace Mynix\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Mynix\DemoBundle\Annotation\EntityAnnotation;
use Mynix\DemoBundle\Annotation\FormFieldTypeAnnotation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WptestsUsers
 *
 * @ORM\Table(name="wptests_users", indexes={@ORM\Index(name="user_login_key", columns={"user_login"}), @ORM\Index(name="user_nicename", columns={"user_nicename"})})
 * @ORM\Entity
 * @EntityAnnotation(pk="id",columns={"userLogin","displayName"},alias="utilizatori")
 */
class WptestsUsers {
	
	/**
	 * @ORM\OneToMany(targetEntity="WptestsPosts",mappedBy="author")
	 */
	private $posts;
	
	/**
	 *
	 * @ORM\Column(name="user_login", type="string", length=60, nullable=false)
	 *
	 * @var string
	 */
	private $userLogin = '';
	
	/**
	 *
	 * @ORM\Column(name="user_pass", type="string", length=64, nullable=false)
	 *
	 * @var string
	 */
	private $userPass = '';
	
	/**
	 *
	 * @ORM\Column(name="user_nicename", type="string", length=50, nullable=false)
	 *
	 * @var string
	 */
	private $userNicename = '';
	
	/**
	 *
	 * @ORM\Column(name="user_email", type="string", length=100, nullable=false)
	 * @FormFieldTypeAnnotation(field_type="Email")
	 *
	 * @var string
	 */
	private $userEmail = '';
	
	/**
	 *
	 * @ORM\Column(name="user_url", type="string", length=100, nullable=false)
	 * @FormFieldTypeAnnotation(field_type="Url")
	 *
	 * @var string
	 */
	private $userUrl = '';
	
	/**
	 *
	 * @ORM\Column(name="user_registered", type="datetime", nullable=false)
	 *
	 * @var \DateTime
	 */
	private $userRegistered = '0000-00-00 00:00:00';
	
	/**
	 *
	 * @ORM\Column(name="user_activation_key", type="string", length=60, nullable=false)
	 *
	 * @var string
	 */
	private $userActivationKey = '';
	
	/**
	 *
	 * @ORM\Column(name="user_status", type="integer", nullable=false)
	 * @Assert\Choice(choices={0,1});
	 *
	 * @var integer
	 */
	private $userStatus = '0';
	
	/**
	 *
	 * @ORM\Column(name="display_name", type="string", length=250, nullable=false)
	 *
	 * @var string
	 */
	private $displayName = '';
	
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
		$this->posts = new ArrayCollection ();
	}
	
	/**
	 * Set userLogin
	 *
	 * @param string $userLogin        	
	 *
	 * @return WptestsUsers
	 */
	public function setUserLogin($userLogin) {
		$this->userLogin = $userLogin;
		
		return $this;
	}
	
	/**
	 * Get userLogin
	 *
	 * @return string
	 */
	public function getUserLogin() {
		return $this->userLogin;
	}
	
	/**
	 * Set userPass
	 *
	 * @param string $userPass        	
	 *
	 * @return WptestsUsers
	 */
	public function setUserPass($userPass) {
		$this->userPass = $userPass;
		
		return $this;
	}
	
	/**
	 * Get userPass
	 *
	 * @return string
	 */
	public function getUserPass() {
		return $this->userPass;
	}
	
	/**
	 * Set userNicename
	 *
	 * @param string $userNicename        	
	 *
	 * @return WptestsUsers
	 */
	public function setUserNicename($userNicename) {
		$this->userNicename = $userNicename;
		
		return $this;
	}
	
	/**
	 * Get userNicename
	 *
	 * @return string
	 */
	public function getUserNicename() {
		return $this->userNicename;
	}
	
	/**
	 * Set userEmail
	 *
	 * @param string $userEmail        	
	 *
	 * @return WptestsUsers
	 */
	public function setUserEmail($userEmail) {
		$this->userEmail = $userEmail;
		
		return $this;
	}
	
	/**
	 * Get userEmail
	 *
	 * @return string
	 */
	public function getUserEmail() {
		return $this->userEmail;
	}
	
	/**
	 * Set userUrl
	 *
	 * @param string $userUrl        	
	 *
	 * @return WptestsUsers
	 */
	public function setUserUrl($userUrl) {
		$this->userUrl = $userUrl;
		
		return $this;
	}
	
	/**
	 * Get userUrl
	 *
	 * @return string
	 */
	public function getUserUrl() {
		return $this->userUrl;
	}
	
	/**
	 * Set userRegistered
	 *
	 * @param \DateTime $userRegistered        	
	 *
	 * @return WptestsUsers
	 */
	public function setUserRegistered($userRegistered) {
		$this->userRegistered = $userRegistered;
		
		return $this;
	}
	
	/**
	 * Get userRegistered
	 *
	 * @return \DateTime
	 */
	public function getUserRegistered() {
		return $this->userRegistered;
	}
	
	/**
	 * Set userActivationKey
	 *
	 * @param string $userActivationKey        	
	 *
	 * @return WptestsUsers
	 */
	public function setUserActivationKey($userActivationKey) {
		$this->userActivationKey = $userActivationKey;
		
		return $this;
	}
	
	/**
	 * Get userActivationKey
	 *
	 * @return string
	 */
	public function getUserActivationKey() {
		return $this->userActivationKey;
	}
	
	/**
	 * Set userStatus
	 *
	 * @param integer $userStatus        	
	 *
	 * @return WptestsUsers
	 */
	public function setUserStatus($userStatus) {
		$this->userStatus = $userStatus;
		
		return $this;
	}
	
	/**
	 * Get userStatus
	 *
	 * @return integer
	 */
	public function getUserStatus() {
		return $this->userStatus;
	}
	
	/**
	 * Set displayName
	 *
	 * @param string $displayName        	
	 *
	 * @return WptestsUsers
	 */
	public function setDisplayName($displayName) {
		$this->displayName = $displayName;
		
		return $this;
	}
	
	/**
	 * Get displayName
	 *
	 * @return string
	 */
	public function getDisplayName() {
		return $this->displayName;
	}
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Add post
	 *
	 * @param \Mynix\DemoBundle\Entity\WptestsPosts $post        	
	 *
	 * @return WptestsUsers
	 */
	public function addPost(\Mynix\DemoBundle\Entity\WptestsPosts $post) {
		$this->posts [] = $post;
		
		return $this;
	}
	
	/**
	 * Remove post
	 *
	 * @param \Mynix\DemoBundle\Entity\WptestsPosts $post        	
	 */
	public function removePost(\Mynix\DemoBundle\Entity\WptestsPosts $post) {
		$this->posts->removeElement ( $post );
	}
	
	/**
	 * Get posts
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getPosts() {
		return $this->posts;
	}
}
