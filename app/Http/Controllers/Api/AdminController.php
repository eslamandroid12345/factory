<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\AdminRepositoryInterface;
use Illuminate\Http\Request;

class AdminController extends Controller{

    public $adminRepositoryInterface;

    public function __construct(AdminRepositoryInterface $adminRepositoryInterface){

        $this->middleware('check:developer-api')->only('register');
        $this->adminRepositoryInterface = $adminRepositoryInterface;
    }

    public function login(Request $request)
    {

        return $this->adminRepositoryInterface->login($request);
    }

    public function register(Request $request)
    {

        return $this->adminRepositoryInterface->register($request);

    }

    public function logout()
    {
        return $this->adminRepositoryInterface->logout();

    }
}
