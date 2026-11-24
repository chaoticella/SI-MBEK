/**
 * MODUL 3 — Kesehatan & Pencatatan Penyakit Kambing (Nat)
 * Dynamic UI pakai jQuery:
 *  - Layout     : toggle sidebar (HP), panel notifikasi, tutup alert, konfirmasi hapus
 *  - Kondisi    : AJAX info kambing & berat terakhir, cek tanda vital live
 *  - Penyakit   : tambah/hapus baris obat/vaksin, preset obat, chip gejala,
 *                 toggle tanggal sembuh/mati sesuai status
 */
import $ from 'jquery';

window.$ = window.jQuery = $;

$(function () {
    /* =========================================================
     * 1. LAYOUT
     * ======================================================= */
    $('#btn-sidebar, #sidebar-backdrop').on('click', function () {
        $('#sidebar').toggleClass('-translate-x-full');
        $('#sidebar-backdrop').toggleClass('hidden');
    });

    $('#btn-notif').on('click', function (e) {
        e.stopPropagation();
        $('#notif-panel').toggleClass('hidden');
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#notif-panel').length) $('#notif-panel').addClass('hidden');
    });

    $(document).on('click', '.js-dismiss', function () {
        $(this).closest('.js-dismissable').slideUp(150);
    });

    $(document).on('submit', 'form.js-confirm', function (e) {
        if (!window.confirm($(this).data('confirm') || 'Yakin?')) e.preventDefault();
    });

    /* =========================================================
     * 2. FORM KONDISI FISIK
     * ======================================================= */
    const $fk = $('#form-kondisi');
    if ($fk.length) {
        const cfg = $fk.data();
        let beratTerakhir = null;

        const num = (v) => {
            if (v === undefined || v === null || v === '') return null;
            const n = parseFloat(String(v).replace(',', '.'));
            return isNaN(n) ? null : n;
        };

        // --- AJAX: info kambing + pencatatan terakhir ---
        function muatInfoKambing() {
            const id = $('#id_kambing').val();
            const $info = $('#info-kambing');
            beratTerakhir = null;
            if (!id) { $info.addClass('hidden'); hitungBerat(); return; }

            $info.removeClass('hidden').html('<span class="animate-pulse">Memuat data kambing…</span>');

            $.getJSON(`${cfg.urlTerakhir}/${id}`)
                .done(function (res) {
                    let html = `<b>${res.kambing.kode}</b> · ${res.kambing.kandang ?? 'tanpa kandang'}`;
                    if (res.kambing.isolasi) html += ' <span class="ml-1 rounded bg-red-100 px-1.5 text-[10px] font-bold uppercase text-red-700">Isolasi</span>';
                    if (res.kambing.sakit)   html += ' <span class="ml-1 rounded bg-amber-100 px-1.5 text-[10px] font-bold uppercase text-amber-700">Sedang sakit</span>';

                    if (res.terakhir) {
                        beratTerakhir = res.terakhir.berat_badan;
                        html += `<br>Cek terakhir <b>${res.terakhir.tanggal}</b>: ${res.terakhir.berat_badan} kg · ${res.terakhir.suhu_tubuh}°C · nafsu ${res.terakhir.nafsu_makan}`;
                    } else {
                        html += '<br>Belum pernah dicatat — ini pencatatan pertama.';
                    }
                    $info.html(html);
                    hitungBerat();
                })
                .fail(() => $info.html('<span class="text-red-600">Gagal memuat info kambing.</span>'));
        }

        // --- Selisih berat vs pencatatan terakhir ---
        function hitungBerat() {
            const b = num($('#berat_badan').val());
            const $h = $('#hint-berat').removeClass('text-red-600 text-emerald-600 text-stone-500');
            if (b === null || beratTerakhir === null) { $h.text('').addClass('text-stone-500'); return; }

            const d = +(b - beratTerakhir).toFixed(2);
            const persen = beratTerakhir ? ((d / beratTerakhir) * 100).toFixed(1) : 0;
            if (d < 0) $h.addClass('text-red-600').text(`▼ turun ${Math.abs(d)} kg (${persen}%) dari cek terakhir`);
            else       $h.addClass('text-emerald-600').text(`▲ naik ${d} kg (+${persen}%) dari cek terakhir`);
        }

        // --- Cek tanda vital secara live ---
        function cekVital() {
            const suhu = num($('#suhu_tubuh').val());
            const bcs = num($('#bcs').val());
            const fam = num($('input[name=famacha]:checked').val());
            const nafsu = $('input[name=nafsu_makan]:checked').val();

            const hasil = {
                suhu: suhu === null ? null : (suhu > cfg.suhuMax ? ['bad', 'Demam (> ' + cfg.suhuMax + '°C)']
                    : suhu < cfg.suhuMin ? ['bad', 'Suhu rendah (< ' + cfg.suhuMin + '°C)'] : ['ok', 'Suhu normal']),
                bcs: bcs === null ? null : (bcs < cfg.bcsMin ? ['warn', 'Terlalu kurus'] : bcs > cfg.bcsMax ? ['warn', 'Terlalu gemuk'] : ['ok', 'BCS ideal']),
                famacha: fam === null ? null : (fam >= cfg.famachaBahaya ? ['bad', 'Anemia — cek cacingan']
                    : fam === 3 ? ['warn', 'Perlu dipantau'] : ['ok', 'Selaput mata sehat']),
            };
            const warna = { ok: 'text-emerald-600', warn: 'text-amber-600', bad: 'text-red-600' };
            const ikon = { ok: '✓', warn: '!', bad: '⚠' };

            let list = '', adaMasalah = false;
            $.each(hasil, function (key, r) {
                const $hint = $(`.js-vital-hint[data-for=${key}]`).removeClass('text-emerald-600 text-amber-600 text-red-600');
                if (!r) { $hint.text(''); return; }
                $hint.addClass(warna[r[0]]).text(r[1]);
                list += `<li class="${warna[r[0]]}">${ikon[r[0]]} ${r[1]}</li>`;
                if (r[0] === 'bad') adaMasalah = true;
            });
            if (nafsu === 'buruk') { list += '<li class="text-red-600">⚠ Nafsu makan buruk</li>'; adaMasalah = true; }

            $('#ringkasan-vital').html(list || '<li class="text-stone-400">Isi tanda vital untuk melihat status.</li>');
            $('#saran-pencatatan-penyakit').toggleClass('hidden', !adaMasalah);
        }

        $('#id_kambing').on('change', muatInfoKambing);
        $('#berat_badan').on('input', hitungBerat);
        $fk.on('input change', '.js-vital, input[name=nafsu_makan]', cekVital);

        if ($('#id_kambing').val()) muatInfoKambing();
        cekVital();
    }

    /* =========================================================
     * 3. FORM PENYAKIT — input obat/vaksin dinamis
     * ======================================================= */
    const $fp = $('#form-penyakit');
    if ($fp.length) {
        const $body = $('#obat-body');
        const template = $('#tpl-obat').html();
        let nextIndex = $body.find('tr.obat-row').length;

        function refreshObat() {
            const n = $body.find('tr.obat-row').length;
            $('#obat-kosong').toggleClass('hidden', n > 0);
            $('#obat-total').text(n);
        }

        function tambahObat(data = {}) {
            const $row = $(template.replaceAll('__INDEX__', nextIndex++));
            if (data.jenis) $row.find('.js-obat-jenis').val(data.jenis);
            if (data.nama)  $row.find('.js-obat-nama').val(data.nama);
            if (data.dosis) $row.find('input[name$="[dosis]"]').val(data.dosis);
            // default tanggal pemberian = hari ini (tidak boleh sebelum tanggal sakit)
            $row.find('.js-obat-tanggal').val(new Date().toISOString().slice(0, 10))
                .attr('min', $('#tanggal_sakit').val());

            $row.hide().appendTo($body).fadeIn(150);
            refreshObat();
            if (!data.nama) $row.find('.js-obat-nama').trigger('focus');
            return $row;
        }

        $('#btn-tambah-obat').on('click', () => tambahObat());

        $fp.on('click', '.js-preset-obat', function () {
            const d = $(this).data();
            // kalau ada baris kosong, isi itu dulu
            const $kosong = $body.find('.js-obat-nama').filter((_, el) => !el.value).first();
            if ($kosong.length) {
                const $row = $kosong.closest('tr');
                $row.find('.js-obat-jenis').val(d.jenis);
                $kosong.val(d.nama);
                $row.find('input[name$="[dosis]"]').val(d.dosis);
            } else {
                tambahObat({ jenis: d.jenis, nama: d.nama, dosis: d.dosis });
            }
            $(this).addClass('ring-2 ring-emerald-400');
            setTimeout(() => $(this).removeClass('ring-2 ring-emerald-400'), 400);
        });

        $fp.on('click', '.js-hapus-obat', function () {
            $(this).closest('tr').fadeOut(150, function () { $(this).remove(); refreshObat(); });
        });

        // --- Chip gejala → tambahkan ke textarea ---
        $fp.on('click', '.js-chip-gejala', function () {
            const g = $(this).data('gejala');
            const $t = $('#gejala');
            const isi = $t.val().trim();
            if (isi.toLowerCase().includes(g)) return;
            $t.val(isi ? `${isi}, ${g}` : g.charAt(0).toUpperCase() + g.slice(1)).trigger('focus');
            $(this).addClass('border-emerald-500 bg-emerald-50');
        });

        // --- Status kasus → tampilkan tanggal sembuh / kematian ---
        function toggleStatus() {
            const s = $('input.js-status:checked').val();
            $('.js-status-field').each(function () {
                const tampil = $(this).data('status') === s;
                $(this).toggle(tampil).find('input').prop('required', tampil);
                if (tampil && !$(this).find('input').val()) {
                    $(this).find('input').val(new Date().toISOString().slice(0, 10));
                }
            });
            hitungLamaSakit();
        }

        function hitungLamaSakit() {
            const mulai = $('#tanggal_sakit').val();
            const s = $('input.js-status:checked').val();
            const akhir = s === 'sembuh' ? $('#tanggal_sembuh').val() : s === 'mati' ? $('#tanggal_kematian').val() : new Date().toISOString().slice(0, 10);
            if (!mulai || !akhir) return $('#info-lama-sakit').text('');
            const hari = Math.round((new Date(akhir) - new Date(mulai)) / 86400000);
            $('#info-lama-sakit').text(hari < 0 ? '⚠ Tanggal akhir sebelum tanggal sakit!' : `Lama sakit: ${hari} hari`)
                .toggleClass('text-red-600', hari < 0);
        }

        $fp.on('change', 'input.js-status', toggleStatus);
        $('#tanggal_sakit, #tanggal_sembuh, #tanggal_kematian').on('change', function () {
            $('#tanggal_sembuh, #tanggal_kematian').attr('min', $('#tanggal_sakit').val());
            $body.find('.js-obat-tanggal').attr('min', $('#tanggal_sakit').val());
            hitungLamaSakit();
        });

        toggleStatus();
        refreshObat();
    }
});
