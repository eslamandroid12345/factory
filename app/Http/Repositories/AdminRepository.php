<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AdminRepositoryInterface;
use App\Http\Resources\AdminResource;
use App\Http\Resources\DeveloperResource;
use App\Models\Admin;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminRepository implements AdminRepositoryInterface
{

    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email|exists:admins,email',
                'password' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules, [
                'email.email' => 403,
                'email.exists' => 407,
            ]);

            if ($validator->fails()) {

                $errors = collect($validator->errors())->flatten(1)[0];
                if (is_numeric($errors)) {

                    $errors_arr = [
                        403 => 'Failed,Email must be an email',
                        407 => 'Failed,Email not found',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return helperJson(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return response()->json(['data' => null, 'message' => $validator->errors()->first(), 'code' => 422], 200);
            }

            $token = Auth::guard('admin-api')->attempt($request->only(['email', 'password']));

            if (!$token) {

                return helperJson(null, "كلمه المرور خطاء يرجي المحاوله مره اخري", 200, 422);
            }
            $admin = Auth::guard('admin-api')->user();
            $admin['token'] = $token;
            return helperJson(new AdminResource($admin), "تم تسجيل دخول المصنع بنجاح", 200);

        } catch (\Exception $exception) {

            return response()->json(['message' => $exception->getMessage(), 'code' => 500], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $rules = [

                'factory_name' => 'nullable|string|min:3|max:50',
                'factory_phone' => 'nullable|numeric',
                'factory_owner' => 'nullable|string|min:3|max:50',
                'commercial_registration' => 'nullable|numeric',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|min:8|confirmed',
                'image' => 'nullable|mimes:jpg,png,jpeg|max:2048',
                'access_days' => 'integer|min:1',

            ];
            $validator = Validator::make($request->all(), $rules, [
                'email.unique' => 406,
                'factory_phone.numeric' => 407,
                'password.confirmed' => 408,
            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];

                if (is_numeric($errors)) {
                    $errors_arr = [
                        406 => 'Failed,Email already exists',
                        407 => 'Failed,Phone of factory number must be an number',
                        408 => 'Failed,Password of factory must be confirmed',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return helperJson(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return response()->json(['data' => null, 'message' => $validator->errors()->first(), 'code' => 422], 200);
            }

            if ($image = $request->file('image')) {

                $destinationPath = 'admins/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $request['image'] = "$profileImage";
            }

            $storeNewAdmin = Admin::create([
                'factory_name' => $request->factory_name,
                'factory_phone' => $request->factory_phone,
                'factory_owner' => $request->factory_owner,
                'commercial_registration' => $request->commercial_registration,
                'email' => $request->email,
                'developer_id' => Auth::guard('developer-api')->id(),
                'password' => Hash::make($request->password),
                'image' => $request->file('image') != null ? $profileImage : 'avatar.jpg',
                'access_days' => $request->access_days,
            ]);

            if (isset($storeNewAdmin)) {

                $storeNewAdmin['token'] = Auth::guard('admin-api')->attempt($request->only(['email', 'password']));
                return helperJson(new AdminResource($storeNewAdmin), "تم تسجيل بيانات المصنع بنجاح", 200, 200);

            }
        } catch (\Exception $exception) {

            return response()->json(['message' => $exception->getMessage(), 'code' => 500], 500);
        }
    }

    public function logout(){
        try {
            auth()->guard('admin-api')->logout();

            return response()->json(['message' => "تم تسجيل خروج المصنع بنجاح", 'code' => 200], 200);


        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage(), 'code' => 500], 500);

        }
    }
}
