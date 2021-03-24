const assert = require('assert');
var r = require("./../resolver.js")

describe('resolver.defaultResolver', function() {
    it('nothing', function() {
        assert.strictEqual(
            r.defaultResolver('', '', {headers:{}}),
            null
        );
    });
    // TODO make that target backend testable or mockable somehow :)
    // it('domain ', function() {
    //     assert.strictEqual(
    //         r.defaultResolver('', '', {headers:{'host':'adomain'}}),
    //         null
    //     );
    // });
});
