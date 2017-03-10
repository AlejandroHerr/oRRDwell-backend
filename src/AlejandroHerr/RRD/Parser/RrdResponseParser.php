<?php

namespace AlejandroHerr\RRD\Parser;

use AlejandroHerr\RRD\Response\RrdResponse;

/**
 * Object to transform rrd responses into objects.
 *
 * @author AlejandroHerr <alejandrohnc88@gmail.com>
 */
class RrdResponseParser
{
    public function parseLastUpdateResponse(array $rawResponse)
    {
        return new RrdResponse([
            'module' => $fileinfo->getModule(),
            'name' => $fileinfo->getName(),
            'test' => $fileinfo->getTest(),
            'data' => [[
                'time' => $rawResponse['last_update'],
                'value' => $rawResponse['data'][0],
            ]],
        ]);
    }

    public function parseFetchResponse(array $rawResponse)
    {
        $data = $rawResponse['data'];

        $rawResponse['data'] = \Functional\map($data, function ($probe) {
            return array_values(\Functional\map($probe, function ($value, $time) {
                if (is_nan($value)) {
                    return [$time];
                }

                return [$time, $value];
            }));
        });

        return $rawResponse;
    }
}
