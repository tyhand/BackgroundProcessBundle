<?php

namespace TyHand\BackgroundProcessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JobEntity
 */
class JobEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $statuses;

    /**
     * @var \TyHand\BackgroundProcessBundle\Entity\JobStatusEntity
     */
    private $currentStatus;

    /**
     * @var string
     */
    private $jobClass;

    /**
     * @var string
     */
    private $jobClassRecordId;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var int
     */
    private $pid;

    /**
     * @var string
     */
    private $createdWho;

    /**
     * @var \DateTime
     */
    private $createdWhen;

    /**
     * @var string
     */
    private $updatedWho;

    /**
     * @var \DateTime
     */
    private $updatedWhen;

    /**
     * @var string
     */
    private $outputFile;

    /**
     * @var integer
     */
    private $unitsToProcess;

    /**
     * @var integer
     */
    private $unitsProcessed;


   /**
     * Constructor
     */
    public function __construct()
    {
        $this->statuses = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set jobClass
     *
     * @param string $jobClass
     * @return JobEntity
     */
    public function setJobClass($jobClass)
    {
        $this->jobClass = $jobClass;

        return $this;
    }

    /**
     * Get jobClass
     *
     * @return string
     */
    public function getJobClass()
    {
        return $this->jobClass;
    }

    /**
     * Set jobClassRecordId
     *
     * @param integer $jobClassRecordId
     * @return JobEntity
     */
    public function setJobClassRecordId($jobClassRecordId)
    {
        $this->jobClassRecordId = $jobClassRecordId;

        return $this;
    }

    /**
     * Get jobClassRecordId
     *
     * @return integer
     */
    public function getJobClassRecordId()
    {
        return $this->jobClassRecordId;
    }

    /**
     * Set parameters
     *
     * @param array $parameters
     * @return JobEntity
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Add status
     *
     * @param \TyHand\BackgroundProcessBundle\Entity\JobStatusEntity $statuses
     * @return JobEntity
     */
    public function addStatus(\TyHand\BackgroundProcessBundle\Entity\JobStatusEntity $status)
    {
        $this->statuses[] = $status;

        return $this;
    }

    /**
     * Remove status
     *
     * @param \TyHand\BackgroundProcessBundle\Entity\JobStatusEntity $statuses
     */
    public function removeStatus(\TyHand\BackgroundProcessBundle\Entity\JobStatusEntity $status)
    {
        $this->statuses->removeElement($status);
    }

    /**
     * Get statuses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatuses()
    {
        return $this->statuses;
    }


    /**
     * Set currentStatus
     *
     * @param \TyHand\BackgroundProcessBundle\Entity\JobStatusEntity $currentStatus
     * @return JobEntity
     */
    public function setCurrentStatus(\TyHand\BackgroundProcessBundle\Entity\JobStatusEntity $currentStatus)
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    /**
     * Get currentStatus
     *
     * @return \TyHand\BackgroundProcessBundle\Entity\JobStatusEntity
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * Set pid
     *
     * @param $pid
     * @return JobEntity
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid
     *
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set createdWho
     *
     * @param string $createdWho
     * @return JobEntity
     */
    public function setCreatedWho($createdWho)
    {
        $this->createdWho = $createdWho;

        return $this;
    }

    /**
     * Get createdWho
     *
     * @return string
     */
    public function getCreatedWho()
    {
        return $this->createdWho;
    }

    /**
     * Set createdWhen
     *
     * @param \DateTime $createdWhen
     * @return JobEntity
     */
    public function setCreatedWhen($createdWhen)
    {
        $this->createdWhen = $createdWhen;

        return $this;
    }

    /**
     * Get createdWhen
     *
     * @return \DateTime
     */
    public function getCreatedWhen()
    {
        return $this->createdWhen;
    }

    /**
     * Set updatedWho
     *
     * @param string $updatedWho
     * @return JobEntity
     */
    public function setUpdatedWho($updatedWho)
    {
        $this->updatedWho = $updatedWho;

        return $this;
    }

    /**
     * Get updatedWho
     *
     * @return string
     */
    public function getUpdatedWho()
    {
        return $this->updatedWho;
    }

    /**
     * Set updatedWhen
     *
     * @param \DateTime $updatedWhen
     * @return JobEntity
     */
    public function setUpdatedWhen($updatedWhen)
    {
        $this->updatedWhen = $updatedWhen;

        return $this;
    }

    /**
     * Get updatedWhen
     *
     * @return \DateTime
     */
    public function getUpdatedWhen()
    {
        return $this->updatedWhen;
    }

    /**
     * Set outputFile
     *
     * @param string $outputFile
     * @return JobEntity
     */
    public function setOutputFile($outputFile)
    {
        $this->outputFile = $outputFile;

        return $this;
    }

    /**
     * Get outputFile
     *
     * @return string
     */
    public function getOutputFile()
    {
        return $this->outputFile;
    }

    /**
     * Set unitsToProcess
     *
     * @param $unitsToProcess
     * @return JobEntity
     */
    public function setUnitsToProcess($unitsToProcess)
    {
        $this->unitsToProcess = $unitsToProcess;

        return $this;
    }

    /**
     * Get unitsToProcess
     *
     * @return int
     */
    public function getUnitsToProcess()
    {
        return $this->unitsToProcess;
    }

    /**
     * Set unitsProcessed
     *
     * @param $unitsProcessed
     * @return JobEntity
     */
    public function setUnitsProcessed($unitsProcessed)
    {
        $this->unitsProcessed = $unitsProcessed;

        return $this;
    }

    /**
     * Get unitsProcessed
     *
     * @return int
     */
    public function getUnitsProcessed()
    {
        return $this->unitsProcessed;
    }


    ///////////////////////////
    // CUSTOM EXTRA METHODS  //
    ///////////////////////////

    /**
     * Add a new status to the statuses and set it as being the current
     *
     * @param  TyHandBackgroundProcessBundleEntityJobStatusEntity $status The status to add and set as current
     *
     * @return self
     */
    public function addAndUpdateStatus(\TyHand\BackgroundProcessBundle\Entity\JobStatusEntity $status) {
        $this->statuses[] = $status;
        $this->currentStatus = $status;

        return $this;
    }

}