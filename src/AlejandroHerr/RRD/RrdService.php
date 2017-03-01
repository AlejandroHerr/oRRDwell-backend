<?php

namespace AlejandroHerr\RRD;

use AlejandroHerr\RRD\Exception\RrdException;
use AlejandroHerr\RRD\Response\RrdResponse;
use GeckoPackages\Silex\Services\Config\ConfigLoader;

class RrdService
{
    protected $files;
    protected $client;
    protected $cofnig;

    public function __construct(ConfigLoader $config, RrdClient $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    public function getLastUpdateAll()
    {
        $response = new RrdResponse();

        foreach (explode(',', $this->config['config']['modules']) as $module) {
            foreach (explode(',', $this->config['configs/'.$module]['names']) as $probe) {
                $file = $this->config['config']['db_folder'].'/'.$module.'_'.$probe.'.rrd';
                $response->merge($this->client->lastUpdate($file, $options));
            }
        }

        return $response;
    }

    public function fetch(string $module, string $probe, array $options = [])
    {
        if (false === $this->moduleIsActive($module)) {
            throw new RrdException(sprintf('Module %s is not active.', $module));
        }
        if (false === $this->probeIsActive($module, $probe)) {
            throw new RrdException(sprintf('Probe %s not active in module %s.', $probe, $module));
        }

        $file = $this->config['config']['db_folder'].'/'.$module.'_'.$probe.'.rrd';

        return new RrdResponse($this->client->fetch($file, $options));
    }

    public function fetchGroup(string $module, array $options = [])
    {
        if (false === $this->moduleIsActive($module)) {
            throw new RrdException(sprintf('Module %s is not active.', $module));
        }

        $response = new RrdResponse();

        foreach (explode(',', $this->config['configs/'.$module]['names']) as $probe) {
            $file = $this->config['config']['db_folder'].'/'.$module.'_'.$probe.'.rrd';
            $response->merge($this->client->fetch($file, $options));
        }

        return $response;
    }

    public function fetchAll(array $options = [])
    {
        $response = new RrdResponse();

        foreach (explode(',', $this->config['config']['modules']) as $module) {
            foreach (explode(',', $this->config['configs/'.$module]['names']) as $probe) {
                $file = $this->config['config']['db_folder'].'/'.$module.'_'.$probe.'.rrd';
                $response->merge($this->client->fetch($file, $options));
            }
        }

        return $response;
    }

    protected function moduleIsActive($module)
    {
        return in_array($module, explode(',', $this->config['config']['modules']));
    }

    protected function probeIsActive($module, $dsname)
    {
        return in_array($dsname, explode(',', $this->config['configs/'.$module]['names']));
    }
}
