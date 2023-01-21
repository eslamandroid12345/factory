<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\DeveloperRepositoryInterface;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{

    public $developerRepositoryInterface;

    public function __construct(DeveloperRepositoryInterface $developerRepositoryInterface)
    {

        $this->middleware('check:developer-api')->only('logout','adminBlockById','allAdmins');
        $this->developerRepositoryInterface = $developerRepositoryInterface;
    }

    public function login(Request $request)
    {
        return $this->developerRepositoryInterface->login($request);
    }

    public function register(Request $request)
    {
        return $this->developerRepositoryInterface->register($request);

    }

    public function logout()
    {
        return $this->developerRepositoryInterface->logout();

    }

    public function allAdmins()
    {
        return $this->developerRepositoryInterface->allAdmins();

    }

    public function adminBlockById(Request $request,$id)
    {
        return $this->developerRepositoryInterface->adminBlockById($request,$id);

    }


}
