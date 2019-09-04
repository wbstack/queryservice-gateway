<?php

namespace App;

class Transformer{

    public static function transformQuery($query, $internal, $external) {
      $query = preg_replace(
        "/(https?:\/\/)(www\.)?($external)\/(entity|prop|reference|value|wiki)/",
        'http://$2' . $internal . '/$4',
        $query
      );

      return $query;
    }
    public static function transformResponse($response, $internal, $external) {
      $response = str_replace($internal, $external, $response);

      return $response;
    }

}
