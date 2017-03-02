<?php

namespace AlejandroHerr\oRRDwell\Controller;

use AlejandroHerr\RRD\Exception\RrdException;
use AlejandroHerr\RRD\RrdService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiController
{
    private $rrd;

    public function __construct(RrdService $rrd)
    {
        $this->rrd = $rrd;
    }

    public function fetchAction(Request $request, $module, $probe)
    {
        try {
            $response = $this->rrd
                ->fetch($module, $test, ['AVERAGE', '--start', 'N-1h']);
        } catch (RrdException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $response->toArray();
    }

    public function fetchModuleAction(Request $request, $module)
    {
        try {
            $response = $this->rrd
                ->fetchModule($module, ['AVERAGE', '--start', 'N-1h']);
        } catch (RrdException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $response->toArray();
    }

    public function fetchAllAction(Request $request)
    {
        try {
            $response = $this->rrd
                ->fetchAll($module, ['AVERAGE', '--start', 'N-1h']);
        } catch (RrdException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $response->toArray();
    }
}
