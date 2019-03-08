<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 29.10.18
 * Time: 09:38.
 */

namespace Modules\Schedule\Transformers;

use Foundation\Abstracts\Transformers\Transformer;
use Foundation\Exceptions\Exception;
use Modules\Schedule\Entities\Schedule;
use Modules\Machine\Transformers\MachineTransformer;
use Modules\User\Transformers\UserTransformer;

class ScheduleTransformer extends Transformer
{
    public $available = [
        'user' => UserTransformer::class
    ];

    /**
     * Transform the resource into an array.
     *
     * @throws Exception
     *
     * @return array
     */
    public function transformResource(Schedule $schedule)
    {
        return [
            'id' => $schedule->id,
            'alias' => $schedule->alias,
            'timezone' => $schedule->timezone,
            'week_days' => $schedule->week_days,
            'exceptions' => $schedule->exceptions,
            'randomize' => $schedule->randomize,
            'times' => $schedule->times, //$this->buildTimes($schedule->week_days),
            'accounts' => [],
            'created_at' => $schedule->created_at,
            'updated_at' => $schedule->updated_at
        ];
    }

    public function buildTimes($weekDays): array
    {
        return [];
    }
}
