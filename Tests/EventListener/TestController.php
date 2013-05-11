<?php

namespace Astina\Bundle\InjectionBundle\Tests\EventListener;

use Astina\Bundle\InjectionBundle\Annotation as Inject;

class TestController
{
    /**
     * @Inject\Service("acme_foo.my_service")
     */
    private $service;

    /**
     * @Inject\Parameter("acme_foo.bar")
     */
    private $parameter;

    public function fooAction()
    {

    }

    public function getService()
    {
        return $this->service;
    }

    public function getParameter()
    {
        return $this->parameter;
    }
}
