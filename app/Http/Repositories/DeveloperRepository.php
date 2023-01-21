<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\DeveloperRepositoryInterface;
use App\Http\Resources\AdminResource;
use App\Http\Resources\DeveloperResource;
use App\Models\Admin;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeveloperRepository implements DeveloperRepositoryInterface
{

    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email|exists:developers,email',
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

            $token = Auth::guard('developer-api')->attempt($request->only(['email', 'password']));

            if (!$token) {

                return helperJson(null, "كلمه المرور خطاء يرجي المحاوله مره اخري", 200, 422);
            }
            $developer = Auth::guard('developer-api')->user();
            $developer['token'] = $token;
            return helperJson(new DeveloperResource($developer), "تم تسجيل دخول المطور بنجاح", 200);

        } catch (\Exception $exception) {

            return response()->json(['message' => $exception->getMessage(), 'code' => 500], 500);
        }
    }

    public function register(Request $request)
    {

        try {
            $rules = [
                'name' => 'required|string|min:3|max:50',
                'email' => 'required|email|unique:developers,email',
                'password' => 'required|min:8|confirmed',
                'image' => 'nullable|mimes:jpg,png,jpeg|max:2048',
                'phone' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $rules, [
                'email.unique' => 406,
                'phone.numeric' => 407,
                'password.confirmed' => 408,
            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];

                if (is_numeric($errors)) {
                    $errors_arr = [
                        406 => 'Failed,Email already exists',
                        407 => 'Failed,Phone number must be an number',
                        408 => 'Failed,Password must be confirmed',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return helperJson(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return response()->json(['data' => null, 'message' => $validator->errors()->first(), 'code' => 422], 200);
            }

            if ($image = $request->file('image')) {

                $destinationPath = 'developers/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $request['image'] = "$profileImage";
            }

            $storeNewDeveloper = Developer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'image' => $request->file('image') != null ? $profileImage : 'avatar.jpg',
            ]);

            if (isset($storeNewDeveloper)) {

                $storeNewDeveloper['token'] = Auth::guard('developer-api')->attempt($request->only(['email', 'password']));
                return helperJson(new DeveloperResource($storeNewDeveloper), "تم تسجيل بيانات المطور بنجاح", 201, 201);

            }
        } catch (\Exception $exception) {

            return response()->json(['message' => $exception->getMessage(), 'code' => 500], 500);
        }
    }

    public function logout()
    {
        try {
            auth()->guard('developer-api')->logout();

            return response()->json(['message' => "تم تسجيل خروج المطور بنجاح", 'code' => 200], 200);


        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage(), 'code' => 500], 500);

        }

    }

    public function allAdmins()
    {

        $allAdmins = Admin::query()->orderByDesc('id')->get();
        if($allAdmins->count() > 0) {

            return helperJson(AdminResource::collection($allAdmins),'تم ارسال جميع المشرفين بنجاح',200);
        }else{

            return response()->json(['message' => 'لا يوجد مشرفين الان يرجي الانتظار حين اضافه مشرفين جدد', 'code' => 407],200);
        }
    }

    public function adminBlockById(Request $request,$id){

        try {
            $rules = [
                'status' => 'required|in:enabled,disabled',

            ];
            $validator = Validator::make($request->all(), $rules, [
                'status.in' => 406,

            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten(1)[0];

                if (is_numeric($errors)) {
                    $errors_arr = [
                        406 => 'Failed,Status must be an enabled or disabled',
                    ];

                    $code = collect($validator->errors())->flatten(1)[0];
                    return helperJson(null, isset($errors_arr[$errors]) ? $errors_arr[$errors] : 500, $code);
                }
                return response()->json(['data' => null, 'message' => $validator->errors()->first(), 'code' => 422], 200);
            }
            $admin = Admin::where('id','=',$id)->first();
            if(!$admin){

                return helperJson(null,'هذا المصنع غير مسجل بقاعده البيانات برجاء المحاوله مع رمز اخر',404);
            }

            $admin->update([
                'status' => $request->status,
            ]);

            return helperJson(new AdminResource($admin),'تم تحديث حاله المصنع بنجاح',200);

        }catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage(), 'code' => 500], 500);

        }

    }


}
