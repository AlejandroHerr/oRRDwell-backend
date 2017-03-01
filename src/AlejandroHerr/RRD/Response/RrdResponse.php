<?php

namespace AlejandroHerr\RRD\Response;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Simple objecto to hold the rrd responses. Because os lazyness it's just a ParameterBag.
 *
 * @author AlejandroHerr <alejandrohnc88@gmail.com>
 */
class RrdResponse extends ParameterBag
{
    public function merge(array $parameters = [])
    {
        if (isset($this->parameters['data'])) {
            $this->parameters['data'] = array_merge($this->parameters['data'], $parameters['data']);

            return $this;
        }
        $this->parameters = $parameters;

        return $this;
    }
}
