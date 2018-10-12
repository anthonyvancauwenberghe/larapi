<?php

namespace Foundation\Abstracts;

/**
 * Class MongoModel.
 *
 * @mixin \Eloquent
 */
abstract class MongoModel extends \Jenssegers\Mongodb\Eloquent\Model
{
    protected $connection = 'mongodb';
}
