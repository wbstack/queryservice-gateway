<?php

namespace App;

class RequestParser
{
    /**
     * TODO we should track which case is hit the most to decide the order they should be executed?
     */
    public static function getExternalHost($query)
    {
        // Otherwise require the path..
        $uriSuccess = preg_match_all(
            '/' . // delimiter
            '\<' . // < start of URI
            '(https?:\/\/([^\<\>\/]+))' . // actual body of the URI (no < or > or /)
            '(\/?((?:entity|prop|reference|value|wiki)(?:[^\<\>]+))?)' . // NON OPTIONAL path for URI (no < or >)
            '\>' . // end of URI
            '/i' // delimiter and options
            ,
            $query,
            $uriMatch
        );

        if ($uriSuccess) {

            $uriDomains = $uriMatch[2];

            // array_values so that this is 0 indexed
            $uriDomains = array_values( array_unique($uriDomains) );

            //if (count($uriDomains) != 1) {
                // TODO log uncertainty?
            //}

            return $uriDomains[0];
        }

        return null;
    }

}
