<?php
namespace Tests\Database;

use DB\DB;
use Tests\BaseTestCase;

/**
 * Created by PhpStorm.
 * User: chris
 * Date: 4/29/17
 * Time: 12:45 PM
 */
class ConnectionTest extends BaseTestCase
{
    public function testConnection(){
        $connection = DB::getConnection();

        self::assertTrue(  $connection !== false  );
    }
}