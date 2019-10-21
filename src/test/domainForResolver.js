const assert = require('assert');
var r = require("./../resolver.js")

describe('resolver', function() {
    it('should return null when given garbage', function() {
        assert.equal(
            r.domainForResolver('foobar', '/foo', {headers:{}}),
            null
        );
    });
    it('First URI in query is matched', function() {
        assert.equal(
            r.domainForResolver('somedomain.wiki', '/sparql?query=<http://somedomain.wiki/entity/>', {headers:{}}),
            "somedomain.wiki"
        );
    });
    it('URI ignored if not for wikibase 1', function() {
        assert.equal(
            r.domainForResolver('somedomain.wiki', '/sparql?query=<http://something><http://somedomain.wiki/entity/>', {headers:{}}),
            "somedomain.wiki"
        );
    });
    it('URI ignored if not for wikibase 2', function() {
        assert.equal(
            r.domainForResolver('somedomain.wiki', '/sparql?query=<http://something/><http://somedomain.wiki/entity/>', {headers:{}}),
            "somedomain.wiki"
        );
    });
    it('localhost, origin header is used, when no query URI match', function() {
        assert.equal(
            r.domainForResolver('somedomain.wiki', '/sparql?query=someQuery', {headers:{origin:"http://localhost:8084"}}),
            "localhost"
        );
    });
    it('localhost, referer header is used, when no query URI match', function() {
        assert.equal(
            r.domainForResolver('somedomain.wiki', '/sparql?query=someQuery', {headers:{referer:"http://localhost:8084"}}),
            "localhost"
        );
    });
});
