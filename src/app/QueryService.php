<?php

namespace App;

class QueryService{

    private static $nextReponse;
    private static $expectedQuery;

    public static function setExpectedQuery($response) {
        self::$expectedQuery = $response;
    }

    public static function setNextResponse($response) {
        self::$nextReponse = $response;
    }

    public static function query($query) {
        if( $query !== self::$expectedQuery ) {
          return "Expected Query Was Wrong...";
        }
    //   // 5 - Make request to inner / other sparql endpoint
    //   // TODO forward on header
    //   $ch = curl_init("https://query.wikidata.org/sparql?query=" . urlencode($query));
    //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //   curl_setopt($ch, CURLOPT_HEADER, 0);
    //   curl_setopt($ch,CURLOPT_USERAGENT,'Addshore OpenCura wdqs-gateway testing');
    //   $data = curl_exec($ch);
    //   curl_close($ch);
        return self::$nextReponse;
    }

}
