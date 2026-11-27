<?php

namespace App\Http\Controllers;

use App\Http\Requests\KandangRequest;
use App\Models\Kandang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KandangController extends Controller
{
    /**
     * Display a listing of the resource with filters and search.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $status = $request->input('status');

        $query = Kandang::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('blok_kandang', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if (!empty($kategori) && in_array($kategori, ['jantan', 'betina', 'isolasi'])) {
            $query->where('kategori', $kategori);
        }

        if ($status !== null && $status !== '') {
            if ($status === 'aktif') {
                $query->where('status_aktif', true);
            } elseif ($status === 'nonaktif') {
                $query->where('status_aktif', false);
            }
        }

        // Statistics for summary cards
        $totalKandang = Kandang::count();
        $totalKapasitas = Kandang::sum('kapasitas_maksimal');
        $kandangJantan = Kandang::where('kategori', 'jantan')->count();
        $kandangBetina = Kandang::where('kategori', 'betina')->count();
        $kandangIsolasi = Kandang::where('kategori', 'isolasi')->count();

        $kandangs = $query->orderBy('blok_kandang', 'asc')->paginate(10)->withQueryString();

        return view('kandang.index', compact(
            'kandangs',
            'totalKandang',
            'totalKapasitas',
            'kandangJantan',
            'kandangBetina',
            'kandangIsolasi',
            'search',
            'kategori',
            'status'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('kandang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KandangRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status_aktif'] = $request->boolean('status_aktif', true);

        $kandang = Kandang::create($data);

        return redirect()->route('kandang.index')
            ->with('success', "Kandang {$kandang->blok_kandang} berhasil ditambahkan!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Kandang $kandang): View
    {
        return view('kandang.show', compact('kandang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kandang $kandang): View
    {
        return view('kandang.edit', compact('kandang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KandangRequest $request, Kandang $kandang): RedirectResponse
    {
        $data = $request->validated();
        $data['status_aktif'] = $request->boolean('status_aktif', true);

        $kandang->update($data);

        return redirect()->route('kandang.index')
            ->with('success', "Data Kandang {$kandang->blok_kandang} berhasil diperbarui!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kandang $kandang): RedirectResponse
    {
        $blok = $kandang->blok_kandang;
        $kandang->delete();

        return redirect()->route('kandang.index')
            ->with('success', "Kandang {$blok} berhasil dihapus dari sistem.");
    }

    /**
     * Toggle the active status of the kandang.
     */
    public function toggleStatus(Kandang $kandang): RedirectResponse
    {
        $kandang->status_aktif = !$kandang->status_aktif;
        $kandang->save();

        $statusText = $kandang->status_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status Kandang {$kandang->blok_kandang} berhasil {$statusText}.");
    }
}
