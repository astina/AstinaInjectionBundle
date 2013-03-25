<?php

namespace Astina\Bundle\InjectionBundle\EventListener;

use Astina\Bundle\InjectionBundle\Annotation\Parameter;
use Astina\Bundle\InjectionBundle\Annotation\Service;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    protected $reader;

    public function __construct(ContainerInterface $container, Reader $reader)
    {
        $this->container = $container;
        $this->reader = $reader;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : get_class($controller[0]);
        $object = new \ReflectionClass($className);

        foreach ($object->getProperties() as $property) {

            // service injection
            $annotation = $this->reader->getPropertyAnnotation($property, 'Astina\Bundle\InjectionBundle\Annotation\Service');
            if ($annotation) {
                /** @var $annotation Service */
                $serviceId = $annotation->id;
                $service = $this->container->get($serviceId);
                $this->injectValue($controller[0], $property, $service);
            }

            // parameter injection
            $annotation = $this->reader->getPropertyAnnotation($property, 'Astina\Bundle\InjectionBundle\Annotation\Parameter');
            if ($annotation) {
                /** @var $annotation Parameter */
                $parameterName = $annotation->name;
                $service = $this->container->getParameter($parameterName);
                $this->injectValue($controller[0], $property, $service);
            }
        }
    }

    /**
     * @param object $controller A controller instance
     * @param \ReflectionProperty $property
     * @param mixed $value
     */
    private function injectValue($controller, \ReflectionProperty $property, $value)
    {
        $wasPublic = true;
        if (!$property->isPublic()) {
            $property->setAccessible(true);
            $wasPublic = false;
        }

        $property->setValue($controller, $value);

        if (!$wasPublic) {
            $property->setAccessible(false);
        }
    }
}
