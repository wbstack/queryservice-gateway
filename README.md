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
 - If this gateway sits behind the current wdqs-proxy then we need to pass on headers
 - If no headers are passed (by the user or UI, how to pick defaults?)

Test URL for docker-compose setup:

view-source:http://localhost:8070/sparql?query=%20PREFIX%20rdf%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0A%20PREFIX%20xsd%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0A%20PREFIX%20ontolex%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2Fns%2Flemon%2Fontolex%23%3E%0A%20PREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0A%20PREFIX%20rdfs%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F2000%2F01%2Frdf-schema%23%3E%0A%20PREFIX%20owl%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F2002%2F07%2Fowl%23%3E%0A%20PREFIX%20skos%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F2004%2F02%2Fskos%2Fcore%23%3E%0A%20PREFIX%20schema%3A%20%3Chttp%3A%2F%2Fschema.org%2F%3E%0A%20PREFIX%20cc%3A%20%3Chttp%3A%2F%2Fcreativecommons.org%2Fns%23%3E%0A%20PREFIX%20geo%3A%20%3Chttp%3A%2F%2Fwww.opengis.net%2Font%2Fgeosparql%23%3E%0A%20PREFIX%20prov%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2Fns%2Fprov%23%3E%0A%20PREFIX%20wikibase%3A%20%3Chttp%3A%2F%2Fwikiba.se%2Fontology%23%3E%0A%20PREFIX%20wdata%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fwiki%2FSpecial%3AEntityData%2F%3E%0A%20PREFIX%20bd%3A%20%3Chttp%3A%2F%2Fwww.bigdata.com%2Frdf%23%3E%0A%20%0A%20PREFIX%20wd%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fentity%2F%3E%0A%20PREFIX%20wdt%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Fdirect%2F%3E%0A%20PREFIX%20wdtn%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Fdirect-normalized%2F%3E%0A%20%0A%20PREFIX%20wds%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fentity%2Fstatement%2F%3E%0A%20PREFIX%20p%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2F%3E%0A%20PREFIX%20wdref%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Freference%2F%3E%0A%20PREFIX%20wdv%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fvalue%2F%3E%0A%20PREFIX%20ps%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Fstatement%2F%3E%0A%20PREFIX%20psv%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Fstatement%2Fvalue%2F%3E%0A%20PREFIX%20psn%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Fstatement%2Fvalue-normalized%2F%3E%0A%20PREFIX%20pq%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Fqualifier%2F%3E%0A%20PREFIX%20pqv%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Fqualifier%2Fvalue%2F%3E%0A%20PREFIX%20pqn%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Fqualifier%2Fvalue-normalized%2F%3E%0A%20PREFIX%20pr%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Freference%2F%3E%0A%20PREFIX%20prv%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Freference%2Fvalue%2F%3E%0A%20PREFIX%20prn%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Freference%2Fvalue-normalized%2F%3E%0A%20PREFIX%20wdno%3A%20%3Chttp%3A%2F%2Fwww.wikiblabla.org%2Fprop%2Fnovalue%2F%3E%0A%0A%20PREFIX%20hint%3A%20%3Chttp%3A%2F%2Fwww.bigdata.com%2FqueryHints%23%3E%0A%0A%23Cats%0ASELECT%20%3Fitem%20%3FitemLabel%20%0AWHERE%20%0A%7B%0A%20%20%3Fitem%20wdt%3AP31%20wd%3AQ146.%0A%20%20SERVICE%20wikibase%3Alabel%20%7B%20bd%3AserviceParam%20wikibase%3Alanguage%20%22en%2Cen%22.%20%7D%0A%7D
