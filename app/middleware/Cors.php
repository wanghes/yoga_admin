<?php
namespace app\middleware;

class Cors
{

    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $origin = $request->header('Origin', '');

        //OPTIONS请求返回204请求
        if ($request->method(true) === 'OPTIONS') {
            $response->code(204);
        }
        $response->header([
            'Access-Control-Allow-Origin'      => $origin,
            'Access-Control-Allow-Methods'     => 'OPTIONS, GET, POST, PUT, PATCH, DELETE, HEAD',
            'Access-Control-Allow-Credentials' => 'true',
             // 'Content-type'                     => 'application/json; charset=UTF-8',
             // 'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With,token,uid,Cookie,authorization',
            'Access-Control-Allow-Headers'     => '*'
        ]);

        return $response;
    }  
}
