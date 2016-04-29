<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Annotation\EntityAnnotation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WptestsOptions
 *
 * @ORM\Table(name="wptests_options", uniqueConstraints={@ORM\UniqueConstraint(name="option_name", columns={"option_name"})})
 * @ORM\Entity
 * @EntityAnnotation(pk="optionId",columns={"optionName"},alias="optiuni")
 */
class WptestsOptions
{
    /**
     * @var string
     *
     * @ORM\Column(name="option_name", type="string", length=64, nullable=false)
     */
    private $optionName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="option_value", type="text", nullable=false)
     */
    private $optionValue;

    /**
     * @var string
     *
     * @ORM\Column(name="autoload", type="string", length=20, nullable=false)
     * @Assert\Choice(choices={"yes","no"})
     */
    private $autoload = 'yes';

    /**
     * @var integer
     *
     * @ORM\Column(name="option_id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $optionId;



    /**
     * Set optionName
     *
     * @param string $optionName
     *
     * @return WptestsOptions
     */
    public function setOptionName($optionName)
    {
        $this->optionName = $optionName;

        return $this;
    }

    /**
     * Get optionName
     *
     * @return string
     */
    public function getOptionName()
    {
        return $this->optionName;
    }

    /**
     * Set optionValue
     *
     * @param string $optionValue
     *
     * @return WptestsOptions
     */
    public function setOptionValue($optionValue)
    {
        $this->optionValue = $optionValue;

        return $this;
    }

    /**
     * Get optionValue
     *
     * @return string
     */
    public function getOptionValue()
    {
        return $this->optionValue;
    }

    /**
     * Set autoload
     *
     * @param string $autoload
     *
     * @return WptestsOptions
     */
    public function setAutoload($autoload)
    {
        $this->autoload = $autoload;

        return $this;
    }

    /**
     * Get autoload
     *
     * @return string
     */
    public function getAutoload()
    {
        return $this->autoload;
    }

    /**
     * Get optionId
     *
     * @return integer
     */
    public function getOptionId()
    {
        return $this->optionId;
    }
}
