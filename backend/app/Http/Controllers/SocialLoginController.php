<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use TaylorNetwork\UsernameGenerator\Generator;

class SocialLoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $isNewUser = false;
        $provider = $request->input('provider_name');
        $token = $request->input('access_code');
        // get the provider's user. (In the provider server)
        $providerUser = Socialite::driver($provider)->userFromToken($token);

        $user = User::where('provider_name', $provider)->where('provider_id', $providerUser->id)->first();
        // if there is no record with these data, create a new user
        if ($user == null) {
            $usernameGenerator = new Generator;
            $user = User::create([
                'email' => $providerUser->getEmail(),
                'username' => $usernameGenerator->generate($providerUser->getNickname() ?? $providerUser->getName()),
                'name' => $providerUser->getName(),
                'provider_name' => $provider,
                'provider_id' => $providerUser->getId(),
            ]);
            $isNewUser = true;
        }

        return response()->json(['token' => $user->createToken($request->device_name)->plainTextToken, 'user' => $user, 'is_new_user' => $isNewUser]);
    }
}
