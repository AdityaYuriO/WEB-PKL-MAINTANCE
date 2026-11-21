<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HakAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth()->check() && $request->is('login')) {
            return redirect()->route('dashboard.' . auth()->user()->role);
        }

        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        // Redirect berdasarkan role jika user tidak punya akses
        switch (auth()->user()->role) {
            case 'hubin':
                return redirect('/dashboard/hubin');
            case 'siswa':
                return redirect('/dashboard/siswa');
            case 'iduka':
                return redirect('/dashboard/iduka');
            case 'kaprog':
                return redirect('/dashboard/kaprog');
            case 'persuratan':
                return redirect('/dashboard/persuratan');
            case 'guru':
                return redirect('/dashboard/guru');
            case 'ppkl':
                return redirect('/dashboard/ppkl');
            case 'orangtua':
                return redirect('/dashboard/orangtua');
            case 'psekolah':
                return redirect('/dashboard/psekolah');
            case 'kepsek':
                return redirect('/dashboard/kepsek');
            case 'pendamping':
                return redirect('/dashboard/pendamping');
            default:
                return redirect('/');
        }
    }
}
