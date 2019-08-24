The idea of the gateway is to transform prefixes sent by clients into the prefixes
actually used inside the backend storage for the query service.
And also to transform the results back to the prefixis that would have been provided
byt the clients.

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

Things to consider:
 - Users using full URIs in the query (not just the headers)
 - Multiple response types? XML? JSON? etc.
 - Probably want to stream in and out if possible for results? Might mean doing
 line replacements rather than parsing the full result in some way?.
