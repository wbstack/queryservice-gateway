<?php

use \App\Transformer;
use \App\QueryService;

class TheThingsTest extends TestCase
{

    public function provideTestFiles(){
      $toReturn = [];

      $files = glob(__DIR__ . '/data/*', GLOB_BRACE);
      foreach($files as $file) {
          if(strpos($file, 'skipme.') !== false) {
            continue;
          }
          $content = file_get_contents( $file );
          $parts = explode("--------", $content);
          $parts = array_map('trim', $parts);
          $settings = explode("\n", $parts[1]);
          $settings = array_map('trim', $settings);
          $toReturn[] = [basename($file), $settings, $parts[2], $parts[3], $parts[4], $parts[5]];
      }
      return $toReturn;
    }

    /**
     * @dataProvider provideTestFiles
     * @param $testName Name of the file for the test being runner
     * @param $settings [] of settings to be set, right now just HTTP_HOST
     * @param $queryIn The query provided by the user
     * @param $queryOut Expected query after modifications
     * @param $responseIn Mock response from the query service
     * @param $responseOut Expected query respone after modifications
     */
    public function testStuff($testName, $settings, $queryIn, $queryOut, $responseIn, $responseOut)
    {
        $_SERVER['HTTP_HOST'] = $settings[0];
        QueryService::setNextResponse($responseIn);

        $response = $this->call('GET', '/sparql?query=' . urlencode( $queryIn ) );

        //$this->assertEquals(200, $response->status());
        $this->assertEquals( $queryOut, QueryService::$lastQuery );
        $this->assertEquals( $responseOut, $response->getContent() );
    }
}
