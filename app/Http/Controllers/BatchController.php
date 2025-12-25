<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::latest()->paginate(15);
        return view('batches.index', compact('batches'));
    }

    public function create()
    {
        return view('batches.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'requester_unit' => 'nullable|string|max:120',
            'notes' => 'nullable|string',
        ]);

        $batch = Batch::create($data);
        return redirect()->route('batches.show', $batch)->with('ok', 'Batch berhasil dibuat.');
    }

    public function show(Batch $batch)
    {
        $clients = $batch->clients()->latest()->get();
        return view('batches.show', compact('batch', 'clients'));
    }

    public function destroy(Batch $batch)
    {
        if ($batch->status !== 'draft') {
            return back()->with('err', 'Batch tidak bisa dihapus karena status bukan DRAFT.');
        }

        // Ini akan menghapus client juga kalau relasi FK cascadeOnDelete sudah ada
        $batch->delete();

        return redirect()->route('batches.index')->with('ok', 'Batch draft berhasil dihapus.');
    }

}
