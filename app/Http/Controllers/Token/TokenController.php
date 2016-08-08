<?php

namespace App\Http\Controllers\Token;

use App\Http\Controllers\Model\Weixin;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    public function __construct()
    {
        $this->model= new Weixin();
    }
    //验证入口token
    public function index(){
        echo $this->model->getData();
    }
}
