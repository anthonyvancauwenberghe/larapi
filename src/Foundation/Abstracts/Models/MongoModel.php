<?php

namespace Foundation\Abstracts;

/**
 * Class MongoModel.
 *
 * @mixin \Eloquent
 */
class MongoModel extends \Jenssegers\Mongodb\Eloquent\Model
{
    protected $connection = 'mongodb';

}
