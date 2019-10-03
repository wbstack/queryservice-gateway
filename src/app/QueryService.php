<?php

namespace App;

class QueryService{

    private static $bypassRealQuery = false;
    private static $nextReponse;
    public static $lastQuery;
    public static $lastHeaders;
    private static $sparqlApi = null;

    public static function setNextResponse($response) {
        self::$nextReponse = $response;
    }

    public static function setNextQueryIsTestBypass() {
      self::$bypassRealQuery = true;
    }

    public static function query($sparqlEndpoint, $query, $extraCurlHeaders) {
        if(self::$bypassRealQuery) {
          self::$bypassRealQuery = false;
          self::$lastQuery = $query;
          self::$lastHeaders = $extraCurlHeaders;
          return self::$nextReponse;
        }

        // Make request to inner / other sparql endpoint
        $ch = curl_init( $sparqlEndpoint . "?query=" . urlencode($query));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_USERAGENT,'OpenCura(Addshore) wdqs-gateway');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $extraCurlHeaders);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

}
