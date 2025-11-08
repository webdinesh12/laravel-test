<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;
use Maatwebsite\Excel\Facades\Excel;
use wapmorgan\Mp3Info\Mp3Info;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'DESC')->get();
        return view('users', compact('users'));
    }

    public function createuser()
    {
        return view('create-user');
    }

    public function editUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        return view('create-user', compact('user'));
    }

    public function exportUsers()
    {
        $users = User::orderBy('created_at', 'DESC')->get();
        return Excel::download(new UserExport($users), 'User_Export_' . date('YmdHis') . '.xlsx');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        if (file_exists(storage_path(str_replace('storage/', 'app/public/', $user->image)))) {
            unlink(storage_path(str_replace('storage/', 'app/public/', $user->image)));
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function audioLength()
    {
        return view('audio-length');
    }

    public function doCreateUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['nullable', 'exists:users,id'],
                'name' => ['required', 'regex:/^[A-Za-z ]+$/'],
                'email' => ['required', 'email'],
                'mobile_no' => ['bail', 'required', 'numeric', 'digits:10'],
                'password' => empty($request->id) ? ['required', 'min:8'] : ['nullable', 'min:8'],
                'profile_pic' => empty($request->id) ? ['required', 'mimes:jpg,jpeg,png'] : ['nullable', 'mimes:jpg,jpeg,png']
            ], [
                'name.regex' => 'Only charecters and spaces are allowed.'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }

            $user = new User();
            if (!empty($request->id)) {
                $user = User::find($request->id);
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->mobile_no;
            if (!empty($request->password)) {
                $user->password = $request->password;
            }
            if ($request->hasFile('profile_pic')) {
                $image = $request->file('profile_pic');
                $name = uniqid('IMG_') . '.' . $image->getClientOriginalExtension();
                $dbName = 'storage/user/' . $name;
                $image->storeAs('user', $name, 'public');
                $user->image = $dbName;
            }
            $user->save();
            session()->flash('success', 'User ' . (empty($request->id) ? 'created' : 'updated') . ' successfully.');
            return response()->json(['success' => 1, 'redirect' => route('users')]);
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

    public function findDistence()
    {
        return view('distence');
    }

    public function doFindDistence(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_latitude' => ['required', 'numeric'],
            'from_longitude' => ['required', 'numeric'],
            'to_latitude' => ['required', 'numeric'],
            'to_longitude' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        $fromCord = new Coordinate([$request->from_latitude, $request->from_longitude]);
        $toCord = new Coordinate([$request->to_latitude, $request->to_longitude]);
        $distence = number_format((new Geotools())->distance()->setFrom($fromCord)->setTo($toCord)->in('km')->haversine() ?? 0, 2, '.', ',');
        return $distence > 0 ? response()->json(['success' => 1, 'msg' => 'The distence between these two location is: <strong>' . $distence . 'KM</strong>']) : response()->json(['success' => 0, 'msg' => 'Wrong latitude or longitude']);
    }
}
