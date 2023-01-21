<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;

interface DeveloperRepositoryInterface{

    public function login(Request $request);
    public function register(Request $request);
    public function logout();
    public function allAdmins();
    public function adminBlockById(Request $request,$id);

}
