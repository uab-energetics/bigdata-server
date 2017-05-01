<?php
namespace Tests\API;

use Models\Edges\Assignment;

/**
 * User: chris
 * Date: 5/1/17
 * Time: 1:24 AM
 */
class AssignmentTest extends \Tests\BaseTestCase
{
    private $test_user_key;
    private $test_paper_key;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->withMiddleware = false;

        $this->test_user_key = \DB\DB::create( 'users', []);
        $this->test_paper_key = \DB\DB::create('papers', []);
    }


    public function testCreateAssignment(){
        $response = $this->runApp("POST", "/assignments",
            [
                "userKey"   =>  $this->test_user_key,
                "paperKey"  =>  $this->test_paper_key
            ]);

        self::assertEquals(200, $response->getStatusCode());

        return [
            'userKey'   =>  $this->test_user_key
        ];
    }

    /**
     * @depends testCreateAssignment
     * @param $given
     * @return mixed
     */
    public function testGetAssignments( $given ){
        $key = $given['userKey'];
        $response = $this->runApp("GET", "/users/$key/assignments");

        self::assertEquals(200, $response->getStatusCode());

        $assignments = json_decode((string)$response->getBody(), true);
        self::assertTrue( count($assignments) > 0 );

        return [
            "assignmentKey" => $assignments[0]["_key"]
        ];
    }

    /**
     * @depends testGetAssignments
     * @param $given
     */
    public function testUpdateAssignments( $given ){
        $key = $given["assignmentKey"];

        $response = $this->runApp("PUT", "/assignments/$key",
            [
                "completion" => 999
            ]);

        self::assertEquals(200, $response->getStatusCode());

        $assignment = Assignment::retrieve($key);
        self::assertEquals(999, $assignment->get("completion"));
    }

    /**
     * @depends testGetAssignments
     * @param $given
     */
    public function testGetAssignment( $given ){
        $key = $given["assignmentKey"];
        $response = $this->runApp("GET", "/assignments/$key");
        var_dump((string)$response->getBody());
        self::assertEquals(200, $response->getStatusCode());
    }
}