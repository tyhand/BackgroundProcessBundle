<?php

namespace TyHand\BackgroundProcessBundle\Service;

use Symfony\Component\HttpKernel\KernelInterface;

use TyHand\BackgroundProcessBundle\Job\AbstractJob;
use TyHand\BackgroundProcessBundle\Service\JobManager;
use TyHand\BackgroundProcessBundle\Entity\JobEntity;

class JobLauncher
{
    ///////////////
    // VARIABLES //
    ///////////////

    /**
     * Reference to the symfony kernel
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * The name of the entity manager to use to save the job entities in
     * @var string
     */
    protected $entityManager;

    /**
     * Reference to the job manager service
     * @var JobManager
     */
    protected $jobManager;

    ///////////////////
    // BASE METHODS  //
    ///////////////////


    /**
     * Constructor
     *
     * @param KernelInterface $kernel [description]
     */
    public function __construct(
        KernelInterface $kernel,
        JobManager $jobManager
    ) {
        //Set the variables
        $this->kernel = $kernel;
        $this->jobManager = $jobManager;
    }

    /////////////
    // METHODS //
    /////////////


    /**
     * Launch a job
     *
     * @param  AbstractJob $job The job object to launch
     *
     * @return JobEntity        The process id of the job
     */
    public function launchJob(AbstractJob $job)
    {
        //Add the environment variable to the job
        $job->addParameter('env', $this->kernel->getEnvironment());

        //Check that the job has the required parameters
        if (!$job->hasRequiredParameters()) {
            throw new \Exception('Job is missing required parameters');
        }

        //If the output file is not set, set the default
        if (null === $job->getOutputFile()) {
            if ($this->isWindows()) {
                //Set the default for Windows
                $job->setOutputFile(null);
            } else {
                //Set the default for Linux/Unix/Mac
                $job->setOutputFile('/dev/null');
            }
        }

        //Setup the job entity
        $entity = $this->jobManager->initializeJobEntity($job);

        //Launch
        $ret = $this->launch($job);

        //Set the pid field in the entity
        $this->jobManager->setJobProcessId($entity, $ret);

        //Return
        return $entity;
    }


    /**
     * Returns true if the current environment is windows
     *
     * @return boolean Whether the current environment is windows or not
     */
    public function isWindows() {
        return ('WIN' === strtoupper(substr(PHP_OS, 0, 3)));
    }


    ///////////////////////
    // INTERNAL METHODS  //
    ///////////////////////


    /**
     * Determine which method to use to launch the job
     *
     * @param  AbstractJob $job The prepped job
     *
     * @return boolean          The success of the operation
     */
    protected function launch(AbstractJob $job) {
        if ($this->isWindows()) {
            $ret = $this->launchWin($job);
        } else {
            $ret = $this->launchNix($job);
        }
        return $ret;
    }


    /**
     * Launch the job for windows (Assumes that the php path variable is set)
     *
     * @param  AbstractJob $job The prepped job to launch
     *
     * @return int              The process id
     */
    protected function launchWin(AbstractJob $job) {
        //Construct the command string
        $cmd = 'php ../app/console ' . $job->getCommandString();
        if (null !== $job->getOutputFile()) {
            $cmd =  $cmd . ' > ' . $job->getOutputFile();
        }

        //Uses the WScript Shell work around (NOTE: this requires the php path variable to be set)
        $WshShell = new \COM("WScript.Shell");
        $oExec = $WshShell->Run($cmd, 0, false);

        //return 1
        //@TODO: determine whether process ids exist for windows and if they can be obtained here
        return 1;
    }


    /**
     * Launch the job for linux/unix/mac
     *
     * @param  AbstractJob $job The prepped job to launch
     *
     * @return int              The process id
     */
    protected function launchNix(AbstractJob $job) {
        //Construct the command string
        $cmd = '../app/console ' . $job->getCommandString();

        $c = sprintf('%s > %s 2>&1 & echo $!', $cmd, $job->getOutputFile());

        //Execute
        $pid = shell_exec(sprintf('%s > %s 2>&1 & echo $!', $cmd, $job->getOutputFile()));

        //Return the pid
        return intval($pid);
    }
}