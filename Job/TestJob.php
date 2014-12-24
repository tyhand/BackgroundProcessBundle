<?php

namespace TyHand\BackgroundProcessBundle\Job;

use TyHand\BackgroundProcessBundle\Job\AbstractJob;

class TestJob extends AbstractJob
{
    /**
     * Get the name of the console command to launch
     * 
     * @return string The name of the command
     */
    public function getCommandName() 
    {
        return 'tyhand:test:background_process_bundle_test';
    }


    /**
     * Return an array of strings listing the parameters this job requires
     * 
     * @return array The list of parameters required
     */
    public function getRequiredParameters() {
        return array('loops');
    }
}