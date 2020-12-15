<?php

namespace App\Http\Middleware;

use ArrayObject;
use Closure;

class JwtCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!isJwtCustomer()) {
            $message = __('message.token_invalid');
            $data = new ArrayObject();
            return apiResponse(0, $message, $data);
        }
        return $next($request);
    }
}
