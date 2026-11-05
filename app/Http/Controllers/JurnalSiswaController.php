<?php

namespace App\Http\Controllers;

use App\Models\jurnalSiswa;
use Illuminate\Http\Request;

class JurnalSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('siswa.jurnal.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(jurnalSiswa $jurnalSiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(jurnalSiswa $jurnalSiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, jurnalSiswa $jurnalSiswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(jurnalSiswa $jurnalSiswa)
    {
        //
    }
}
