<?php

namespace TyHand\BackgroundProcessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JobStatusEntity
 */
class JobStatusEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \TyHand\BackgroundProcessBundle\Entity\JobEntity
     */
    private $job;

    /**
     * @var \TyHand\BackgroundProcessBundle\Entity\StatusEntity
     */
    private $status;


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
     * Set date
     *
     * @param \DateTime $date
     * @return JobStatusEntity
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set job
     *
     * @param \TyHand\BackgroundProcessBundle\Entity\JobEntity $job
     * @return JobStatusEntity
     */
    public function setJob(\TyHand\BackgroundProcessBundle\Entity\JobEntity $job = null)
    {
        $this->job = $job;
    
        return $this;
    }

    /**
     * Get job
     *
     * @return \TyHand\BackgroundProcessBundle\Entity\JobEntity 
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set status
     *
     * @param \TyHand\BackgroundProcessBundle\Entity\StatusEntity $status
     * @return JobStatusEntity
     */
    public function setStatus(\TyHand\BackgroundProcessBundle\Entity\StatusEntity $status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return \TyHand\BackgroundProcessBundle\Entity\StatusEntity 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @var string
     */
    private $message;


    /**
     * Set message
     *
     * @param string $message
     * @return JobStatusEntity
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }
}