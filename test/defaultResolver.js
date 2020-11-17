const assert = require('assert');
var r = require("./../resolver.js")

describe('resolver', function() {
    it('should return null when given garbage', function() {
        assert.equal(
            r.defaultResolver('foobar', '/foo', {headers:{}}),
            null
        );
    });
});
