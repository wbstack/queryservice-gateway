<?php

namespace App;

class RequestParser{

    private static function getInternalHostFromExternal( $external ) {
      $lookup = [
        'www.wikidata.org' => 'wdinternal',
        'wikibase-registry.wmflabs.org' => 'registryinternal',
      ];
      if( array_key_exists($external, $lookup) ) {
        return $lookup[$external];
      }
      throw new \RuntimeException('could not find');
    }

    /**
     * TODO we should track which case is hit the most to decide the order they should be executed?
     */
    public static function getExternalInternalHosts( $query, $serverArray ) {
      // TODO use strpos first to see if any prefixes actually appear?
      $prefixSuccess = preg_match_all( '/(?:.*)PREFIX(?:.*):(?:.*)<(https?:\/\/(.+?))\/(?:entity|prop|reference|value|wiki)\/?(?:.*)>/i', $query, $prefixMatch );
      $prefixDomains = $prefixMatch[2];

      if($prefixSuccess){

        // HTTP_HOST and PREFIX match
        // Try to figure out the correct taregt by looking at where the request came from
        // and the headers used in the request..
        if( array_key_exists( 'HTTP_HOST', $_SERVER ) && $_SERVER['HTTP_HOST'] ) {
          $requestHost = $_SERVER['HTTP_HOST'];
          if( ( $key = array_search( $requestHost, $prefixDomains ) ) !== false ) {
            return[ $prefixDomains[$key], self::getInternalHostFromExternal( $prefixDomains[$key] ) ];
          }
        }

        // Guess that the first prefix we find will be the right one? D:
        // XXX: This is evil as prooved by the test it doesnt really work...
        // TODO look at SERVICES used in the query and eliminate them?
        // return[ $prefixDomains[0], self::getInternalHostFromExternal( $prefixDomains[0] ) ];

      }



      return [null, null];

      // 2 - Figure out the wiki being served
      // - Start with the host header?
      // - TODO Otherwise look for a prefix?
      //if()
      // For testing
      //$requestHost = 'query.wikidata.org'
      // but our requests will be wikidomain/sparql so host is just wiki domain
      $requestHost = 'wikidata.org';

      // 3 - Figure out the internal namespace we want to look at
      $internalHost = 'abcd1234';

      // XXX: testing with live wdqs...
      $requestHost = 'wikiblabla.org';
      $internalHost = 'wikidata.org';

      return [ $internalHost, $requestHost ];
    }

}
