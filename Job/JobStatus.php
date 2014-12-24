<?php

namespace TyHand\BackgroundProcessBundle\Job;

use TyHand\BackgroundProcessBundle\Job\AbstractJob;

class JobStatus
{
    ////////////////
    // VARIABLES  //
    ////////////////

    /**
     * The current status of the job
     *
     * @var string
     */
    protected $status;

    /**
     * Whether the job is still running/queued or if completed/failed
     * @var boolean
     */
    protected $terminated;

    /**
     * The number of units to process (if supported)
     * @var int|null The number of units to process (or null if no supported by the job return this status)
     */
    protected $toProcess;

    /**
     * The number of units processed (if supported)
     * @var int|null The number of units processed (or null if not supported by the job returning this status)
     */
    protected $processed;

    /**
     * Any message added to the job status
     * @var string|null Message for the job status
     */
    protected $message;

    /**
     * The time the job object was created
     * @var \DateTime
     */
    protected $created;

    /**
     * The job the status is for
     * @var AbstractJob
     */
    protected $job;

    //////////////////
    // BASE METHODS //
    //////////////////


    /**
     * Constructor
     *
     * @param string $status The status string
     */
    public function __construct($status, $terminated, AbstractJob $job) {
        //Set the status message
        $this->status = $status;
        $this->terminated = $terminated;
        $this->job = $job;

        //Set the created var
        $this->created = $this->job->getJobEntity()->getCreatedWhen();

        //Set the progress varaibles from job entity record
        $this->toProcess = $this->job->getJobEntity()->getUnitsToProcess();
        $this->processed = $this->job->getJobEntity()->getUnitsProcessed();
    }


    //////////////
    // METHODS  //
    //////////////


    /**
     * Gets the progress if supported
     *
     * @return float|null The percentage of units processed or null if the job does not support such a calculation
     */
    public function getProgress() {
        if (null !== $this->toProcess && null !== $this->processed) {
            return ((float)$this->processed / (float)$this->toProcess);
        } else {
            return null;
        }
    }


    //////////////////////////
    // GETTERS AND SETTERS  //
    //////////////////////////


    /**
     * Gets the The current status of the job.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the The current status of the job.
     *
     * @param string $status the status
     *
     * @return self
     */
    protected function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the Whether the job is still running/queued or if completed/failed.
     *
     * @return boolean
     */
    public function getTerminated()
    {
        return $this->terminated;
    }

    /**
     * Sets the Whether the job is still running/queued or if completed/failed.
     *
     * @param boolean $terminated the terminated
     *
     * @return self
     */
    protected function setTerminated($terminated)
    {
        $this->terminated = $terminated;

        return $this;
    }

    /**
     * Gets the The number of units to process (if supported).
     *
     * @return int|null The number of units to process (or null if no supported by the job return this status)
     */
    public function getToProcess()
    {
        return $this->job->getJobEntity()->getUnitsToProcess();
    }

    /**
     * Sets the The number of units to process (if supported).
     *
     * @param int|null The number of units to process (or null if no supported by the job return this status) $toProcess the to process
     *
     * @return self
     */
    public function setToProcess($toProcess)
    {
        $this->job->getJobEntity()->setUnitsToProcess($toProcess);

        return $this;
    }

    /**
     * Gets the The number of units processed (if supported).
     *
     * @return int|null The number of units processed (or null if not supported by the job returning this status)
     */
    public function getProcessed()
    {
        return $this->job->getJobEntity()->getUnitsProcessed();
    }

    /**
     * Sets the The number of units processed (if supported).
     *
     * @param int|null The number of units processed (or null if not supported by the job returning this status) $processed the processed
     *
     * @return self
     */
    public function setProcessed($processed)
    {
        $this->job->getJobEntity()->setUnitsProcessed($processed);

        return $this;
    }

    /**
     * Gets the Any message added to the job status.
     *
     * @return string|null Message for the job status
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the Any message added to the job status.
     *
     * @param string|null Message for the job status $message the message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Gets the The job the status is for.
     *
     * @return AbstractJob
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Sets the The job the status is for.
     *
     * @param AbstractJob $job the job
     *
     * @return self
     */
    protected function setJob(AbstractJob $job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Gets the The time the job object was created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}