<?php

namespace TyHand\BackgroundProcessBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StatusController extends ContainerAware
{
    /**
     * Get the status of a job
     * 
     * @param  int      $jobId The id of the job to get the status of
     * 
     * @return Response        The rendered display of the jobs status
     */
    public function getStatusAction($jobId) {
        //Get the job
        $job = $this->container->get('tyhand_background_process.job_factory')->buildFromId($jobId);
        if (null === $job) {
            $status = null;
        } else {
            $status = $job->getStatus();
        }

        //Render the template
        return new Response($this->container->get('templating')->render(
            'TyHandBackgroundProcessBundle:Status:status.html.twig', array(
                'status' => $status
        )));
    }
}