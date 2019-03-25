<?php

namespace Modules\Script\Dtos;

use Spatie\DataTransferObject\DataTransferObject;

class CreateScriptDto extends DataTransferObject
{
    public $user_id;
    public $name;
    public $description;
    public $short_description;
    public $game;
    public $public;
    public $base_price;
    public $recurring_price;
    public $git_access;
    public $repository_url;
    public $public_key;
    public $private_key;
    public $tags;
}
