<?php

namespace TyHand\BackgroundProcessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatusEntity
 */
class StatusEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $shortName;

    /**
     * @var string
     */
    private $longName;

    /**
     * @var boolean
     */
    private $terminating;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     * @return StatusEntity
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    
        return $this;
    }

    /**
     * Get shortName
     *
     * @return string 
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set longName
     *
     * @param string $longName
     * @return StatusEntity
     */
    public function setLongName($longName)
    {
        $this->longName = $longName;
    
        return $this;
    }

    /**
     * Get longName
     *
     * @return string 
     */
    public function getLongName()
    {
        return $this->longName;
    }

    /**
     * Set terminating
     *
     * @param boolean $terminating
     * @return StatusEntity
     */
    public function setTerminating($terminating)
    {
        $this->terminating = $terminating;
    
        return $this;
    }

    /**
     * Get terminating
     *
     * @return boolean 
     */
    public function getTerminating()
    {
        return $this->terminating;
    }
}