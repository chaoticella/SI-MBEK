<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKambingRequest;
use App\Http\Requests\UpdateKambingRequest;
use App\Models\Kambing;
use App\Models\Kandang;
use Illuminate\Database\QueryException;

class KambingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kambing = Kambing::with('kandang')->latest()->paginate(10);

        return view('kambing.index', compact('kambing'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kandang = Kandang::aktif()->get();

        return view('kambing.create', compact('kandang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKambingRequest $request)
    {
        Kambing::create($request->validated());

        return redirect()->route('kambing.index')
            ->with('success', 'Data kambing berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kambing $kambing)
    {
        $kambing->load(['kandang', 'perkawinanSebagaiPejantan', 'perkawinanSebagaiIndukan']);

        return view('kambing.show', compact('kambing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kambing $kambing)
    {
        $kandangs = Kandang::aktif()->get();

        return view('kambing.edit', compact('kambing', 'kandangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKambingRequest $request, Kambing $kambing)
    {
        $kambing->update($request->validated());

        return redirect()->route('kambing.index')
            ->with('success', 'Data kambing berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kambing $kambing)
    {
        try {
            $kambing->delete();
        } catch (QueryException $e) {
            return redirect()->route('kambing.index')
                ->with('error', 'Kambing tidak bisa dihapus karena sudah tercatat di data perkawinan.');
        }

        return redirect()->route('kambing.index')
            ->with('success', 'Data kambing berhasil dihapus.');
    }
}
