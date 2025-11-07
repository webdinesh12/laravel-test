<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use wapmorgan\Mp3Info\Mp3Info;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function createuser()
    {
        $users = User::orderBy('created_at', 'DESC')->get();
        return view('create-user', compact('users'));
    }
    public function audioLength()
    {
        return view('audio-length');
    }

    public function doCreateUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'regex:/^[A-Za-z ]+$/'],
                'email' => ['required', 'email'],
                'mobile_no' => ['bail', 'required', 'numeric', 'digits:10'],
                'password' => ['required', 'min:8'],
                'profile_pic' => ['required', 'mimes:jpg,jpeg,png']
            ], [
                'name.regex' => 'Only charecters and spaces are allowed.'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->mobile_no;
            $user->password = $request->password;
            if ($request->hasFile('profile_pic')) {
                $image = $request->file('profile_pic');
                $name = uniqid('IMG_') . '.' . $image->getClientOriginalExtension();
                $dbName = 'storage/user/' . $name;
                $image->storeAs('user', $name, 'public');
                $user->image = $dbName;
            }
            $user->save();
            session()->flash('success', 'User created successfully.');
            return response()->json(['success' => 1, 'msg' => 'User created successfully.']);
        } catch (Exception $err) {
            return response()->json(['success' => 0, 'msg' => 'Something went wrong.']);
        }
    }

    public function doCheckAudioLength(Request  $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'audio' => ['required', 'mimes:mp3,m4a,wav']
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }
            $file = $request->file('audio');
            $audio = new Mp3Info($file);
            $durationInSeconds = $audio->duration;

            $hour = floor($durationInSeconds / (60 * 60));
            $min = floor(($durationInSeconds % (60 * 60)) / 60);
            $sec = $durationInSeconds % 60;

            $returnText = '';
            if (!empty($hour)) {
                $returnText = $hour . ' Hour' . ($hour > 1 ? 's' : '') . ' ';
            }
            if (!empty($min)) {
                $returnText .= $min . ' Minuit' . ($min > 1 ? 's' : '') . ' ';
            }
            $returnText .= $sec . ' Second' . ($sec > 1 ? 's' : '') . ' ';
            return response()->json(['success' => 1, 'msg' => 'Audio length: ' . $returnText]);
        } catch (Exception $err) {
            return response()->json(['success' => 0, 'msg' => 'Something went wrong.']);
        }
    }
}
