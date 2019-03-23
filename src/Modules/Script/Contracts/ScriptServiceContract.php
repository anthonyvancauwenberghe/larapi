<?php

namespace Modules\Script\Contracts;

use Modules\Script\Entities\Script;
use Illuminate\Database\Eloquent\Collection;
use Modules\Script\Entities\ScriptConfigProfile;
use Modules\Script\Entities\ScriptExclusivity;
use Modules\Script\Entities\ScriptReview;
use Modules\Script\Entities\ScriptReviewReply;

interface ScriptServiceContract
{
    /**
     * @param $id
     * @return Script|null
     */
    public function resolve($id): ?Script;

    /**
     * @param $userId
     * @return Script[]
     */
    public function getByUserId($userId): Collection;

    /**
     * @param $id
     * @return Script|null
     */
    public function update($id, array $data): ?Script;

    /**
     * @param $id
     * @return Script
     */
    public function create(array $data): Script;

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * @param $id
     * @return Script|null
     */
    public function releaseVersion($id, array $data): Script;

    /**
     * @param $id
     * @return Script|null
     */
    public function publishReview($id, array $data): ScriptReview;

    /**
     * @param $id
     * @return Script|null
     */
    public function publishReviewReply($id, array $data): ScriptReviewReply;

    /**
     * @param $id
     * @return $userId
     */
    public function grantUserExclusivity($id, array $data): ScriptExclusivity;

    /**
     * @param $id
     * @return $userId
     */
    public function removeUserExclusivity($id, $userId): bool;

    /**
     * @param $id
     * @return $userId
     */
    public function updateUserExclusivity($id, array $data): ScriptExclusivity;

    /**
     * @param $id
     * @return array $data
     */
    public function subscribe($id, array $data): ScriptExclusivity;

    /**
     * @param $id
     * @return array $data
     */
    public function unsubscribe($id, array $data): ScriptExclusivity;

    /**
     * @param $id
     * @return ScriptConfigProfile
     */
    public function createConfigProfile($id, array $data) :ScriptConfigProfile;

    /**
     * @param $id
     * @return ScriptConfigProfile
     */
    public function updateConfigProfile($id, array $data) :ScriptConfigProfile;

    /**
     * @param $id
     * @return bool
     */
    public function deleteConfigProfile($id) :bool;
}
