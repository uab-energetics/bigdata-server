<?php

use Models\Vertices\Paper;

/**
 * Created by PhpStorm.
 * User: chris
 * Date: 4/30/17
 * Time: 12:00 AM
 */
class PaperTest extends \Tests\BaseTestCase
{
    public function testCreate(){
        $paper_data = [
            'title' =>  'Test Paper',
            'pmcID' =>  '12345',
            'masterEncoding' => []
        ];
        $paper = Paper::create($paper_data);

        $from_DB = Paper::retrieve( $paper->key() );

        self::assertInstanceOf(Paper::class, $from_DB);
        return $from_DB;
    }

    /**
     * @depends testCreate
     * @var paper Paper
     */
    public function testMerge ($paper) {
        $assignments = json_decode( file_get_contents(__DIR__ . "/../../../data/assignments.json") , true);
        foreach ($assignments as $i => $assignment) {
            echo "\nMerging user ".$assignment['_key'];
            $oldMasterEncoding = $paper->masterEncoding;
            $paper->merge($assignment);
            echo $paper->masterEncoding == $oldMasterEncoding ? " - without change" : " - with change";
        }
        $assignment = $assignments[ count($assignments) - 1 ];
        echo "\nMerging user ".$assignment['_key'];
        $oldMasterEncoding = $paper->masterEncoding;
        $paper->merge($assignment);
        echo $paper->masterEncoding == $oldMasterEncoding ? " - without change" : " - with change";
//        echo PHP_EOL.json_encode($paper->get('masterEncoding'));
    }
}