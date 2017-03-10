<?php

namespace AlejandroHerr\oRRDwell;

use AlejandroHerr\oRRDwell\Controller\ApiController;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class ApiControllerProvider implements ServiceProviderInterface, ControllerProviderInterface
{
    public function register(Container $app)
    {
        $app['controller.api'] = new ApiController($app['rrd.service']);
    }

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/fetch/views', 'api/getViews');
        $controllers->get('/fetch/all', 'api/fetchAll');
        $controllers->get('/fetch/{module}/{probe}', 'api/fetch');
        $controllers->get('/fetch/{module}', 'api/fetchModule');

        return $controllers;
    }
}
