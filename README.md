Astina Injection Bundle
=======================

Lets you inject services and container parameters into controllers.

## Installation

### Step 1: Add to composer.json

```
"require" :  {
    // ...
    "astina/injection-bundle":"dev-master",
}
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Astina\Bundle\InjectionBundle\AstinaInjectionBundle(),
    );
}
```

##Usage

```php
use Astina\Bundle\InjectionBundle\Annotation as Inject;

class DefaultController
{
    /**
     * @Inject\Service("session")
     * @var SessionInterface
     */
    private $session;

    /**
     * @Inject\Parameter("acme_foo")
     */
    private $foo;

    /**
     * @Route("/foo", name="foo")
     * @Template
     */
    public function indexAction()
    {
        $foo = $this->session->get($this->foo);

        return array(
            'foo' => $foo,
        );
    }
}
```