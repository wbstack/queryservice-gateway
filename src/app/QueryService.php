<?php

namespace App;

class QueryService{

    private static $nextReponse;
    public static $lastQuery;

    public static function setNextResponse($response) {
        self::$nextReponse = $response;
    }

    public static function query($query) {
        self::$lastQuery = $query;
        return self::$nextReponse;
    //   // 5 - Make request to inner / other sparql endpoint
    //   // TODO forward on header
    //   $ch = curl_init("https://query.wikidata.org/sparql?query=" . urlencode($query));
    //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //   curl_setopt($ch, CURLOPT_HEADER, 0);
    //   curl_setopt($ch,CURLOPT_USERAGENT,'Addshore OpenCura wdqs-gateway testing');
    //   $data = curl_exec($ch);
    //   curl_close($ch);
    }

}
