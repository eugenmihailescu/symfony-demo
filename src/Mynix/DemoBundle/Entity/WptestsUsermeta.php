<?php

namespace Mynix\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WptestsUsermeta
 *
 * @ORM\Table(name="wptests_usermeta", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="usermeta_meta_key", columns={"meta_key"})})
 * @ORM\Entity
 */
class WptestsUsermeta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="bigint", nullable=false)
     */
    private $userId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="meta_key", type="string", length=255, nullable=true)
     */
    private $metaKey;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_value", type="text", nullable=true)
     */
    private $metaValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="umeta_id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $umetaId;



    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return WptestsUsermeta
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
     * Set metaKey
     *
     * @param string $metaKey
     *
     * @return WptestsUsermeta
     */
    public function setMetaKey($metaKey)
    {
        $this->metaKey = $metaKey;

        return $this;
    }

    /**
     * Get metaKey
     *
     * @return string
     */
    public function getMetaKey()
    {
        return $this->metaKey;
    }

    /**
     * Set metaValue
     *
     * @param string $metaValue
     *
     * @return WptestsUsermeta
     */
    public function setMetaValue($metaValue)
    {
        $this->metaValue = $metaValue;

        return $this;
    }

    /**
     * Get metaValue
     *
     * @return string
     */
    public function getMetaValue()
    {
        return $this->metaValue;
    }

    /**
     * Get umetaId
     *
     * @return integer
     */
    public function getUmetaId()
    {
        return $this->umetaId;
    }
}
