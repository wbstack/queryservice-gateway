<?php

namespace App;

class RequestParser{

    private static function getInternalHostFromExternal( $external ) {
      $lookup = [
        'www.wikidata.org' => 'wdinternal',
      ];
      if( array_key_exists($external, $lookup) ) {
        return $lookup[$external];
      }
      throw new \RuntimeException('could not find');
    }

    public static function getExternalInternalHosts( $query, $serverArray ) {
      if( array_key_exists( 'HTTP_HOST', $_SERVER ) && $_SERVER['HTTP_HOST'] ) {
        $requestHost = $_SERVER['HTTP_HOST'];
        // TODO could use strpos instead of regex initially?
        $preg = preg_match( '/(?:.*)PREFIX(?:.*):(?:.*)<(https?:\/\/(' . $requestHost . '))\/(?:entity|prop|reference|value|wiki)\/?(?:.*)>/i', $query, $matches );
        if( $preg ) {
          return[ $matches[2], self::getInternalHostFromExternal( $matches[2] ) ];
        }
      }

      return null;

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
