<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:38.
 */

namespace Modules\Application\Transformers;

use Foundation\Abstracts\Transformers\Transformer;
use Foundation\Exceptions\Exception;
use Modules\Application\Entities\Application;
use Modules\Machine\Transformers\MachineTransformer;
use Modules\User\Transformers\UserTransformer;

class ApplicationTransformer extends Transformer
{
    public $available = [
        'user'    => UserTransformer::class,
        'machine' => MachineTransformer::class,
    ];

    /**
     * Transform the resource into an array.
     *
     * @param Application $app
     * @throws Exception
     *
     * @return array
     */
    public function transformResource($app)
    {
        switch ($app->type) {
            case 'OSRS':
                return $this->transformOSRS($app);
            default:
                throw new Exception('Could not identity application type');
        }
    }

    protected function transformOSRS($app)
    {
        return [
            'id' => $app->id,
            'alias' => $app->alias ?? $app->credentials['username'],
            'performance_mode' => array_random([
                'EXTREME',
                'MEDIUM',
                'DISABLED',
            ]),
            'banned_at' => $app->banned_at,
        ];
    }
}
