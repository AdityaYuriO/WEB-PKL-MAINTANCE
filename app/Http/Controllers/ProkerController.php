<?php 

namespace App\Http\Controllers;

use App\Models\Proker;
use Illuminate\Http\Request;

class ProkerController extends Controller
{
    public function index(Request $request)
{
    $proker = Proker::when($request->search, function($query, $search) {
                    $query->where('name', 'like', "%$search%");
                })
                ->paginate(10)
                ->withQueryString();

    return view('data.proker.dataProker', compact('proker'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:prokers,name'
        ]);

        Proker::create($request->only('name'));

        return redirect()->back()->with('success', 'Data proker berhasil ditambah.');
    }

    public function update(Request $request, Proker $proker)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:prokers,name,' . $proker->id
        ]);

        $proker->update($request->only('name'));

        return redirect()->back()->with('success', 'Data proker berhasil diperbarui.');
    }

    public function destroy(Proker $proker)
    {
        $proker->delete();

        return redirect()->back()->with('success', 'Data proker berhasil dihapus.');
    }
}
