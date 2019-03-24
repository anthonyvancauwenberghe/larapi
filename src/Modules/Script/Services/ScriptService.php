<?php

namespace Modules\Script\Services;

use Modules\Script\Dtos\UserExclusivityGrantDto;
use Modules\Script\Dtos\UserExclusivityUpdateDto;
use Modules\Script\Entities\Script;
use Modules\Script\Entities\ScriptConfigProfile;
use Modules\Script\Entities\ScriptExclusivity;
use Modules\Script\Entities\ScriptRelease;
use Modules\Script\Entities\ScriptReview;
use Modules\Script\Entities\ScriptReviewReply;
use Modules\Script\Events\ScriptWasCreatedEvent;
use Modules\Script\Events\ScriptWasUpdatedEvent;
use Modules\Script\Events\ScriptWasDeletedEvent;
use Modules\Script\Contracts\ScriptServiceContract;
use Illuminate\Database\Eloquent\Collection;
use Modules\Script\Exceptions\ScriptAlreadyReviewedException;
use Modules\Script\Exceptions\ScriptReviewReplyAlreadyExistsException;
use Modules\Script\Exceptions\UserAlreadyHasExclusivityException;
use Modules\Script\Exceptions\UserDoesNotHaveExclusivityException;
use Modules\Script\Guards\ScriptAlreadyReviewedGuard;
use Modules\Script\Support\Version;

class ScriptService implements ScriptServiceContract
{
    /**
     * @param $id
     * @return Script|null
     */
    public function resolve($id): ?Script
    {
        if ($id instanceof Script) {
            return $id;
        }

        return Script::find($id);
    }

    /**
     * @param $userId
     * @return Script[]
     */
    public function getByUserId($userId): Collection
    {
        return Script::where('user_id', $userId)->get();
    }

    /**
     * @param $id
     * @return Script|null
     */
    public function update($id, array $data): Script
    {
        $script = $this->resolve($id);
        $script->update($data);
        event(new ScriptWasUpdatedEvent($script));

        return $script;
    }

    /**
     * @param $id
     * @return Script
     */
    public function create(array $data): Script
    {
        $data[Script::AUTHOR_ID] = get_authenticated_user_id();
        $script = Script::create($data);
        event(new ScriptWasCreatedEvent($script));
        return $script;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        $script = $this->resolve($id);
        $deleted = $script->delete();
        if ($deleted)
            event(new ScriptWasDeletedEvent($script));
        return $deleted;
    }

    public function releaseVersion($id, array $data): Script
    {
        $script = $this->resolve($id);
        $releases = $script->releases()->get();
        if ($releases->isEmpty())
            $lastVersion = "0.0.0";
        else
            $lastVersion = $releases->last()->version;

        $version = new Version($lastVersion);

        switch ($data['type']) {
            case 'MAJOR':
                $version->incrementMajor();
                break;
            case 'MINOR':
                $version->incrementMinor();
                break;
            case 'PATCH':
                $version->incrementPatch();
                break;
            default:
                throw new \Exception("Invalid release type specified");
        }

        //TODO ADD CONFIG TEMPLATE
        $script->releases()->create([
            ScriptRelease::VERSION => $version->__toString(),
            ScriptRelease::TYPE => $data['type'],
            ScriptRelease::CHANGELOG => $data['changelog']
        ]);

        return $script;
    }

    public function publishReview($id, array $data): ScriptReview
    {
        $script = $this->resolve($id);

        guard(new ScriptAlreadyReviewedGuard($script));

        //TODO CHECK IF THE USER IS ACTUALLY SUBSCRIBED TO THE SCRIPT
        //TODO PREVENT THE AUTHOR FROM REVIEWING HIS OWN SCRIPT

        $review = $script->reviews()->create([
            ScriptReview::REVIEWER_ID => get_authenticated_user_id(),
            ScriptReview::VERSION => $script->releases->last()->version,
            ScriptReview::MESSAGE => $data['message']
        ]);

        return $review;
    }

    public function publishReviewReply($id, array $data): ScriptReviewReply
    {
        $script = $this->resolve($id);

        $review = $script->reviews()->withTrashed()->find($data['review_id']);

        if (!isset($review)) {
            throw new ScriptAlreadyReviewedException();
        }

        if ($review->reply !== null) {
            throw new ScriptReviewReplyAlreadyExistsException();
        }

        $reply = $review->reply()->create([
            ScriptReviewReply::REPLIER_ID => get_authenticated_user_id(),
            ScriptReviewReply::MESSAGE => $data['message']
        ]);

        return $reply;
    }

    public function grantUserExclusivity($id, UserExclusivityGrantDto $data): ScriptExclusivity
    {
        $script = $this->resolve($id);
        if ($script->exclusivity()->where(ScriptExclusivity::USER_ID, $data->user_id ?? null)->first() !== null) {
            throw new UserAlreadyHasExclusivityException();
        }

        return $script->exclusivity()->create($data->toArray());
    }

    public function removeUserExclusivity($id, $userId): bool
    {
        $script = $this->resolve($id);

        if (($exclusivity = $script->exclusivity()->where(ScriptExclusivity::USER_ID, $userId)->first()) === null) {
            throw new UserDoesNotHaveExclusivityException();
        }

        return $exclusivity->delete();
    }

    public function updateUserExclusivity($id, UserExclusivityUpdateDto $data): ScriptExclusivity
    {
        $script = $this->resolve($id);

        if (($exclusivity = $script->exclusivity()->where(ScriptExclusivity::USER_ID, $data->user_id ?? null)->first()) === null) {
            throw new UserDoesNotHaveExclusivityException();
        }

        $exclusivity->update($data->toArray());

        return $exclusivity;
    }

    public function subscribe($id, array $data): ScriptExclusivity
    {
        // TODO: Implement subscribe() method.
    }

    public function unsubscribe($id, array $data): ScriptExclusivity
    {
        // TODO: Implement unsubscribe() method.
    }

    public function createConfigProfile($id, array $data): ScriptConfigProfile
    {
        // TODO: Implement createConfigProfile() method.
    }

    public function updateConfigProfile($id, array $data): ScriptConfigProfile
    {
        // TODO: Implement updateConfigProfile() method.
    }

    public function deleteConfigProfile($id): bool
    {
        // TODO: Implement deleteConfigProfile() method.
    }


}
