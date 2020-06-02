<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Client\UserClient;
use Hyperf\CircuitBreaker\Annotation\CircuitBreaker;

class IndexController extends AbstractController
{
    /**
     * @CircuitBreaker(timeout=0.5, failCounter=3, successCounter=2, fallback="App\Client\UserClient::testFallback")
     * @param UserClient $userClient
     * @return string
     */
    public function index(UserClient $userClient)
    {
        $url = $this->request->input('url', 'http://hf-api.k8s.ynyn.shop?name=v2');

        $res = $userClient->test($url);

        return $res;
    }

    public function test()
    {
        $name = $this->request->input('name', 'nanqi');
        $pSleep = $this->request->input('sleep', 0);
        if ($pSleep > 0) {
            sleep($pSleep);
        }

        $except = $this->request->input('except', 0);
        if ($except == 1) {
            return $this->response->raw('except');
        }

        return [
            "message" => "hello " . $name,
            "sleep" => $pSleep,
            "random" => rand(1, 100000)
        ];
    }
}
