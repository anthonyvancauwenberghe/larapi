<?php

namespace Modules\User\Http\Controllers;

use Foundation\Abstracts\Controller\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\User\Contracts\UserServiceContract;
use Modules\User\Entities\User;
use Modules\User\Services\UserService;

class UserController extends Controller
{

    /**
     * @var UserService
     */
    protected $service;

    /**
     * UserController constructor.
     * @param $service
     */
    public function __construct(UserServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return \response()->json($this->service->all());
    }

    /**
     * Show the specified resource.
     *
     * @return JsonResponse
     */
    public function show()
    {
        return \response()->json(get_authenticated_user()->toArray());
    }

    /**
     * Update the roles in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->service->assignRole($id, $request->roles);
        return \response()->json('success');
    }
}
