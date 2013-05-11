<?php

namespace Astina\Bundle\InjectionBundle\Tests\EventListener;

use Astina\Bundle\InjectionBundle\Annotation\Parameter;
use Astina\Bundle\InjectionBundle\Annotation\Service;
use Astina\Bundle\InjectionBundle\EventListener\ControllerListener;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ControllerListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ControllerListener
     */
    private $listener;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setUp()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->any())
            ->method('getParameter')
            ->will($this->returnValue('foobar'));
        $container->expects($this->any())
            ->method('get')
            ->will($this->returnValue(new \StdClass()));

        // make sure classes are loaded
        new Service(array());
        new Parameter(array());

        $reader = new AnnotationReader();

        $this->container = $container;
        $this->listener = new ControllerListener($container, $reader);
    }

    public function testServiceInjection()
    {
        $controller = new TestController();

        $event = $this->createEvent(array($controller, 'fooAction'));

        $this->assertNull($controller->getService());

        $this->listener->onKernelController($event);

        $this->assertNotNull($controller->getService());
        $this->assertInstanceOf('\StdClass', $controller->getService());
    }

    public function testParameterInjection()
    {
        $controller = new TestController();

        $event = $this->createEvent(array($controller, 'fooAction'));

        $this->assertNull($controller->getParameter());

        $this->listener->onKernelController($event);

        $this->assertEquals('foobar', $controller->getParameter());
    }

    private function createEvent($controller)
    {
        /** @var HttpKernelInterface $kernel */
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = Request::create('http://foo.com/bar', 'GET');

        return new FilterControllerEvent($kernel, $controller, $request, HttpKernelInterface::MASTER_REQUEST);
    }
}
