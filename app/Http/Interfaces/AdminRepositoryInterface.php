<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;

interface AdminRepositoryInterface{


    public function login(Request $request);
    public function register(Request $request);
    public function logout();

}
