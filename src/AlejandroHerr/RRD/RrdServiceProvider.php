<?php

namespace AlejandroHerr\RRD;

use AlejandroHerr\RRD\Parser\RrdResponseParser;
use GeckoPackages\Silex\Services\Config\ConfigLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RrdServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['rrd.service'] = function () use ($app) {
            $config = new ConfigLoader(
                $app,
                '/home/alejandro/Code/rrd',
                '%key%.yaml',
                null,
                null
            );

            $client = new RrdClient(new RrdResponseParser());

            return new RrdService($config, $client);
        };
    }
}
