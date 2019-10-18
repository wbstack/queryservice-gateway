<?php

namespace App;

class RequestParser
{

    /**
     * TODO we should track which case is hit the most to decide the order they should be executed?
     */
    public static function getExternalHost($query)
    {
        // TODO use strpos first to see if any uris actually appear?
        $uriSuccess = preg_match_all('/\<(https?:\/\/([^\<\>]+))\/(?:entity|prop|reference|value|wiki)(?:[^\<\>]+)\>/i', $query, $uriMatch);

        if ($uriSuccess) {

            $uriDomains = array_unique($uriMatch[2]);

            if (count($uriDomains) != 1) {
                // TODO log uncertainty?
            }

            return $uriDomains[0];
        }

        return null;
    }

}
