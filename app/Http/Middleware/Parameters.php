<?php

namespace App\Http\Middleware;

use Closure;

class Parameters
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
        $field = $request->route('id');

        if(isset($data['id']) && $data['id'] != '') {
            $field = $data['id'];
        }

        $request->request->add(['id' => $field]);

        return $next($request);
    }

}
