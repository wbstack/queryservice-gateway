<?php

namespace App;

class QueryService{

    private static $bypassRealQuery = false;
    private static $nextReponse;
    public static $lastQuery;
    private $sparqlApi = null;

    public static function setNextResponse($response) {
        self::$nextReponse = $response;
    }

    public static function setNextQueryIsTestBypass() {
      self::$bypassRealQuery = true;
    }

    public static function setSparqlApi($api) {
      self::$sparqlApi = $api;
    }

    public static function query($query) {
        if(self::$bypassRealQuery) {
          self::$bypassRealQuery = false;
          self::$lastQuery = $query;
          return self::$nextReponse;
        }

        if(!self::$sparqlApi) {
          throw new \RuntimeException('self::$sparqlApi not yet set');
        }

        // Make request to inner / other sparql endpoint
        $ch = curl_init( self::$sparqlApi . "?query=" . urlencode($query));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_USERAGENT,'OpenCura(Addshore) wdqs-gateway');

        // Headers that were set in wdqs-proxy before...
        // TODO maybe we should continue using the proxy? For caching?
        // but should be caching come before or after this? Probably below?
        // user -> edge -> cache -> gateway -> proxy? -> blazegraph    ????
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-BIGDATA-MAX-QUERY-MILLIS: 60000',// 60000 = 60s
            'X-BIGDATA-READ-ONLY: yes',
        ]);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

}
