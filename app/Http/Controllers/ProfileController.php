<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('success', 'Silakan login terlebih dahulu untuk melihat profil.');
        }

        $user = Auth::user();
        $patient = Patient::where('user_id', $user->id)->latest()->first();

        return view('profile.show', compact('user', 'patient'));
    }
}
