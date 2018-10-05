<?php

namespace Foundation\Abstracts;


/**
 * Class MongoModel
 * @package Foundation\Abstracts
 * @mixin \Eloquent
 */
class MongoModel extends \Jenssegers\Mongodb\Eloquent\Model
{
    protected $connection = 'mongodb';
}
