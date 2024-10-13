<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class AuthenticateWithToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        // Mengecek token yang ada di database (hashed)
        $user = User::where('api_token', $token)->first();
        // dd($user);
        if (!$user || !$token) {
            // Jika user tidak ditemukan, kirim respon Unauthorized
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
