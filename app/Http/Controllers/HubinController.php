<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HubinController extends Controller
{
    public function index() {
        return view('hubin.review.reviewpengajuan');
    }

    public function show() {
        return view('hubin.review.detailpengajuan');
    }

    public function diterima()
    {
        return view('hubin.review.historyditerima');
    }

    public function ditolak()
    {
        return view('hubin.review.historyditolak');
    }

}
