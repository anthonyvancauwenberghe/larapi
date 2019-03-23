<?php

namespace Modules\Script\Attributes;

interface ScriptSubscriptionAttributes
{
    const ID = 'id';
    const USER_ID = 'user_id';
    const ACTIVE = 'active';
    const LAST_RENEWED = 'renewed_at';
    const BASE_PRICE = 'base_price';
    const RECURRING_PRICE = 'recurring_price';
}
