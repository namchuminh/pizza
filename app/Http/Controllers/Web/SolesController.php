<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SolesController extends Controller
{
    public function index(){
        return view('admin.soles.index');
    }

    public function create(){
        return view('admin.soles.create');
    }

    public function update(){
        return view('admin.soles.update');
    }
}
