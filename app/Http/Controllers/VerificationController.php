<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function show(): View
    {
        return view('verification.show');
    }

    public function verify(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('index').'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('index').'?verified=1');
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return redirect()
            ->back()
            ->with("success", "Ссылка для подтверждения отправлена на почту!");
    }
}
