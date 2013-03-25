<?php

namespace Astina\Bundle\InjectionBundle\Annotation;

use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 */
class Service implements Annotation
{
    /**
     * @var string
     */
    public $id;
}