<?php

namespace TyHand\BackgroundProcessBundle\Tests;

require_once dirname(__DIR__).'/../../../../app/AppKernel.php';
 
abstract class ContainerAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Symfony\Component\HttpKernel\AppKernel
     */
    protected $kernel;
 
    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $container;
 
    /**
     * @return null
     */
    public function setUp()
    {
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();
 
        $this->container = $this->kernel->getContainer();
 
        parent::setUp();
    }
 
    /**
     * @return null
     */
    public function tearDown()
    {
        $this->kernel->shutdown();
 
        parent::tearDown();
    }
}