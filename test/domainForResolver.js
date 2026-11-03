const assert = require('assert');
var r = require("./../resolver.js")

describe('resolver.domainForResolver', function() {
    it('nothing', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{}}),
            null
        );
    });
    it('host', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{'host': 'somexhost'}}),
            "somexhost"
        );
    });
    it('host sub domain not removed', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{'host': 'somename.somexhost'}}),
            "somename.somexhost"
        );
    });
    it('host www removed', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{'host': 'www.somexhost'}}),
            "somexhost"
        );
    });
    it('host port removed for API domain lookup', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{'host': 'realwiki.localhost:8001'}}),
            "realwiki.localhost"
        );
    });
    it('host www and port removed', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{'host': 'www.realwiki.localhost:8001'}}),
            "realwiki.localhost"
        );
    });
    it('forwarded host port removed and takes precedence', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{
                'host': 'query-gateway',
                'x-forwarded-host': 'www.realwiki.localhost:8001'
            }}),
            "realwiki.localhost"
        );
    });
    it('x-forwarded-host', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{'x-forwarded-host': 'somexhost'}}),
            "somexhost"
        );
    });
    it('x-forwarded-host sub domain not removed', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{'x-forwarded-host': 'somename.somexhost'}}),
            "somename.somexhost"
        );
    });
    it('x-forwarded-host www removed', function() {
        assert.strictEqual(
            r.domainForResolver({headers:{'x-forwarded-host': 'www.somexhost'}}),
            "somexhost"
        );
    });
});
