<?php

namespace App\Http\Controllers;

use App\Models\Iduka;
use App\Models\Pembimbing;
use Illuminate\Http\Request;

class InstitusiController extends Controller
{
   public function index(){
    $iduka = Iduka::all();
    $pembimbing = Pembimbing::all();
    $user = auth()->user();
    return view('iduka.data_pribadi_iduka.dataInstitusi', compact('user', 'iduka', 'pembimbing'));
   }
}
