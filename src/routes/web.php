<?php

use \App\Transformer;
use \App\QueryService;
use \App\RequestParser;

$router->get('/sparql', function ( \Illuminate\Http\Request $request ) use ( $router ) {
    // Get and transform the query
    $query = $request->input('query');
    list( $external, $internal ) = RequestParser::getExternalInternalHosts( $query, $_SERVER );
    if(is_null($external) || is_null($internal)) {
      response()->json('Could not match request to a backend.', 400);
    }
    $query = Transformer::transformQuery( $query, $internal, $external );

    // Get and pass along any related X-BIGDATA headers we want to
    // TODO maybe dynamicaly check for all X-BIGDATA headers?
    $extraCurlHeaders = [];
    if( isset($_SERVER['HTTP_X_BIGDATA_MAX_QUERY_MILLIS']) ) {
      $extraCurlHeaders[] = 'X-BIGDATA-MAX-QUERY-MILLIS: ' . $_SERVER['HTTP_X_BIGDATA_MAX_QUERY_MILLIS'];
    }
    if( isset($_SERVER['HTTP_X_BIGDATA_READ_ONLY']) ) {
      $extraCurlHeaders[] = 'X-BIGDATA-READ-ONLY: ' . $_SERVER['HTTP_X_BIGDATA_READ_ONLY'];
    }

    // Make the query
    $data = QueryService::query( $query, $extraCurlHeaders );

    // Transform the response
    $data = Transformer::transformResponse( $data, $internal, $external );

    // Return the response
    return $data;
});
