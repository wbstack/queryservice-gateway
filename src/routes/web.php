<?php

use \App\Transformer;
use \App\QueryService;
use \App\RequestParser;

$router->get('/sparql', function ( \Illuminate\Http\Request $request ) use ( $router ) {
    $query = $request->input('query');
    list( $external, $internal ) = RequestParser::getExternalInternalHosts( $query, $_SERVER );
    if(is_null($external) || is_null($internal)) {
      return null;
    }
    $query = Transformer::transformQuery( $query, $internal, $external );
    $data = QueryService::query( $query );
    $data = Transformer::transformResponse( $data, $internal, $external );
    return $data;
});
