<?php

namespace App\Http\Middleware;

use App\Models\Cliente;
use Closure;

class CheckCliente
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
        if(auth()->user()->hasRole('cliente')) {
            $cliente = Cliente::where('identificacion', auth()->user()->identificacion)->first();
            return redirect()->route('ver-cliente', ['id' => $cliente->id]);
        }

        return $next($request);
    }
}
