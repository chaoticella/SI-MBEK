<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerkawinanRequest;
use App\Models\Kambing;
use App\Models\Perkawinan;
use Illuminate\Support\Carbon;

class PerkawinanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perkawinans = Perkawinan::with(['pejantan', 'indukan'])
            ->latest('tanggal_kawin')
            ->paginate(10);

        return view('perkawinan.index', compact('perkawinans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('perkawinan.create', [
            'pejantans' => Kambing::where('jenis_kelamin', 'jantan')->orderBy('kode_kambing')->get(),
            'indukans'  => Kambing::where('jenis_kelamin', 'betina')->orderBy('kode_kambing')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PerkawinanRequest $request)
    {
        $data = $request->validated();
        $data['estimasi_melahirkan'] ??= Carbon::parse($data['tanggal_kawin'])->addDays(150);
        $data['jumlah_anak_lahir'] ??= 0;

        Perkawinan::create($data);

        return redirect()->route('perkawinan.index')
            ->with('success', 'Data perkawinan berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Perkawinan $perkawinan)
    {
        $perkawinan->load(['pejantan', 'indukan']);

        return view('perkawinan.show', compact('perkawinan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perkawinan $perkawinan)
    {
        return view('perkawinan.edit', [
            'perkawinan' => $perkawinan,
            'pejantans'  => Kambing::where('jenis_kelamin', 'jantan')->orderBy('kode_kambing')->get(),
            'indukans'   => Kambing::where('jenis_kelamin', 'betina')->orderBy('kode_kambing')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PerkawinanRequest $request, Perkawinan $perkawinan)
    {
        $data = $request->validated();
        $data['estimasi_melahirkan'] ??= Carbon::parse($data['tanggal_kawin'])->addDays(150);
        $data['jumlah_anak_lahir'] ??= 0;

        $perkawinan->update($data);

        return redirect()->route('perkawinan.index')
            ->with('success', 'Data perkawinan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perkawinan $perkawinan)
    {
        $perkawinan->delete();

        return redirect()->route('perkawinan.index')
            ->with('success', 'Data perkawinan berhasil dihapus.');
    }
}