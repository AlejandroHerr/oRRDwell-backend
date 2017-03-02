<?php

namespace AlejandroHerr\Silex;

use Pimple\Container;
use Silex\CallbackResolver;

class SlashActionCallbackResolver extends CallbackResolver
{
    const SERVICE_PATTERN = "/[A-Za-z0-9\._\-]+\/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/";

    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Returns a callable given its string representation.
     *
     * @param string $name
     *
     * @return callable
     *
     * @throws \InvalidArgumentException in case the method does not exist
     */
    public function convertCallback($name)
    {
        if (preg_match(static::SERVICE_PATTERN, $name)) {
            list($service, $method) = explode('/', $name, 2);
            $method .= 'Action';
            $service = 'controller.'.$service;
            $callback = array($this->app[$service], $method);
        } else {
            $service = $name;
            $callback = $this->app[$name];
        }

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException(sprintf('Service "%s" is not callable.', $service));
        }

        return $callback;
    }
}
