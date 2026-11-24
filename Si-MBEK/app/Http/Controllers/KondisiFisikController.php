<?php

namespace App\Http\Controllers;

use App\Http\Requests\KondisiFisikRequest;
use App\Models\Kambing;
use App\Models\KondisiFisik;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KondisiFisikController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->only(['kambing', 'dari', 'sampai', 'nafsu']);

        $data = KondisiFisik::with(['kambing.kandang', 'user'])
            ->filter($filter)
            ->orderByDesc('tanggal_cek')
            ->orderByDesc('id_kondisi')
            ->paginate(15)
            ->withQueryString();

        return view('kesehatan.kondisi-fisik.index', [
            'data'        => $data,
            'filter'      => $filter,
            'daftarKambing' => $this->daftarKambing(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('kesehatan.kondisi-fisik.create', [
            'kondisi'       => new KondisiFisik([
                'tanggal_cek' => now()->toDateString(),
                'id_kambing'  => $request->integer('kambing') ?: null,
                'nafsu_makan' => 'baik',
            ]),
            'daftarKambing' => $this->daftarKambing(),
        ]);
    }

    public function store(KondisiFisikRequest $request): RedirectResponse
    {
        $kondisi = KondisiFisik::create($request->validated() + ['id_user' => auth()->id()]);

        $pesan = 'Kondisi fisik ' . $kondisi->kambing->kode . ' berhasil dicatat.';
        if ($w = $kondisi->peringatan()) {
            $pesan .= ' ⚠ Perhatian: ' . implode(', ', $w) . '.';
        }

        // tombol "Simpan & input lagi" untuk pegawai yang keliling kandang
        if ($request->boolean('lanjut')) {
            return redirect()->route('kondisi-fisik.create')->with('success', $pesan);
        }

        return redirect()->route('kondisi-fisik.index')->with('success', $pesan);
    }

    public function show(KondisiFisik $kondisi): View
    {
        $kondisi->load(['kambing.kandang', 'user']);

        // riwayat 12 pencatatan terakhir kambing yang sama (untuk grafik berat)
        $riwayat = KondisiFisik::where('id_kambing', $kondisi->id_kambing)
            ->orderByDesc('tanggal_cek')->limit(12)->get()->reverse()->values();

        return view('kesehatan.kondisi-fisik.show', compact('kondisi', 'riwayat'));
    }

    public function edit(KondisiFisik $kondisi): View
    {
        return view('kesehatan.kondisi-fisik.edit', [
            'kondisi'       => $kondisi,
            'daftarKambing' => $this->daftarKambing($kondisi->id_kambing),
        ]);
    }

    public function update(KondisiFisikRequest $request, KondisiFisik $kondisi): RedirectResponse
    {
        $kondisi->update($request->validated());

        return redirect()->route('kondisi-fisik.index')
            ->with('success', 'Data kondisi fisik ' . $kondisi->kambing->kode . ' berhasil diperbarui.');
    }

    public function destroy(KondisiFisik $kondisi): RedirectResponse
    {
        $this->hanyaPemilik();
        $kondisi->delete();

        return redirect()->route('kondisi-fisik.index')
            ->with('success', 'Data kondisi fisik berhasil dihapus.');
    }

    /**
     * AJAX (jQuery): data kondisi terakhir seekor kambing, dipakai di form
     * untuk menampilkan berat sebelumnya & selisihnya secara langsung.
     */
    public function terakhir(Kambing $kambing): JsonResponse
    {
        $k = $kambing->kondisiFisik()->orderByDesc('tanggal_cek')->first();
        $kambing->load('kandang');

        return response()->json([
            'kambing' => [
                'kode'      => $kambing->kode,
                'kandang'   => $kambing->kandang?->label,
                'isolasi'   => $kambing->kandang?->isIsolasi() ?? false,
                'sakit'     => $kambing->penyakitAktif()->exists(),
            ],
            'terakhir' => $k ? [
                'tanggal'     => $k->tanggal_cek->translatedFormat('d M Y'),
                'berat_badan' => (float) $k->berat_badan,
                'suhu_tubuh'  => (float) $k->suhu_tubuh,
                'nafsu_makan' => $k->nafsu_makan,
            ] : null,
        ]);
    }

    /* ---------------------------------------------------------------- */

    private function daftarKambing(?int $termasuk = null)
    {
        return Kambing::with('kandang')
            ->where(fn ($q) => $q->where('status_aktif', true)
                ->when($termasuk, fn ($q) => $q->orWhere('id_kambing', $termasuk)))
            ->orderBy('id_kambing')
            ->get();
    }

    private function hanyaPemilik(): void
    {
        // Kolom `role` dibuat oleh modul User/Auth. Kalau belum ada, lewati.
        $user = auth()->user();
        abort_if($user && isset($user->role) && !in_array(strtolower($user->role), ['pemilik', 'pengelola', 'admin']), 403,
            'Hanya pemilik yang boleh menghapus data.');
    }
}
