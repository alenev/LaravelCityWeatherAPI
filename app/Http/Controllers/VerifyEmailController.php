<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;

class VerifyEmailController extends Controller
{

    public function set(Request $request): RedirectResponse
    {
   
        $user = User::find($request->route('id'));

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect('/email_verify_success');
    }
}
