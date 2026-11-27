<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenyakitRequest;
use App\Models\Kambing;
use App\Models\Penyakit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PenyakitController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->only(['kambing', 'dari', 'sampai', 'status', 'q']);

        $data = Penyakit::with(['kambing.kandang', 'user'])
            ->filter($filter)
            // kasus aktif di atas, lalu yang terbaru
            ->orderByRaw('CASE WHEN tanggal_sembuh IS NULL AND tanggal_kematian IS NULL THEN 0 ELSE 1 END')
            ->orderByDesc('tanggal_sakit')
            ->paginate(15)
            ->withQueryString();

        $ringkasan = [
            'sakit'   => Penyakit::aktif()->count(),
            'isolasi' => Penyakit::aktif()->diKandangIsolasi()->count(),
            'sembuh'  => Penyakit::whereNotNull('tanggal_sembuh')->whereMonth('tanggal_sembuh', now()->month)
                            ->whereYear('tanggal_sembuh', now()->year)->count(),
            'mati'    => Penyakit::whereNotNull('tanggal_kematian')->whereMonth('tanggal_kematian', now()->month)
                            ->whereYear('tanggal_kematian', now()->year)->count(),
        ];

        return view('kesehatan.penyakit.index', [
            'data'          => $data,
            'filter'        => $filter,
            'ringkasan'     => $ringkasan,
            'daftarKambing' => $this->daftarKambing(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('kesehatan.penyakit.create', [
            'penyakit'      => new Penyakit([
                'tanggal_sakit' => now()->toDateString(),
                'id_kambing'    => $request->integer('kambing') ?: null,
            ]),
            'daftarKambing' => $this->daftarKambing(),
        ]);
    }

    public function store(PenyakitRequest $request): RedirectResponse
    {
        $penyakit = DB::transaction(function () use ($request) {
            $p = Penyakit::create($request->dataPenyakit() + ['id_user' => auth()->id()]);
            $this->sinkronStatusKambing($p);
            return $p;
        });

        $pesan = 'Pencatatan penyakit ' . $penyakit->kambing->kode . ' berhasil disimpan.';
        if ($penyakit->status === 'sakit' && ! $penyakit->diIsolasi()) {
            return redirect()->route('penyakit.index')->with('success', $pesan)
                ->with('warning', $penyakit->kambing->kode . ' sedang sakit tapi BELUM berada di kandang isolasi. Pertimbangkan untuk memindahkannya.');
        }

        return redirect()->route('penyakit.index')->with('success', $pesan);
    }

    public function show(Penyakit $penyakit): View
    {
        $penyakit->load(['kambing.kandang', 'user']);

        $riwayatLain = Penyakit::where('id_kambing', $penyakit->id_kambing)
            ->whereKeyNot($penyakit->id_penyakit)
            ->orderByDesc('tanggal_sakit')->get();

        $kondisiTerakhir = $penyakit->kambing->kondisiFisik()
            ->orderByDesc('tanggal_cek')->limit(5)->get();

        return view('kesehatan.penyakit.show', compact('penyakit', 'riwayatLain', 'kondisiTerakhir'));
    }

    public function edit(Penyakit $penyakit): View
    {
        return view('kesehatan.penyakit.edit', [
            'penyakit'      => $penyakit,
            'daftarKambing' => $this->daftarKambing($penyakit->id_kambing),
        ]);
    }

    public function update(PenyakitRequest $request, Penyakit $penyakit): RedirectResponse
    {
        DB::transaction(function () use ($request, $penyakit) {
            $penyakit->update($request->dataPenyakit());
            $this->sinkronStatusKambing($penyakit);
        });

        return redirect()->route('penyakit.show', $penyakit)
            ->with('success', 'Pencatatan penyakit berhasil diperbarui.');
    }

    /** Aksi cepat: tandai sembuh hari ini (tombol di tabel). */
    public function sembuh(Penyakit $penyakit): RedirectResponse
    {
        abort_unless($penyakit->status === 'sakit', 422, 'Kasus ini sudah ditutup.');

        $penyakit->update(['tanggal_sembuh' => now()->toDateString()]);

        return back()->with('success', $penyakit->kambing->kode . ' ditandai sembuh. Jangan lupa pindahkan dari kandang isolasi.');
    }

    public function destroy(Penyakit $penyakit): RedirectResponse
    {
        $user = auth()->user();
        abort_if($user && isset($user->role) && $user->role !== 'pemilik', 403,
            'Hanya pemilik yang boleh menghapus data.');

        $penyakit->delete();

        return redirect()->route('penyakit.index')->with('success', 'Pencatatan penyakit berhasil dihapus.');
    }

    /* ---------------------------------------------------------------- */

    /**
     * Kalau kambing dicatat mati → nonaktifkan kambingnya (status_aktif = false).
     * Kalau tanggal kematian dihapus lagi (salah input) → aktifkan kembali.
     */
    private function sinkronStatusKambing(Penyakit $p): void
    {
        if (! Schema::hasColumn('kambing', 'status_aktif')) {
            return;
        }

        $masihAdaKematian = Penyakit::where('id_kambing', $p->id_kambing)
            ->whereNotNull('tanggal_kematian')->exists();

        Kambing::whereKey($p->id_kambing)->update(['status_aktif' => ! $masihAdaKematian]);
    }

    private function daftarKambing(?int $termasuk = null)
    {
        return Kambing::with('kandang')
            ->where(fn ($q) => $q->where('status_aktif', true)
                ->when($termasuk, fn ($q) => $q->orWhere('id_kambing', $termasuk)))
            ->orderBy('id_kambing')
            ->get();
    }
}
