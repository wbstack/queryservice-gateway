<?php

use \App\Transformer;
use \App\QueryService;
use \App\HostLookup;

class HeaderForwardTest extends TestCase
{
    public function setUp(): void {
      parent::setUp();
      unset($_SERVER['HTTP_X_BIGDATA_MAX_QUERY_MILLIS']);
      unset($_SERVER['HTTP_X_BIGDATA_READ_ONLY']);
      unset($_SERVER['HTTP_X_BIGDATA_SOMETHING']);
    }

    public function test_expectedAreCorrectlyForwarded()
    {
        QueryService::setNextQueryIsTestBypass();
        HostLookup::setNextQueryIsTestBypass();

        $expectedHeaders = [
          'X-BIGDATA-MAX-QUERY-MILLIS: 6000',
          'X-BIGDATA-READ-ONLY: yes',
        ];

        $_SERVER['HTTP_HOST'] = 'someHost';
        $_SERVER['HTTP_X_BIGDATA_MAX_QUERY_MILLIS'] = 6000;
        $_SERVER['HTTP_X_BIGDATA_READ_ONLY'] = "yes";
        QueryService::setNextResponse('someResponse');

        $response = $this->call('GET', '/sparql?query=' . urlencode( 'someQuery' ) );

        $this->assertEquals( $expectedHeaders, QueryService::$lastHeaders );
    }

    public function test_emptyAlsoForwardsEmpty()
    {
        QueryService::setNextQueryIsTestBypass();
        HostLookup::setNextQueryIsTestBypass();

        $expectedHeaders = [];

        $_SERVER['HTTP_HOST'] = 'someHost';
        QueryService::setNextResponse('someResponse');

        $response = $this->call('GET', '/sparql?query=' . urlencode( 'someQuery' ) );

        $this->assertEquals( $expectedHeaders, QueryService::$lastHeaders );
    }

    public function test_extraIsNotForwarded()
    {
        QueryService::setNextQueryIsTestBypass();
        HostLookup::setNextQueryIsTestBypass();

        $expectedHeaders = [
          'X-BIGDATA-MAX-QUERY-MILLIS: 6000',
          'X-BIGDATA-READ-ONLY: yes',
        ];

        $_SERVER['HTTP_HOST'] = 'someHost';
        $_SERVER['HTTP_X_BIGDATA_MAX_QUERY_MILLIS'] = 6000;
        $_SERVER['HTTP_X_BIGDATA_READ_ONLY'] = "yes";
        $_SERVER['HTTP_X_BIGDATA_SOMETHING'] = "lala";
        QueryService::setNextResponse('someResponse');

        $response = $this->call('GET', '/sparql?query=' . urlencode( 'someQuery' ) );

        $this->assertEquals( $expectedHeaders, QueryService::$lastHeaders );
    }
}
