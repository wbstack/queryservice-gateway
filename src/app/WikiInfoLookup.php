<?php

namespace App;

class WikiInfoLookup
{

    private static $bypassRealQuery = false;

    public static function setNextQueryIsTestBypass()
    {
        self::$bypassRealQuery = true;
    }

    public static function getBackendAndNamespaceForDomain($external)
    {
        if (is_null($external)) {
            return null;
        }

        if (self::$bypassRealQuery) {
            self::$bypassRealQuery = false;
            $lookup = [
                'www.wikidata.org' => 'wdinternal',
                'wikibase-registry.wmflabs.org' => 'registryinternal',
            ];
            if (array_key_exists($external, $lookup)) {
                return $lookup[$external];
            }
            throw new \RuntimeException('could not find');
        }

        // XXX right now the code below is basically a copy and paste from the mediawiki entry point...
        // Should this be reusable?
        // TODO env var for api location and tokens?
        $url = 'http://api:80/backend/wiki/getWikiForDomain?domain=' . urlencode( $external );
        $headers = [
            'X-Backend-Service: backend-service',
            'X-Backend-Token: backend-token',
        ];

        $client = curl_init($url);
        curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($client);
        $wikiInfo = json_decode( $response );
        $wikiInfo = $wikiInfo->data;
        if ( $wikiInfo !== [] && $wikiInfo !== null ) {
            return [
                $wikiInfo->wiki_queryservice_namespace->backend,
                $wikiInfo->wiki_queryservice_namespace->namespace,
            ];
        }

        return null;
    }
}
