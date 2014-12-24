<?php

namespace TyHand\BackgroundProcessBundle\Tests\Service;

use TyHand\BackgroundProcessBundle\Tests\ContainerAwareTest;

use TyHand\BackgroundProcessBundle\Job\TestJob;
use TyHand\BackgroundProcessBundle\Entity\JobEntity;

class JobLauncherTest extends ContainerAwareTest
{
    public function testLaunchJob()
    {
        //Create a new testjob for testing
        $job = new TestJob();

        //Assert the command name can be retrieved
        $this->assertEquals('tyhand:background_process:background_process_bundle_test', $job->getCommandName());

        //Set the loops parameter to 5
        $job->addParameter('loops', 5);
        $this->assertEquals(5, $job->getParameter('loops'));

        //Assert that the required parameters are met
        $this->assertTrue($job->hasRequiredParameters());

        //Launch the job
        $entity = $this->container->get('tyhand_background_process.job_launcher')->launchJob($job);

        //Assert the returned item is the job entity
        $this->assertTrue($entity instanceof JobEntity);

        //Sleep for a couple of seconds then assert that the status of the job is running
        sleep(5);

        $updatedJob = $this->container->get('tyhand_background_process.job_factory')
            ->buildFromId($entity->getId());

        $this->assertEquals('Running', $updatedJob->getStatus()->getStatus());

        //Sleep again and check that the message contains a number greater than 0
        sleep(10);

        $updatedJob = $this->container->get('tyhand_background_process.job_factory')
            ->buildFromId($entity->getId());

        $this->assertGreaterThan(0, intval($updatedJob->getStatus()->getMessage()));

        //Sleep again and check that the porcess was completed
        sleep(25);

        $updatedJob = $this->container->get('tyhand_background_process.job_factory')
            ->buildFromId($entity->getId());

        $this->assertEquals('Complete', $updatedJob->getStatus()->getStatus());
    }
}