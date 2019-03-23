<?php

namespace Modules\Script\Attributes;

interface ScriptAttributes
{
    const ID = '_id';
    const AUTHOR_ID = 'user_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const SHORT_DESCRIPTION = 'short_description';
    const GAME = 'game';
    const PUBLIC = 'public';
    const BASE_PRICE = 'base_price';
    const RECURRING_PRICE = 'recurring_price';
    const GIT_ACCESS = 'git_access';
    const REPOSITORY_URL = 'repository_url';
    const PUBLIC_KEY = 'public_key';
    const PRIVATE_KEY = 'private_key';
    const VERSIONS = 'versions';
    const ACCESS = 'access';
    const TAGS = 'tags';
    const SUBSCRIPTIONS = 'subscriptions';
    const REVIEWS = 'reviews';
}
