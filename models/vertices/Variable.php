<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 4/30/17
 * Time: 4:05 PM
 */

namespace Models\Vertices;


use vector\ArangoORM\Models\Core\VertexModel;

class Variable extends VertexModel
{
    static $collection = 'variables';
}