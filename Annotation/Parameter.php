<?php

namespace Astina\Bundle\InjectionBundle\Annotation;

use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 */
class Parameter implements Annotation
{
    public $name;
}
