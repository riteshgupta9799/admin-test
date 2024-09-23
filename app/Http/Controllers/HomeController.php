<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Auth;
use DB;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class HomeController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($userId) {
            return response()->json([
                'status' => 'true',
                'message' => 'User registered successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'User registration failed. Please try again.',
            ], 500);
        }
    }



    public function loginpage()
    {
        return view('login');
    }

    public function registerpage()
    {
        return view('register');
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $request->session()->regenerate();


        $user = DB::table('users')->where('email', $request->input('email'))->first();

        if ($user && password_verify($request->input('password'), $user->password)) {
            $request->session()->put('user_id', $user->id);

            return response()->json([
                'status' => 'true',
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], 200);
        }

        return response()->json(['status' => 'false', 'message' => 'Invalid credentials'], 401);
    }

    public function home(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $user = DB::table('users')->where('id', $userId)->first();


        return view('home', compact('user'));
    }



    public function logout(Request $request)
    {
        $request->session()->flush();



        return response()->json([
            'status' => 'true',
            'message' => 'Logged out successfully'
        ]);
    }

    public function getdata(Request $request)
    {
        $data = DB::select('select * from employee');
        return response()->json(['status' => 'true', 'message' => 'data', 'data' => $data]);
    }


    public function addPage(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $user = DB::table('users')->where('id', $userId)->first();


        return view('addPage', compact('user'));
    }


    public function adddata(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employee,email',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $img = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $img);
            $img = 'images/' . $img;

        } else {
            $img = $request->logo;
        }
        $Data = [

            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'image' => $img,
        ];
        DB::table('employee')->insert($Data);




        return response()->json([
            'status' => 'true',
            'message' => ' added successfully',
            'data' => $Data
        ], 201);
    }


    public function updatedata(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:employee,employee_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $id = $request->employee_id;
        // dd($id);

        $existingData = DB::table('employee')
            ->where('employee_id', $id)
            ->first();

        $existing_image = $existingData->image;

        if ($request->hasFile('image')) {
            $Image = $request->file('image');
            $imageName = time() . '.' . $Image->getClientOriginalExtension();
            $Image->move(public_path('images'), $imageName);
            $ImagePath = 'images/' . $imageName;
        } else {
            $ImagePath = $existing_image;
        }

        $updateData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'image' => $ImagePath
        ];

        DB::table('employee')->where('employee_id', $id)->update($updateData);

        return response()->json([
            'status' => 'true',
            "message"=>"Updated....!",
            'data' => $updateData,
            'existing_image' => $existing_image
        ]);
    }


    public function deletedata(Request $request)
    {
        $id = $request->employee_id;
        $data = DB::table('employee')->where('employee_id', $id)->first();
        if (!$data) {
            return response()->json([
                'status' => 'false',
                'message' => 'No data found'
            ]);
        }
        DB::table('employee')->where('employee_id', $id)->delete();
        return response()->json([
            'status' => 'true',
            'message' => 'Data Deleted'
        ]);

    }




}
