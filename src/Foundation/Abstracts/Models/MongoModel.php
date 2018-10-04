<?php

namespace Foundation\Abstracts;

class MongoModel extends \Jenssegers\Mongodb\Eloquent\Model
{
    protected $connection = 'mongodb';
}
