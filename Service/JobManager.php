<?php

namespace TyHand\BackgroundProcessBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\SecurityContext;

use TyHand\BackgroundProcessBundle\Job\AbstractJob;
use TyHand\BackgroundProcessBundle\Service\JobFactory;
use TyHand\BackgroundProcessBundle\Entity\StatusEntity;
use TyHand\BackgroundProcessBundle\Entity\JobEntity;
use TyHand\BackgroundProcessBundle\Entity\JobStatusEntity;

class JobManager
{
    ////////////////
    // CONSTANTS  //
    ////////////////

    //Error
    const ERROR_MISSING_STATUS = 'Missing status ';

    //Status
    const STATUS_LOADING = 'Loading';
    const STATUS_RUNNING = 'Running';
    const STATUS_COMPLETE = 'Complete';
    const STATUS_FAILED = 'Failed';

    ////////////////
    // VARIABLES  //
    ////////////////

    /**
     * Reference to doctrines manager registry
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * Reference to symfonys security context
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * Reference to the job factory service
     * @var JobFactory
     */
    protected $jobFactory;

    /**
     * The name of the entity manager managing the job entities
     * @var string
     */
    protected $entityManagerName;

    ///////////////////
    // BASE METHODS  //
    ///////////////////


    /**
     * Constructor
     *
     * @param ManagerRegistry $managerRegistry Doctrines manager registry
     * @param SecurityContext $securityContext Symfonys security context
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        SecurityContext $securityContext,
        JobFactory $jobFactory
    ) {
        //Set the object properties
        $this->managerRegistry = $managerRegistry;
        $this->securityContext = $securityContext;
        $this->jobFactory = $jobFactory;
    }


    //////////////
    // METHODS  //
    //////////////


    /**
     * Initializes a new job entity from a job object
     *
     * @param  AbstractJob $job The job object to generate a new job entity for
     *
     * @return JobEntity        The new job entity
     */
    public function initializeJobEntity(AbstractJob $job)
    {
        //Create the job entity from the job object
        $entity = $job->createJobEntity();

        //Set the fields regarding current user and time
        if (null !== $this->securityContext->getToken()) {
            $username = $this->securityContext->getToken()->getUsername();
        } else {
            $username = 'UNIT_TEST';
        }
        $entity->setCreatedWho($username);
        $entity->setCreatedWhen(new \DateTime());

        //Set the current status
        $this->setStatus($entity, self::STATUS_LOADING);

        //Set the job entity parameter in the job
        $job->addParameter('job-entity-id', $entity->getId());

        //Return
        return $entity;
    }


    /**
     * Update the status of a job
     *
     * @param JobEntity $entity          The entity of the job to update the status of
     * @param string    $statusShortName The short name of the status to set
     * @param string    $message         The message to add to the job status
     */
    public function setStatus(JobEntity $entity, $statusShortName, $message = null)
    {
        //Get the status
        $status = $this->getStatus($statusShortName);
        if (null === $status) {
            throw new \Exception(self::ERROR_MISSING_STATUS . $statusShortName);
        }

        //Update the status
        $jobStatus = new JobStatusEntity();
        $jobStatus->setDate(new \DateTime());
        $jobStatus->setStatus($status);
        if (null !== $message) {
            $jobStatus->setMessage($message);
        }
        $jobStatus->setJob($entity);
        $entity->addAndUpdateStatus($jobStatus);

        //Persist changes
        $this->getEntityManager()->persist($jobStatus);
        $this->getEntityManager()->persist($entity);

        //Flush
        $this->getEntityManager()->flush();
    }


    /**
     * Set the process id from a job entity
     *
     * @param JobEntity $jobEntity The entity to set the process id for
     * @param int       $pid       The process id to set (or null if one does not exist)
     */
    public function setJobProcessId(JobEntity $jobEntity, $pid = null)
    {
        $jobEntity->setPid($pid);
        $this->getEntityManager()->persist($jobEntity);

        return $jobEntity;
    }


    /**
     * Get the status entity with the given short name
     *
     * @param  string       $statusShortName The short name of the requested status
     *
     * @return StatusEntity                  The requested status entity if it exists
     */
    public function getStatus($statusShortName)
    {
        return $this->getEntityManager()->getRepository('TyHandBackgroundProcessBundle:StatusEntity')
            ->findOneByShortName($statusShortName);
    }


    /**
     * Gets the entity manager that manages the job entities
     *
     * @return EntityManager The entity manager
     */
    protected function getEntityManager()
    {
        return $this->managerRegistry->getManager($this->getEntityManagerName());
    }


    //////////////////////////
    // GETTERS AND SETTERS  //
    //////////////////////////

    /**
     * Gets the The name of the entity manager managing the job entities.
     *
     * @return string
     */
    public function getEntityManagerName()
    {
        return $this->entityManagerName;
    }

    /**
     * Sets the The name of the entity manager managing the job entities.
     *
     * @param string $entityManagerName the entity manager name
     *
     * @return self
     */
    public function setEntityManagerName($entityManagerName)
    {
        $this->entityManagerName = $entityManagerName;

        return $this;
    }
}