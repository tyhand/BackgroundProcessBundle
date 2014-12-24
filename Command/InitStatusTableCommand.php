<?php

namespace TyHand\BackgroundProcessBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use TyHand\BackgroundProcessBundle\Entity\StatusEntity;

class InitStatusTableCommand extends ContainerAwareCommand
{
    /**
     * Configure the commnd
     */
    protected function configure() {
        $this
            ->setName('tyhand:background_process:init_status_table')
            ->setDescription('Initializes the status table for the job queue')
            ->addOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager that holds the job queue and status table')
        ;
    }


    /**
     * Execute the worker process
     * 
     * @param  InputInterface  $input  The input interface
     * @param  OutputInterface $output The output interface
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        //Get the entity manager
        $em = $this->getContainer()->get('doctrine')->getManager($input->getOption('em'));

        //Get the existing statuses if they exist
        $existing = $em->getRepository('TyHandBackgroundProcessBundle:StatusEntity')->getAll();

        //Rekey the results
        $existingStatuses = array();
        foreach($existing as $ex) {
            $existingStatuses[$ex->getShortName()] = $ex;
        }

        //Add the missing statuses
        foreach($this->getStatuses() as $status) {
            if (array_key_exists($status->getShortName(), $existingStatuses)) {
                //Check if needs update
                if ($existingStatuses[$status->getShortName()]->getLongName() !== $status->getLongName()) {
                    $existingStatuses[$status->getShortName()]->setLongName($status->getLongName());
                    $em->persist($existingStatuses[$status->getShortName()]);
                    $output->writeln('Updating long name of status ' . $status->getShortName());
                }
                if ($existingStatuses[$status->getShortName()]->getTerminating() !== $status->getTerminating()) {
                    $existingStatuses[$status->getShortName()]->setTerminating($status->getTerminating());
                    $em->persist($existingStatuses[$status->getShortName()]);
                    $output->writeln('Updating the terminating flag of status ' . $status->getShortName());
                }
            } else {
                //add
                $em->persist($status);
                $output->writeln('Adding new status: ' . $status->getShortName());
            }
        }

        //Flush the changes
        $em->flush();

        //Complete
        $output->writeln('Process complete!');
    }


    /**
     * Create the status entities to put in the database if they do not currently exist
     *
     * @return  array The status entities to ensure are in the database
     */
    private function getStatuses() {
        $statuses = array();

        //Loading
        $statuses['Loading'] = new StatusEntity();
        $statuses['Loading']->setShortName('Loading');
        $statuses['Loading']->setLongName('Loading');
        $statuses['Loading']->setTerminating(false);

        //Running
        $statuses['Running'] = new StatusEntity();
        $statuses['Running']->setShortName('Running');
        $statuses['Running']->setLongName('Running');
        $statuses['Running']->setTerminating(false);

        //Complete
        $statuses['Complete'] = new StatusEntity();
        $statuses['Complete']->setShortName('Complete');
        $statuses['Complete']->setLongName('Complete');
        $statuses['Complete']->setTerminating(true);

        //Failed
        $statuses['Failed'] = new StatusEntity();
        $statuses['Failed']->setShortName('Failed');
        $statuses['Failed']->setLongName('Failed');
        $statuses['Failed']->setTerminating(true);

        //Return
        return $statuses;
    }
}