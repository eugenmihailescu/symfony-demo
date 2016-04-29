<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Annotation\EntityAnnotation;

/**
 * WptestsComments
 *
 * @ORM\Table(name="wptests_comments", indexes={@ORM\Index(name="comment_post_ID", columns={"comment_post_ID"}), @ORM\Index(name="comment_approved_date_gmt", columns={"comment_approved", "comment_date_gmt"}), @ORM\Index(name="comment_date_gmt", columns={"comment_date_gmt"}), @ORM\Index(name="comment_parent", columns={"comment_parent"}), @ORM\Index(name="comment_author_email", columns={"comment_author_email"})})
 * @ORM\Entity
 * @EntityAnnotation(pk="commentId",columns={"commentPostId","commentAuthor"},alias="comentarii")
 */
class WptestsComments
{
    /**
     * @var integer
     *
     * @ORM\Column(name="comment_post_ID", type="bigint", nullable=false)
     */
    private $commentPostId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="comment_author", type="text", length=255, nullable=false)
     */
    private $commentAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_author_email", type="string", length=100, nullable=false)
     */
    private $commentAuthorEmail = '';

    /**
     * @var string
     *
     * @ORM\Column(name="comment_author_url", type="string", length=200, nullable=false)
     */
    private $commentAuthorUrl = '';

    /**
     * @var string
     *
     * @ORM\Column(name="comment_author_IP", type="string", length=100, nullable=false)
     */
    private $commentAuthorIp = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="comment_date", type="datetime", nullable=false)
     */
    private $commentDate = '0000-00-00 00:00:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="comment_date_gmt", type="datetime", nullable=false)
     */
    private $commentDateGmt = '0000-00-00 00:00:00';

    /**
     * @var string
     *
     * @ORM\Column(name="comment_content", type="text", length=65535, nullable=false)
     */
    private $commentContent;

    /**
     * @var integer
     *
     * @ORM\Column(name="comment_karma", type="integer", nullable=false)
     */
    private $commentKarma = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="comment_approved", type="string", length=20, nullable=false)
     */
    private $commentApproved = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="comment_agent", type="string", length=255, nullable=false)
     */
    private $commentAgent = '';

    /**
     * @var string
     *
     * @ORM\Column(name="comment_type", type="string", length=20, nullable=false)
     */
    private $commentType = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="comment_parent", type="bigint", nullable=false)
     */
    private $commentParent = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="bigint", nullable=false)
     */
    private $userId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="comment_ID", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $commentId;



    /**
     * Set commentPostId
     *
     * @param integer $commentPostId
     *
     * @return WptestsComments
     */
    public function setCommentPostId($commentPostId)
    {
        $this->commentPostId = $commentPostId;

        return $this;
    }

    /**
     * Get commentPostId
     *
     * @return integer
     */
    public function getCommentPostId()
    {
        return $this->commentPostId;
    }

    /**
     * Set commentAuthor
     *
     * @param string $commentAuthor
     *
     * @return WptestsComments
     */
    public function setCommentAuthor($commentAuthor)
    {
        $this->commentAuthor = $commentAuthor;

        return $this;
    }

    /**
     * Get commentAuthor
     *
     * @return string
     */
    public function getCommentAuthor()
    {
        return $this->commentAuthor;
    }

    /**
     * Set commentAuthorEmail
     *
     * @param string $commentAuthorEmail
     *
     * @return WptestsComments
     */
    public function setCommentAuthorEmail($commentAuthorEmail)
    {
        $this->commentAuthorEmail = $commentAuthorEmail;

        return $this;
    }

    /**
     * Get commentAuthorEmail
     *
     * @return string
     */
    public function getCommentAuthorEmail()
    {
        return $this->commentAuthorEmail;
    }

    /**
     * Set commentAuthorUrl
     *
     * @param string $commentAuthorUrl
     *
     * @return WptestsComments
     */
    public function setCommentAuthorUrl($commentAuthorUrl)
    {
        $this->commentAuthorUrl = $commentAuthorUrl;

        return $this;
    }

    /**
     * Get commentAuthorUrl
     *
     * @return string
     */
    public function getCommentAuthorUrl()
    {
        return $this->commentAuthorUrl;
    }

    /**
     * Set commentAuthorIp
     *
     * @param string $commentAuthorIp
     *
     * @return WptestsComments
     */
    public function setCommentAuthorIp($commentAuthorIp)
    {
        $this->commentAuthorIp = $commentAuthorIp;

        return $this;
    }

    /**
     * Get commentAuthorIp
     *
     * @return string
     */
    public function getCommentAuthorIp()
    {
        return $this->commentAuthorIp;
    }

    /**
     * Set commentDate
     *
     * @param \DateTime $commentDate
     *
     * @return WptestsComments
     */
    public function setCommentDate($commentDate)
    {
        $this->commentDate = $commentDate;

        return $this;
    }

    /**
     * Get commentDate
     *
     * @return \DateTime
     */
    public function getCommentDate()
    {
        return $this->commentDate;
    }

    /**
     * Set commentDateGmt
     *
     * @param \DateTime $commentDateGmt
     *
     * @return WptestsComments
     */
    public function setCommentDateGmt($commentDateGmt)
    {
        $this->commentDateGmt = $commentDateGmt;

        return $this;
    }

    /**
     * Get commentDateGmt
     *
     * @return \DateTime
     */
    public function getCommentDateGmt()
    {
        return $this->commentDateGmt;
    }

    /**
     * Set commentContent
     *
     * @param string $commentContent
     *
     * @return WptestsComments
     */
    public function setCommentContent($commentContent)
    {
        $this->commentContent = $commentContent;

        return $this;
    }

    /**
     * Get commentContent
     *
     * @return string
     */
    public function getCommentContent()
    {
        return $this->commentContent;
    }

    /**
     * Set commentKarma
     *
     * @param integer $commentKarma
     *
     * @return WptestsComments
     */
    public function setCommentKarma($commentKarma)
    {
        $this->commentKarma = $commentKarma;

        return $this;
    }

    /**
     * Get commentKarma
     *
     * @return integer
     */
    public function getCommentKarma()
    {
        return $this->commentKarma;
    }

    /**
     * Set commentApproved
     *
     * @param string $commentApproved
     *
     * @return WptestsComments
     */
    public function setCommentApproved($commentApproved)
    {
        $this->commentApproved = $commentApproved;

        return $this;
    }

    /**
     * Get commentApproved
     *
     * @return string
     */
    public function getCommentApproved()
    {
        return $this->commentApproved;
    }

    /**
     * Set commentAgent
     *
     * @param string $commentAgent
     *
     * @return WptestsComments
     */
    public function setCommentAgent($commentAgent)
    {
        $this->commentAgent = $commentAgent;

        return $this;
    }

    /**
     * Get commentAgent
     *
     * @return string
     */
    public function getCommentAgent()
    {
        return $this->commentAgent;
    }

    /**
     * Set commentType
     *
     * @param string $commentType
     *
     * @return WptestsComments
     */
    public function setCommentType($commentType)
    {
        $this->commentType = $commentType;

        return $this;
    }

    /**
     * Get commentType
     *
     * @return string
     */
    public function getCommentType()
    {
        return $this->commentType;
    }

    /**
     * Set commentParent
     *
     * @param integer $commentParent
     *
     * @return WptestsComments
     */
    public function setCommentParent($commentParent)
    {
        $this->commentParent = $commentParent;

        return $this;
    }

    /**
     * Get commentParent
     *
     * @return integer
     */
    public function getCommentParent()
    {
        return $this->commentParent;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return WptestsComments
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get commentId
     *
     * @return integer
     */
    public function getCommentId()
    {
        return $this->commentId;
    }
}
