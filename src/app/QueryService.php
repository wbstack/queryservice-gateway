<?php

namespace App;

class QueryService{

    private static $bypassRealQuery = false;
    private static $nextReponse;
    public static $lastQuery;
    public static $lastHeaders;

    public static function setNextResponse($response) {
        self::$nextReponse = $response;
    }

    public static function setNextQueryIsTestBypass() {
      self::$bypassRealQuery = true;
    }

    public static function query($sparqlEndpoint, $query, $extraCurlHeaders, $otherParameters) {
        if(self::$bypassRealQuery) {
          self::$bypassRealQuery = false;
          self::$lastQuery = $query;
          self::$lastHeaders = $extraCurlHeaders;
          return self::$nextReponse;
        }

        $otherParameters['query'] = $query;

        // Make request to inner / other sparql endpoint
        $ch = curl_init( $sparqlEndpoint . '?' . http_build_query( $otherParameters ));
        $responseHeaders = [];
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_USERAGENT,'WbStack(Addshore) wdqs-gateway');

        // https://stackoverflow.com/a/41135574
        // this function is called by curl for each header received
        curl_setopt($ch, CURLOPT_HEADERFUNCTION,
            function($curl, $header) use (&$responseHeaders)
            {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $responseHeaders[strtolower(trim($header[0]))][] = trim($header[1]);

                return $len;
            }
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $extraCurlHeaders);

        $data = curl_exec($ch);
        curl_close($ch);

        return [$data, $responseHeaders];
    }

}
