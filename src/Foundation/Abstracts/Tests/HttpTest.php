<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.10.18
 * Time: 21:56.
 */

namespace Foundation\Abstracts\Tests;

use Illuminate\Foundation\Testing\TestResponse;

abstract class HttpTest extends \Foundation\Abstracts\Tests\TestCase
{

    protected function decodeHttpResponse($content, $unwrap = true)
    {
        if ($content instanceof TestResponse) {
            $content = $content->content();
        }

        if ($unwrap) {
            return json_decode($content, true)['data'] ?? json_decode($content, true);
        }

        return json_decode($content, true);
    }

    protected function http(string $method, string $route, array $payload = [], array $headers = []) : \Illuminate\Foundation\Testing\TestResponse
    {
        if(!in_array($method,['GET','POST','PATCH','DELETE','PUT']))
            throw new \HttpRequestMethodException("Invalid Http Method");

        return $this->json($method, env('API_URL').$route, $payload, $headers);
    }
}
