<?php

use \App\WikiInfoLookup;
use \App\QueryService;
use \App\RequestParser;

$router->get('/sparql', function (\Illuminate\Http\Request $request) use ($router) {
    $allInputs = $request->input();
    if(!array_key_exists( 'query', $allInputs )) {
        response()->json('No query found.', 400);
    }
    $query = $allInputs['query'];
    unset($allInputs['query']);

    $external = RequestParser::getExternalHost($query);

    if (is_null($external)) {
        response()->json('Could not match request to a backend. 1.', 400);
    }

    $wikiDetails = WikiInfoLookup::getBackendAndNamespaceForDomain($external);
    if($wikiDetails === null) {
        response()->json('Could not match request to a backend. 2.', 400);
    }

    $queryHost = $wikiDetails[0];
    $queryNamespace = $wikiDetails[1];
    $queryLocation = $queryHost . '/bigdata/namespace/' . $queryNamespace . '/sparql';

    // Get and pass along any related X-BIGDATA headers we want to
    // TODO maybe dynamically check for all X-BIGDATA headers?
    // TODO there are probably even more headers to pass through too...
    $extraCurlHeaders = [];
    if (isset($_SERVER['HTTP_X_BIGDATA_MAX_QUERY_MILLIS'])) {
        $extraCurlHeaders[] = 'X-BIGDATA-MAX-QUERY-MILLIS: ' . $_SERVER['HTTP_X_BIGDATA_MAX_QUERY_MILLIS'];
    }
    if (isset($_SERVER['HTTP_X_BIGDATA_READ_ONLY'])) {
        $extraCurlHeaders[] = 'X-BIGDATA-READ-ONLY: ' . $_SERVER['HTTP_X_BIGDATA_READ_ONLY'];
    }
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        $extraCurlHeaders[] = 'ACCEPT: ' . $_SERVER['HTTP_ACCEPT'];
    }

    // Make the query
    list($data, $responseHeaders) = QueryService::query($queryLocation, $query, $extraCurlHeaders, $allInputs);

    // TODO XXX THIS IS REALLY EVIL
    // now that we are not query rewriting, we should just use a proxy or something...
    if(!isset($responseHeaders['content-type'])) {
        response()->json('No content type retrieved', 400);
    }

    // Return the response
    return response($data, 200)
        ->header('Content-Type', $responseHeaders['content-type']);
});
