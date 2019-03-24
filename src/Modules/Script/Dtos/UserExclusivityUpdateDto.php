<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 24.03.19
 * Time: 18:19
 */

namespace Modules\Script\Dtos;


use Spatie\DataTransferObject\DataTransferObject;

class UserExclusivityUpdateDto extends DataTransferObject
{
    /** @var int */
    public $user_id;

    /** @var double */
    public $base_price;

    /** @var double */
    public $recurring_price;
}
