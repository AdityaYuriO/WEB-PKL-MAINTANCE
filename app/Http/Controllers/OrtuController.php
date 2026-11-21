<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrtuController extends Controller
{
    public function create(){
        return view('orangtua.dataOrtu.createOrtu');
    }
}
