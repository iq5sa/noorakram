<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Laravel\Socialite\Facades\Socialite;

class FcaebookLogin extends Controller
{
    //

    public function redirect(): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function redirectCallback(Request $request): Redirector|RedirectResponse|Application
    {

        if ($request->session()->has('redirectTo')) {
            $redirectURL = $request->session()->get('redirectTo');
        } else {
            $redirectURL = null;
        }


        try {
            $user = Socialite::driver('facebook')->user();

            $findUser = User::where('facebook_id', $user->id)->first();
            if (!$findUser) {
                $findUser = User::where('email', $user->email)->first();

            }

            if ($findUser) {
                $findUser->google_id = $user->id;
                $findUser->status = 1;
                $findUser->email_verified_at = Carbon::today();
                $findUser->save();
                auth()->login($findUser);

            } else {
                User::create([
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'facebook' => $user->id,
                    'password' => encrypt('123456dummy'),
                    'username' => $this->generateUniqueUsername($user->name),
                    'status' => 1,
                    'email_verified_at' => Carbon::today(),
                ]);

            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }

        if ($redirectURL == null) {
            return redirect()->route('user.dashboard');
        } else {

            $request->session()->forget('redirectTo');

            return redirect($redirectURL);
        }

    }


    public function generateUniqueUsername($name): string
    {
        // Remove spaces from the name and convert it to lowercase
        $username = str_replace(' ', '', strtolower($name));

        // Append a random string to the username
        $username .= '_' . substr(md5(time()), 0, 5);

        // Check if the username already exists in the database
        $existingUser = User::where('username', $username)->first();

        // If the username already exists, call the function recursively until we get a unique username
        if ($existingUser) {
            return $this->generateUniqueUsername($name);
        }

        return $username;
    }
}
