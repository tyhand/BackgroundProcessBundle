<?php

namespace TyHand\BackgroundProcessBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

use TyHand\BackgroundProcessBundle\Entity\JobEntity;
use TyHand\BackgroundProcessBundle\Job\AbstractJob;

/**
 * Rebuilds the job object from the job entity
 */
class JobFactory
{
    ///////////////
    // VARIABLES //
    ///////////////

    /**
     * Reference to the doctrine manager registry
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * The name of the entity manager for the job entites
     * @var string
     */
    protected $entityManagerName;

    ///////////////////
    // BASE METHODS  //
    ///////////////////


    /**
     * Constructor
     *
     * @param EntityJobFactory $entityJobFactory Entity job factory service
     * @param ManagerRegistry  $managerRegistry  Doctrines manager registry
     */
    public function __construct(ManagerRegistry $managerRegistry) {
        $this->managerRegistry = $managerRegistry;
    }


    //////////////
    // METHODS  //
    //////////////


    /**
     * Build the job from the id for the job entity
     *
     * @param  int         $jobEntityId The id of the job entity to build the job from
     *
     * @return AbstractJob              The rebuilt job object
     */
    public function buildFromId($jobEntityId)
    {
        //Check that the entity manager was set
        if (null !== $this->getEntityManager()) {
            return $this->build(
                $this->getEntityManager()->getRepository('TyHandBackgroundProcessBundle:JobEntity')
                    ->findOneById($jobEntityId)
            );
        } else {
            return null;
        }
    }


    /**
     * Recreate the job object from the entity
     *
     * @param  JobEntity   $jobEntity The job entity
     *
     * @return AbstractJob            The rebuilt job object
     */
    public function build(JobEntity $jobEntity)
    {
        //Check that the class exists
        if (class_exists($jobEntity->getJobClass())) {
            //Create new instance
            $class = $jobEntity->getJobClass();
            $job = new $class;
        } else {
            //throw exception that the class does not exist
            throw new \Exception('Job Class ' . $jobEntity->getJobClass() . ' does not exist');
        }

        //Get the job object the deserialized parameters
        $job->setParameters($jobEntity->getParameters());

        //Set the originating entity
        $job->setJobEntity($jobEntity);

        //Return the rebuilt job object
        return $job;
    }


    /**
     * Get the entity manager that is responsible for managing the job entities
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
     * Gets the The name of the entity manager for the job entites.
     *
     * @return string
     */
    public function getEntityManagerName()
    {
        return $this->entityManagerName;
    }

    /**
     * Sets the The name of the entity manager for the job entites.
     *
     * @param string $entityManagerName the entity manager name
     *
     * @return self
     */
    protected function setEntityManagerName($entityManagerName)
    {
        $this->entityManagerName = $entityManagerName;

        return $this;
    }
}