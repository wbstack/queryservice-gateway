<?php

namespace App;

use \App\HostLookup;

class RequestParser{

    /**
     * TODO we should track which case is hit the most to decide the order they should be executed?
     */
    public static function getExternalInternalHosts( $query, $serverArray ) {
      // TODO use strpos first to see if any uris actually appear?
      $uriSuccess = preg_match_all( '/\<(https?:\/\/([^\<\>]+))\/(?:entity|prop|reference|value|wiki)(?:[^\<\>]+)\>/i', $query, $uriMatch );
      $uriDomains = $uriMatch[2];

      if($uriSuccess){

        // HTTP_HOST and URI match
        // Try to figure out the correct taregt by looking at where the request came from
        // and the uris used in the request..
        if( array_key_exists( 'HTTP_HOST', $_SERVER ) && $_SERVER['HTTP_HOST'] ) {
          $requestHost = $_SERVER['HTTP_HOST'];
          if( ( $key = array_search( $requestHost, $uriDomains ) ) !== false ) {
            return[ $uriDomains[$key], HostLookup::getInternalHostFromExternal( $uriDomains[$key] ) ];
          }
        }

      }

      return [null, null];
    }

}
