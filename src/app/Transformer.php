<?php

namespace App;

class Transformer{

    public static function transformQuery($query, $internal, $external) {
      $query = preg_replace(
        "/(<[^\>]*)(https?:\/\/)(www\.)?(" . preg_quote($external) . ")\/(entity|prop|reference|value|wiki)([^\<]*>)/i",
        '$1http://$3' . $internal . '/$5$6',
        $query
      );

      return $query;
    }
    public static function transformResponse($response, $internal, $external) {
      $response = str_replace($internal, $external, $response);

      return $response;
    }

}
