<?php

namespace Modules\Account\Http\Controllers;

use Foundation\Abstracts\Controller\Controller;
use Foundation\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\Account\Contracts\AccountServiceContract;
use Modules\Account\Transformers\AccountTransformer;

class AccountController extends Controller
{
    /**
     * @var AccountServiceContract
     */
    protected $service;

    /**
     * AccountController constructor.
     *
     * @param $service
     */
    public function __construct(AccountServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AccountTransformer::collection($this->service->getByUserId(get_authenticated_user_id()));
    }

    /**
     * Store a newly created Account in storage.
     */
    public function store(Request $request)
    {
        $Account = $this->service->create($this->injectUserId($request));

        return AccountTransformer::resource($Account);
    }

    /**
     * Update a Account.
     *
     * @param Request $request
     */
    public function update(Request $request, $id)
    {
        $Account = $this->service->find($id);

        $this->isOwner($Account);

        $Account = $this->service->update($id, $request->toArray());

        return AccountTransformer::resource($Account);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $Account = $this->service->find($id);

        $this->isOwner($Account);

        return AccountTransformer::resource($Account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Account = $this->service->find($id);

        $this->isOwner($Account);

        $this->service->delete($Account);

        return ApiResponse::deleted();
    }
}
