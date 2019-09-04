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
     */
    public function testStuff($testName, $settings, $queryIn, $queryOut, $responseIn, $responseOut)
    {
        $_SERVER['HTTP_HOST'] = $settings[0];
        QueryService::setExpectedQuery($queryOut);
        QueryService::setNextResponse($responseIn);

        $response = $this->call('GET', '/sparql?query=' . urlencode( $queryIn ) );

        // if($response->status() !== '200') {
        //   var_dump($response->getContent());die();
        // }
        //$this->assertEquals(200, $response->status());
        $this->assertEquals( $responseOut, $response->getContent() );
    }
}
