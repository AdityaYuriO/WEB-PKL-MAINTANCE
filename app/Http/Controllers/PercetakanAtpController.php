<?php

namespace App\Http\Controllers;

use App\Models\Iduka;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PercetakanAtpController extends Controller
{
    public function index(Request $request)
    {
        $query = Iduka::orderBy('rekomendasi', 'desc')
                      ->orderBy('created_at', 'desc');

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', '%' . $search . '%')
                  ->orWhere('alamat', 'LIKE', '%' . $search . '%');
            });
        }

        // Filter berdasarkan rekomendasi/ajuan
        if ($request->has('filter') && $request->filter != 'all') {
            if ($request->filter == 'rekomendasi') {
                $query->where('rekomendasi', 1);
            } elseif ($request->filter == 'ajuan') {
                $query->where('rekomendasi', 0);
            }
        }

        $iduka = $query->paginate(10)->withQueryString();

        return view('iduka.dataiduka.cetakatp', compact('iduka'));
    }

    public function show($id)
    {
        $iduka = Iduka::where('id', $id)->first();

        return view('iduka.dataiduka.detailpersuratan', compact('iduka'));
    }

    public function downloadAtpIduka($id)
    {
        $iduka = Iduka::with(['atps.konke', 'atps.cp', 'atps.atp'])
            ->findOrFail($id);

        return Pdf::loadView('persuratan.suratPengajuan.kaprogatp', compact('iduka'))
            ->download('Data_ATP_' . $iduka->nama . '.pdf');
    }
}
