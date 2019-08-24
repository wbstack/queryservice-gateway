<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->get('/sparql', function ( \Illuminate\Http\Request $request ) use ( $router ) {
    // 1 - Get the query
    $query = $request->input('query');

    // 2 - Figure out the wiki being served
    // - Start with the host header?
    // - TODO Otherwise look for a prefix?
    $requestHost = $_SERVER['HTTP_HOST'];
    // For testing
    //$requestHost = 'query.wikidata.org'
    // but our requests will be wikidomain/sparql so host is just wiki domain
    $requestHost = 'wikidata.org';

    // 3 - Figure out the internal namespace we want to look at
    $internalHost = 'abcd1234';

    // XXX: testing with live wdqs...
    $requestHost = 'wikiblabla.org';
    $internalHost = 'wikidata.org';

    // 4 - Transform request
    // TOOD how should this handle www.? and http vs https?
    $query = preg_replace(
      "/(https?:\/\/)(www\.)?($requestHost)\/(entity|prop|reference|value|wiki)/",
      '$1$2' . $internalHost . '/$4',
      $query
    );

    // 5 - Make request to inner / other sparql endpoint
    // TODO forward on header
    $ch = curl_init("https://query.wikidata.org/sparql?query=" . urlencode($query));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch,CURLOPT_USERAGENT,'Addshore OpenCura wdqs-gateway testing');
    $data = curl_exec($ch);
    curl_close($ch);

    // 6 - Transform response
    $data = str_replace($internalHost, $requestHost, $data);

    // 7 - Return response
    return $data;
});
