The idea of the gateway is to transform some URIs sent by clients into the internal URIs
actually used for the backend storage for the query service.
And also to transform the results back to the URIs that would have been provided
by the clients.

For example:

Client queries with:
PREFIX wd: <http://www.wikidata.org/entity/>

Gateway will transfer this to:
PREFIX wd: <http://abcd4567ksda/entity/>

Where the domain is the dbname / storage key for the wiki.

One the way back the same thing will happen with results.?

Response is:
"bindings" : [ {
  "item" : {
    "type" : "uri",
    "value" : "http://abcd4567ksda/entity/Q378619"
  }

We give the client:
"bindings" : [ {
  "item" : {
    "type" : "uri",
    "value" : "http://www.wikidata.org/entity/Q378619"
  }

Future things to think about:
 - Probably want to stream in and out if possible for results? Might mean doing
 line replacements rather than parsing the full result in some way?.
 - If this gateway sits behind the current wdqs-proxy then we need to pass on headers
 - Maybe want to look at https://www.w3.org/TR/rdf-sparql-query/#rIRI_REF for the regex?
