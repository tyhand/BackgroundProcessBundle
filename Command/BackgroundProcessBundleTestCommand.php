<?php

namespace TyHand\BackgroundProcessBundle\Command;

use TyHand\BackgroundProcessBundle\AbstractCommand\AbstractBackgroundCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BackgroundProcessBundleTestCommand extends AbstractBackgroundCommand
{
    protected function configure() {
        $this
            ->setName('tyhand:background_process:background_process_bundle_test')
            ->setDescription('Test command for testing the background process bundle')
            ->addOption('loops', null, InputOption::VALUE_REQUIRED, 'How many loops to run before ending')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output) {
        //Get the job object
        $job = $this->loadJob($input);

        //Get the number of loops to go thrugh
        $loops = $job->getParameter('loops');

        //Go through the loops
        for($i = 0; $i < $loops; $i++) {
            $output->writeln('It is currently ' . date('H:i:s'));
            $this->setJobStatusRunning($i);
            sleep(5);
        }

        //Set job status to complete
        $this->setJobStatusComplete('Completed ' . $loops . ' loops');
    }
}