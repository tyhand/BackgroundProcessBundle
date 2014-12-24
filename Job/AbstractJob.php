<?php

namespace TyHand\BackgroundProcessBundle\Job;

use TyHand\BackgroundProcessBundle\Entity\JobEntity;
use TyHand\BackgroundProcessBundle\Job\JobStatus;

use Symfony\Component\EventDispatcher\Event;

abstract class AbstractJob
{
    ///////////////
    // VARIABLES //
    ///////////////

    /**
     * The parameters array to feed the command
     * @var array
     */
    protected $parameters;

    /**
     * The underlying job entity
     * @var JobEntity
     */
    protected $jobEntity;

    /**
     * The output file for the job
     * @var string
     */
    protected $outputFile;

    ///////////////////
    // BASE METHODS  //
    ///////////////////


    /**
     * A constructor...
     *     completely prevent overriding, as that would prevent the factory from rebuilding the jobs from the entities
     */
    public final function __construct() {
        $this->init();

        //Explicitily set the output file to null
        $this->outputFile = null;
    }

    ///////////////////////
    // ABSTRACT METHODS  //
    ///////////////////////

    /**
     * Get the name of the console command to launch
     *
     * @return
     */
    public abstract function getCommandName();


    /////////////
    // METHODS //
    /////////////


    /**
     * This is called during the creation of the object
     */
    public function init() {

    }


    /**
     * Shortcut for getting the id of the job entity
     *
     * @return int The job entity id
     */
    public function getEntityId() {
        if (null !== $this->jobEntity) {
            return $this->jobEntity->getId();
        } else {
            return null;
        }
    }


    /**
     * Returns the name to display for users (by default this is the command name)
     *
     * @return string The display name for the job
     */
    public function getDisplayName() {
        return $this->getCommandName;
    }


    /**
     * Return an array of strings listing the parameters this job requires
     *
     * @return array The list of parameters required
     */
    public function getRequiredParameters() {
        return array();
    }


    /**
     * Convert the parameters as a string to combine with the command
     *
     * @return string The parameters as a string to call with the command
     */
    public function getParametersAsString() {
        return implode(' ', $this->getFormattedParameters());
    }


    /**
     * Get parameters formatted for use in the process builder or a command line stirng
     *
     * @return array Array of formatted parameters
     */
    public function getFormattedParameters() {
        $return = array();
        foreach($this->parameters as $name => $value) {
            $str = '--' . $name;
            if (null !== $value) {
                $str = ('string' === gettype($value)) ? $str . "='" . $value . "'" : $str . '=' . $value;
            }
            $return[] = $str;
        }
        return $return;
    }


    /**
     * Combines the command name with the parameters string to form the core string for the command
     *
     * @return string Command and parameters
     */
    public function getCommandString() {
        return $this->getCommandName() . ' ' . $this->getParametersAsString();
    }


    /**
     * Adds a parameter to the array
     *
     * @param  string $name  The name of the parameter
     * @param  mixed  $value The value of the parameter (if null string output will look like the following: --hasnovalue )
     *
     * @return self
     */
    public function addParameter($name, $value = null) {
        $this->parameters[$name] = $value;

        return $this;
    }


    /**
     * Gets the value of the parameter
     * @param  string      $name The name of the parameter to get
     *
     * @return mixed       The value, or false if the parameter
     */
    public function getParameter($name) {
        return $this->parameters[$name];
    }


    /**
     * Check if a parameter exists
     *
     * @param  string  $name The name of the parameter to check if exists
     *
     * @return boolean       Whether the parameter exists or not
     */
    public function hasParameter($name) {
        if (array_key_exists($name, $this->parameters)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Creates the job entity to put in the database job queue
     *
     * @return JobEntity The entity
     */
    public function createJobEntity() {
        if (null === $this->jobEntity) {
            $this->jobEntity = new JobEntity();
            $this->jobEntity->setCreatedWhen(new \DateTime());
            $this->jobEntity->setJobClass(get_class($this));
            $this->jobEntity->setParameters($this->parameters);
            $this->jobEntity->setOutputFile($this->outputFile);
        }
        return $this->jobEntity;
    }


    /**
     * Returns the status object for this job (NOTE, will return null if the job isnt queued yet)
     *
     * @return JobStatus|null The status object for the job (or null if the job is not yet queued)
     */
    public function getStatus() {
        //Check that the entity was set
        if (null !== $this->jobEntity && null !== $this->jobEntity->getCurrentStatus()) {
            //Create the new status object
            $jsEntity = $this->jobEntity->getCurrentStatus();
            $status = new JobStatus($jsEntity->getStatus()->getLongName(), $jsEntity->getStatus()->getTerminating(), $this);
            if (null !== $jsEntity->getMessage()) {
                $status->setMessage($jsEntity->getMessage());
            }
            return $status;
        } else {
            return null;
        }
    }


    /**
     * Gives a list of parameters that are displayable to the user
     *
     * @return array The list of parameters names to show the user
     */
    public function displayParameters() {
        return array();
    }


    /**
     * Check whether the job has the required parameters
     *
     * @return boolean True if the required parameters are present, false elsewise
     */
    public function hasRequiredParameters() {
        foreach($this->getRequiredParameters() as $param) {
            if (!$this->hasParameter($param)) {
                return false;
            }
        }
        return true;
    }


    //////////////////////////
    // GETTERS AND SETTERS  //
    //////////////////////////


    /**
     * Gets the The parameters array to feed the command.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Sets the The parameters array to feed the command.
     *
     * @param array $parameters the parameters
     *
     * @return self
     */
    public function setParameters($parameters = null)
    {
        if (null === $parameters) {
            $parameters = array();
        }
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Gets the The underlying job entity.
     *
     * @return JobEntity
     */
    public function getJobEntity()
    {
        return $this->jobEntity;
    }

    /**
     * Sets the The underlying job entity.
     *
     * @param JobEntity $jobEntity the job entity
     *
     * @return self
     */
    public function setJobEntity(JobEntity $jobEntity)
    {
        $this->jobEntity = $jobEntity;

        return $this;
    }

    /**
     * Gets the The output file for the job.
     *
     * @return string
     */
    public function getOutputFile()
    {
        return $this->outputFile;
    }

    /**
     * Sets the The output file for the job.s
     *
     * @param string $outputFile the output file
     *
     * @return self
     */
    public function setOutputFile($outputFile)
    {
        $this->outputFile = $outputFile;

        return $this;
    }
}