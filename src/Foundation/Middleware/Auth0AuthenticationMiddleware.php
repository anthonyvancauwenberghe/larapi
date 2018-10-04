<?php

namespace Foundation\Middleware;

use Auth0\Login\Contract\Auth0UserRepository;
use Auth0\SDK\Exception\CoreException;
use Auth0\SDK\Exception\InvalidTokenException;
use Closure;

class Auth0AuthenticationMiddleware
{
    protected $auth0Repository;

    /**
     * Auth0AuthenticationMiddleware constructor.
     */
    public function __construct(Auth0UserRepository $auth0Repository)
    {
        $this->auth0Repository = $auth0Repository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth0 = \App::make('auth0');

        $accessToken = $request->bearerToken();
        try {
            $tokenInfo = $auth0->decodeJWT($accessToken);
            $user = $this->auth0Repository->getUserByDecodedJWT($tokenInfo);
            if (!$user) {
                return response()->json(["message" => "Unauthorized user"], 401);
            }

            \Auth::login($user);
        } catch (InvalidTokenException $e) {
            return response()->json(["message" => $e->getMessage()], 401);
        } catch (CoreException $e) {
            return response()->json(["message" => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
