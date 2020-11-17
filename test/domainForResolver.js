const assert = require('assert');
var r = require("./../resolver.js")

describe('resolver', function() {
    it('should return null when given garbage', function() {
        assert.equal(
            r.domainForResolver('foobar', '/foo', {headers:{}}),
            null
        );
    });
    it('First Wikibase URI in query is matched 1', function() {
        assert.equal(
            r.domainForResolver('XXX', '/sparql?query=<http://something><http://somedomain.wiki/entity/>', {headers:{}}),
            "somedomain.wiki"
        );
    });
    it('First Wikibase URI in query is matched 1', function() {
        assert.equal(
            r.domainForResolver('XXX', '/sparql?query=<http://something/><http://somedomain.wiki/entity/>', {headers:{}}),
            "somedomain.wiki"
        );
    });
    it('Referer used when x-qsui header passed, Wikibase URI in query ignored', function() {
        assert.equal(
            r.domainForResolver('XXX', '/sparql?query=<http://someOtherHeader/entity/>', {headers:{"x-qsui": 1, referer: "http://query.mydomain"}}),
            "mydomain"
        );
    });
    it('localhost, origin header is used, when no query URI match', function() {
        assert.equal(
            r.domainForResolver('XXX', '/sparql?query=someQuery', {headers:{origin:"http://localhost:8084"}}),
            "localhost"
        );
    });
    it('localhost, referer header is used, when no query URI match', function() {
        assert.equal(
            r.domainForResolver('XXX', '/sparql?query=someQuery', {headers:{referer:"http://localhost:8084"}}),
            "localhost"
        );
    });
});
