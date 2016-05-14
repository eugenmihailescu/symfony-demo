<?php

namespace MynixSymfonyDemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MynixSymfonyDemoBundle\Annotation\EntityAnnotation;

/**
 * WptestsTermRelationships
 *
 * @ORM\Table(name="wptests_term_relationships", indexes={@ORM\Index(name="term_taxonomy_id", columns={"term_taxonomy_id"})})
 * @ORM\Entity
 * @EntityAnnotation(pk="objectId",columns={"termOrder","termTaxonomyId"},alias="Relatii termeni articole")
 */
class WptestsTermRelationships
{
    /**
     * @var integer
     *
     * @ORM\Column(name="term_order", type="integer", nullable=false)
     */
    private $termOrder = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="object_id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $objectId;

    /**
     * @var integer
     *
     * @ORM\Column(name="term_taxonomy_id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $termTaxonomyId;



    /**
     * Set termOrder
     *
     * @param integer $termOrder
     *
     * @return WptestsTermRelationships
     */
    public function setTermOrder($termOrder)
    {
        $this->termOrder = $termOrder;

        return $this;
    }

    /**
     * Get termOrder
     *
     * @return integer
     */
    public function getTermOrder()
    {
        return $this->termOrder;
    }

    /**
     * Set objectId
     *
     * @param integer $objectId
     *
     * @return WptestsTermRelationships
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set termTaxonomyId
     *
     * @param integer $termTaxonomyId
     *
     * @return WptestsTermRelationships
     */
    public function setTermTaxonomyId($termTaxonomyId)
    {
        $this->termTaxonomyId = $termTaxonomyId;

        return $this;
    }

    /**
     * Get termTaxonomyId
     *
     * @return integer
     */
    public function getTermTaxonomyId()
    {
        return $this->termTaxonomyId;
    }
}
