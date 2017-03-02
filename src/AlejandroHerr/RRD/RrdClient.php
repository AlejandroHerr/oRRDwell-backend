<?php

namespace AlejandroHerr\RRD;

use AlejandroHerr\RRD\Exception\RrdException;
use AlejandroHerr\RRD\Parser\RrdResponseParser;

/**
 * RRD client is a simple wrapper around the php native rrd client.
 *
 * @author AlejandroHerr <alejandrohnc88@gmail.com>
 */
class RrdClient
{
    protected $parser;
    protected $path;

    public function __construct(RrdResponseParser $parser)
    {
        $this->parser = $parser;
    }

    public function lastUpdate(string $file)
    {
        $rawResponse = rrd_lastupdate($file);

        return $this->parser->parseLastUpdateResponse($file, $rawResponse);
    }

    public function fetch(string $file, array $options = [])
    {
        if (false === $rawResponse = rrd_fetch($file, $options)) {
            throw new RrdException('RRD file not found');
        }

        return $this->parser->parseFetchResponse($rawResponse);
    }
}
