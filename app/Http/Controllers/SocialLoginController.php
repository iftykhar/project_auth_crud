<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function gotogoogel(){
        return Socialite::driver('google')->redirect();
    }

    public function apigstore(){
        $socialUser = Socialite::driver('google')->user();
    //    dd($socialUser);
        $user = User::where('sid',$socialUser->id)->first();
        if($user != null){
            
            Auth::login($user);
            return redirect(RouteServiceProvider::HOME);
        }else{
            $store = new User;
            // $store->uname = $socialUser->email; //user set in different page
            $store->fname = $socialUser->user['given_name'];
            $store->lname = $socialUser->user['family_name'];
            $store->email = $socialUser->email;
            // $store->password = Hash::make($store->email); //password set in different page too
            $store->pic = $socialUser->avatar;
            $store->sid = $socialUser->id;
            $store->save();
            $sid = $socialUser->id;
            
            return view('auth.setpass',compact('sid'));

            // Auth::login($user);
            // return redirect(RouteServiceProvider::HOME);
        }
    }

    public function setpass(Request $request, $sid){
        $user = User::where('sid',$sid)->first();
        $user->uname = $request->uname;
        $user->password = Hash::make($request->password);
        $user->update();


        return redirect(RouteServiceProvider::HOME);
    }
}
