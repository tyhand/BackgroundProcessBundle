<?php

namespace TyHand\BackgroundProcessBundle\AbstractCommand;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractBackgroundCommand extends ContainerAwareCommand
{
    ////////////////
    // CONSTANTS  //
    ////////////////

    //Status
    const STATUS_LOADING = 'Loading';
    const STATUS_RUNNING = 'Running';
    const STATUS_COMPLETE = 'Complete';
    const STATUS_FAILED = 'Failed';

    ////////////////
    // VARIABLES  //
    ////////////////

    /**
     * The job object for this command
     * @var AbstractJob
     */
    protected $commandJob;

    /////////////////////////
    // OVERRIDDEN METHODS  //
    /////////////////////////


    /**
     * Constructor.
     *
     * @param string $name The name of the command
     *
     * @throws \LogicException When the command name is empty
     *
     * @api
     */
    public function __construct($name = null)
    {
        //Call the command classes original constructor
        parent::__construct($name);

        //Call this methods extra configuration method
        $this->postConfigure();

        //Set the job to null until loaded
        $this->commandJob = null;
    }


    //////////////
    // METHODS  //
    //////////////


    /**
     * Set some extra configuration options
     */
    public function postConfigure()
    {
        $this->addOption('job-entity-id', null, InputOption::VALUE_REQUIRED, 'The id of the job entity');
    }


    /**
     * Gets the job object
     * 
     * @return AbstractJob The job this command is running from (if loaded)
     */
    public function getJob()
    {
        return $this->commandJob;
    }


    /**
     * Sets the status of the underlying job object
     * 
     * @param string $statusName The short name of the status to set
     * @param string $message    Optional message to set along with the status
     */
    public function setJobStatus($statusName, $message = null)
    {
        $this->getContainer()->get('tyhand_background_process.job_manager')
            ->setStatus($this->getJob()->getJobEntity(), $statusName, $message);
    }


    /**
     * Shorthand for setJobStatus('Complete')
     *
     * @param string $message Optional message to set along with the status
     */
    public function setJobStatusComplete($message = null)
    {
        $this->setJobStatus(self::STATUS_COMPLETE, $message);
    }


    /**
     * Shorthand for setJobStatus('Failed')
     * 
     * @param string $message Optional message to set along with the status
     */
    public function setJobStatusFailed($message = null)
    {
        $this->setJobStatus(self::STATUS_FAILED, $message);
    }


    /**
     * Shorthand for setJobStatus('Running')
     * 
     * @param string $message Optional message to set along with the status
     */
    public function setJobStatusRunning($message = null)
    {
        $this->setJobStatus(self::STATUS_RUNNING, $message);
    }


    /**
     * Load the job for use in the command
     * 
     * @param  InputInterface     $input The input interface for the command to extract the entity details from
     * 
     * @return AbstractJob               The job this command is running from
     */
    public function loadJob(InputInterface $input)
    {
        //Build the job
        $this->commandJob = $this->getContainer()->get('tyhand_background_process.job_factory')
            ->buildFromId($input->getOption('job-entity-id'));

        //Mark the job was being worked on
        $this->setJobStatus(self::STATUS_RUNNING);

        //Return the loaded job object
        return $this->commandJob;
    }
}