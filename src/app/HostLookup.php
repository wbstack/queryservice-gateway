<?php

namespace App;

class HostLookup {

  private static $bypassRealQuery = false;

  public static function setNextQueryIsTestBypass() {
    self::$bypassRealQuery = true;
  }

  public static function getInternalHostFromExternal( $external ) {
    if(self::$bypassRealQuery) {
      self::$bypassRealQuery = false;
      $lookup = [
        'www.wikidata.org' => 'wdinternal',
        'wikibase-registry.wmflabs.org' => 'registryinternal',
      ];
      if( array_key_exists($external, $lookup) ) {
        return $lookup[$external];
      }
      throw new \RuntimeException('could not find');
    }

    // TODO real implemenmtation looking up from API..
  }
}
