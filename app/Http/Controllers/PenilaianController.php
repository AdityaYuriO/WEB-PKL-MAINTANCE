<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\IndikatorPenilaian;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenilaianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role === 'hubin') {
            $allSiswa = User::where('role', 'siswa')->where('status', 'aktif')->get();
        } elseif ($role === 'kaprog' || $role === 'guru') {
            $guru     = $user->guru;
            $allSiswa = $guru ? $guru->siswas : collect();
        } elseif ($role === 'iduka') {
            $iduka    = $user->iduka;
            $allSiswa = $iduka
                ? User::where('role', 'siswa')->where('status', 'aktif')->where('iduka_id', $iduka->id)->get()
                : collect();
        } else {
            $allSiswa = collect();
        }

        $siswa = $allSiswa->filter(function ($s) {
            $nilaiGuru  = Penilaian::where('users_id', $s->id)
                ->where('jenis_penilaian', 'guru_pembimbing')
                ->whereNull('ketercapaian_indikator')
                ->exists();
            $nilaiIduka = Penilaian::where('users_id', $s->id)
                ->where('jenis_penilaian', 'instruktur_iduka')
                ->whereNull('ketercapaian_indikator')
                ->exists();
            return !($nilaiGuru && $nilaiIduka);
        })->values();

        $draft = $allSiswa->filter(function ($s) {
            $nilaiGuru  = Penilaian::where('users_id', $s->id)
                ->where('jenis_penilaian', 'guru_pembimbing')
                ->whereNull('ketercapaian_indikator')
                ->exists();
            $nilaiIduka = Penilaian::where('users_id', $s->id)
                ->where('jenis_penilaian', 'instruktur_iduka')
                ->whereNull('ketercapaian_indikator')
                ->exists();
            return ($nilaiGuru xor $nilaiIduka);
        })->values();

        $draftData = [];
        foreach ($draft as $ds) {
            $draftData[] = [
                'siswa' => $ds,
                'guru'  => Penilaian::where('users_id', $ds->id)
                    ->where('jenis_penilaian', 'guru_pembimbing')
                    ->whereNull('ketercapaian_indikator')
                    ->get(),
                'iduka' => Penilaian::where('users_id', $ds->id)
                    ->where('jenis_penilaian', 'instruktur_iduka')
                    ->whereNull('ketercapaian_indikator')
                    ->get(),
            ];
        }

        return view('penilaian.input_nilai.index', compact('siswa', 'draftData'));
    }

    public function getIndikator($siswa_id)
    {
        $siswa = User::findOrFail($siswa_id);

        $tujuanList = \App\Models\TujuanPembelajaran::where('id_konke', $siswa->konke_id)
            ->with('indikatorPenilaians')
            ->get()
            ->map(function ($tp) {
                return [
                    'id'                   => $tp->id,
                    'tujuan_pembelajaran'  => $tp->tujuan_pembelajaran,
                    'indikator_penilaians' => $tp->indikatorPenilaians->map(function ($i) {
                        return [
                            'id'        => $i->id,
                            'indikator' => $i->indikator,
                        ];
                    }),
                ];
            });

        return response()->json($tujuanList);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'            => 'required|exists:users,id',
            'nilai_tp'           => 'required|array',
            'ketercapaian'       => 'required|array',
            'jenis_penilaian_tp' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->nilai_tp as $tp_id => $nilai) {
                Penilaian::create([
                    'users_id'               => $request->user_id,
                    'id_tujuan_pembelajaran' => $tp_id,
                    'jenis_penilaian'        => $request->jenis_penilaian_tp[$tp_id] ?? null,
                    'nilai'                  => $nilai,
                    'ketercapaian_indikator' => null,
                    'deskripsi'              => $request->deskripsi_tp[$tp_id] ?? null,
                ]);
            }

            foreach ($request->ketercapaian as $indikator_id => $ketercapaian) {
                $indikator       = \App\Models\IndikatorPenilaian::find($indikator_id);
                $tp_id           = $indikator ? $indikator->id_tujuan_pembelajaran : null;
                $jenis_penilaian = ($tp_id && isset($request->jenis_penilaian_tp[$tp_id]))
                    ? $request->jenis_penilaian_tp[$tp_id]
                    : null;

                Penilaian::create([
                    'users_id'               => $request->user_id,
                    'id_tujuan_pembelajaran' => $tp_id,
                    'ketercapaian_indikator' => $ketercapaian, // 'ya' atau 'tidak'
                    'jenis_penilaian'        => $jenis_penilaian,
                    'nilai'                  => null,
                    'deskripsi'              => null,
                ]);
            }

            DB::commit();
            return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Penilaian Store Error: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Gagal! Terjadi kesalahan saat menyimpan penilaian: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function export($id)
    {
        $siswa          = User::findOrFail($id);
        $namaGuru       = $siswa->pembimbing ? $siswa->pembimbing->nama : '-';
        $namaInstruktur = $siswa->iduka ? ($siswa->iduka->nama_pimpinan ?? '-') : '-';
        $namaIduka      = $siswa->iduka ? $siswa->iduka->nama : '-';
        $namaKonke      = $siswa->konke ? $siswa->konke->name_konke : '-';

        // Ambil penilaian TP (nilai numerik, ketercapaian_indikator IS NULL)
        $penilaianGuru = Penilaian::where('users_id', $id)
            ->where('jenis_penilaian', 'guru_pembimbing')
            ->whereNull('ketercapaian_indikator')
            ->with('tujuanPembelajaran.indikatorPenilaians')
            ->get()
            ->keyBy('id_tujuan_pembelajaran');

        $penilaianIduka = Penilaian::where('users_id', $id)
            ->where('jenis_penilaian', 'instruktur_iduka')
            ->whereNull('ketercapaian_indikator')
            ->with('tujuanPembelajaran.indikatorPenilaians')
            ->get()
            ->keyBy('id_tujuan_pembelajaran');

        // Ambil ketercapaian per TP, urutkan by id agar urutan indikator konsisten
        $ketercapaianByTp = [];
        $ketercapaianRows = Penilaian::where('users_id', $id)
            ->whereNotNull('ketercapaian_indikator')
            ->orderBy('id_tujuan_pembelajaran')
            ->orderBy('id')
            ->get();

        foreach ($ketercapaianRows as $row) {
            $ketercapaianByTp[$row->id_tujuan_pembelajaran][] = $row->ketercapaian_indikator;
        }

        $allTujuanIds = $penilaianGuru->keys()->merge($penilaianIduka->keys())->unique();

        $nilaiGuru  = round($penilaianGuru->avg('nilai')  ?? 0, 2);
        $nilaiIduka = round($penilaianIduka->avg('nilai') ?? 0, 2);
        $nilaiAkhir = round(($nilaiGuru + $nilaiIduka) / 2, 2);

        $predikat = 'Cukup';
        if ($nilaiAkhir >= 86)     $predikat = 'Sangat Baik';
        elseif ($nilaiAkhir >= 71) $predikat = 'Baik';

        $groups    = [];
        $tpCounter = 0;

        foreach ($allTujuanIds as $tpId) {
            $g  = $penilaianGuru->get($tpId);
            $i  = $penilaianIduka->get($tpId);
            $tp = $g ? $g->tujuanPembelajaran : ($i ? $i->tujuanPembelajaran : null);
            if (!$tp) continue;

            $tpCounter++;
            $indikators   = $tp->indikatorPenilaians ?? collect();
            $ketercapaian = $ketercapaianByTp[$tpId] ?? [];
            $indRows      = [];

            foreach ($indikators as $idx => $indikator) {
                // Ambil nilai mentah dari DB, format jadi 'Ya' / 'Tidak' / '-'
                $raw = isset($ketercapaian[$idx]) ? strtolower(trim($ketercapaian[$idx])) : null;

                if ($raw === 'ya' || $raw === '1' || $raw === 'true') {
                    $labelKetercapaian = 'Ya';
                } elseif ($raw === 'tidak' || $raw === '0' || $raw === 'false') {
                    $labelKetercapaian = 'Tidak';
                } else {
                    $labelKetercapaian = '-';
                }

                $indRows[] = [
                    'no'           => $tpCounter . '.' . ($idx + 1),
                    'indikator'    => $indikator->indikator,
                    'ketercapaian' => $labelKetercapaian,
                ];
            }

            $groups[] = [
                'no'         => (string) $tpCounter,
                'tujuan'     => $tp->tujuan_pembelajaran,
                'nilaiIduka' => $i ? (string) $i->nilai : '',
                'nilaiGuru'  => $g ? (string) $g->nilai : '',
                'deskripsi'  => $g ? ($g->deskripsi ?? '') : ($i ? ($i->deskripsi ?? '') : ''),
                'skorLabel'  => 'Skor ' . $tpCounter,
                'indikators' => $indRows,
            ];
        }

        $dataJson = json_encode([
            'nama'       => $siswa->name,
            'konke'      => $namaKonke,
            'iduka'      => $namaIduka,
            'instruktur' => $namaInstruktur,
            'guru'       => $namaGuru,
            'groups'     => $groups,
            'nilaiGuru'  => $nilaiGuru,
            'nilaiIduka' => $nilaiIduka,
            'nilaiAkhir' => $nilaiAkhir,
            'predikat'   => $predikat,
        ], JSON_UNESCAPED_UNICODE);

        $safeName   = preg_replace('/[^a-zA-Z0-9_]/', '_', $siswa->name);
        $dataFile   = storage_path('app/export_data_'      . $safeName . '.json');
        $scriptPath = storage_path('app/gen_lembar_'       . $safeName . '.cjs');
        $outputPath = storage_path('app/Lembar_Penilaian_' . $safeName . '.docx');

        $projectRoot = base_path();
        $nodeModules = $projectRoot . '/node_modules';

        file_put_contents($dataFile,   $dataJson);
        file_put_contents($scriptPath, $this->getNodeScript($dataFile, $outputPath));

        $cmd = 'NODE_PATH=' . escapeshellarg($nodeModules) . ' node ' . escapeshellarg($scriptPath) . ' 2>&1';
        exec($cmd, $out, $code);

        @unlink($dataFile);
        @unlink($scriptPath);

        if ($code !== 0 || !file_exists($outputPath)) {
            return redirect()->back()->with('error', 'Gagal generate dokumen: ' . implode("\n", $out));
        }

        while (ob_get_level()) {
            ob_end_clean();
        }

        return response()->download($outputPath, 'Lembar_Penilaian_' . $siswa->name . '.docx')
            ->deleteFileAfterSend(true);
    }

    private function getNodeScript(string $dataFile, string $outputPath): string
    {
        $header  = "const DATA_FILE   = " . json_encode($dataFile)   . ";\n";
        $header .= "const OUTPUT_PATH = " . json_encode($outputPath) . ";\n\n";

        $body = <<<'JS'
'use strict';
const fs   = require('fs');
const path = require('path');
const os   = require('os');
const { execSync } = require('child_process');

const data = JSON.parse(fs.readFileSync(DATA_FILE, 'utf8'));

function x(s) {
    return String(s ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function tc(w, text, opts = {}) {
    const sz   = opts.sz  || 19;
    const al   = opts.al  || 'left';
    const va   = opts.va  || 'center';
    const bold = opts.bold ? '<w:b/>' : '';

    const border_style = opts.nb
        ? '<w:top w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:left w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:bottom w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:right w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/>'
        : '<w:top w:val="single" w:sz="6" w:space="0" w:color="000000"/><w:left w:val="single" w:sz="6" w:space="0" w:color="000000"/><w:bottom w:val="single" w:sz="6" w:space="0" w:color="000000"/><w:right w:val="single" w:sz="6" w:space="0" w:color="000000"/>';

    let tcPr = `<w:tcW w:w="${w}" w:type="dxa"/>`;
    if (opts.gs > 1)              tcPr += `<w:gridSpan w:val="${opts.gs}"/>`;
    if (opts.vm === 'restart')    tcPr += `<w:vMerge w:val="restart"/>`;
    else if (opts.vm === 'cont')  tcPr += `<w:vMerge/>`;
    if (opts.bg)                  tcPr += `<w:shd w:val="clear" w:color="auto" w:fill="${opts.bg}"/>`;
    tcPr += `<w:tcBorders>${border_style}</w:tcBorders>`;
    tcPr += `<w:tcMar><w:top w:w="55" w:type="dxa"/><w:left w:w="100" w:type="dxa"/><w:bottom w:w="55" w:type="dxa"/><w:right w:w="100" w:type="dxa"/></w:tcMar>`;
    tcPr += `<w:vAlign w:val="${va}"/>`;

    const jc   = al === 'center' ? '<w:jc w:val="center"/>' : '';
    const font = `<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>`;

    return `<w:tc><w:tcPr>${tcPr}</w:tcPr>` +
           `<w:p><w:pPr>${jc}<w:spacing w:before="0" w:after="0"/></w:pPr>` +
           `<w:r><w:rPr>${bold}${font}<w:sz w:val="${sz}"/><w:szCs w:val="${sz}"/></w:rPr>` +
           `<w:t xml:space="preserve">${x(text)}</w:t></w:r></w:p></w:tc>`;
}

function tr_row(cells_xml, is_header = false) {
    const trPr = is_header ? '<w:trPr><w:tblHeader/></w:trPr>' : '';
    return `<w:tr>${trPr}${cells_xml}</w:tr>`;
}

const G   = [468, 2252, 451, 1383, 1113, 229, 764, 2691];
const TW  = G.reduce((a,b) => a+b, 0);

const WNO   = G[0];
const WTP   = G[1]+G[2];
const WKET  = G[3];
const WIDUK = G[4];
const WGURU = G[5]+G[6];
const WDESC = G[7];
const WTP3  = G[1]+G[2]+G[3];
const WSKOR = G[0]+G[1]+G[2]+G[3];

const hdr1 = tr_row(
    tc(WNO,   'No',                              {gs:1,  vm:'restart', bg:'D9D9D9', bold:true, al:'center'}) +
    tc(WTP,   'Tujuan Pembelajaran / Indikator', {gs:2,  vm:'restart', bg:'D9D9D9', bold:true, al:'center'}) +
    tc(WKET,  'Ketercapaian Ya/Tidak',           {gs:1,  vm:'restart', bg:'D9D9D9', bold:true, al:'center'}) +
    tc(WIDUK, 'Instruktur Iduka',                {gs:1,               bg:'D9D9D9', bold:true, al:'center'}) +
    tc(WGURU, 'Guru Pembimbing',                 {gs:2,               bg:'D9D9D9', bold:true, al:'center'}) +
    tc(WDESC, 'Deskripsi',                       {gs:1,  vm:'restart', bg:'D9D9D9', bold:true, al:'center'}),
    true
);

const hdr2 = tr_row(
    tc(WNO,   '',               {gs:1, vm:'cont', bg:'D9D9D9'}) +
    tc(WTP,   '',               {gs:2, vm:'cont', bg:'D9D9D9'}) +
    tc(WKET,  '',               {gs:1, vm:'cont', bg:'D9D9D9'}) +
    tc(WIDUK, 'Nilai\n(0-100)', {gs:1, bg:'D9D9D9', bold:true, al:'center', sz:18}) +
    tc(WGURU, 'Nilai\n(0-100)', {gs:2, bg:'D9D9D9', bold:true, al:'center', sz:18}) +
    tc(WDESC, '',               {gs:1, vm:'cont', bg:'D9D9D9'}),
    true
);

let dataRowsXml = '';

for (const grp of data.groups) {
    dataRowsXml += tr_row(
        tc(WNO,   grp.no,         {vm:'restart', al:'center', bold:true}) +
        tc(WTP3,  grp.tujuan,     {gs:3, va:'top'}) +
        tc(WIDUK, grp.nilaiIduka, {vm:'restart', al:'center'}) +
        tc(WGURU, grp.nilaiGuru,  {gs:2, vm:'restart', al:'center'}) +
        tc(WDESC, grp.deskripsi,  {vm:'restart'})
    );

    for (const ind of grp.indikators) {
        // ✅ FIX: tampilkan nilai ketercapaian dari data (Ya / Tidak / -)
        // bukan lagi hardcode 'Ya/Tidak*)'
        dataRowsXml += tr_row(
            tc(WNO,   '',                   {vm:'cont'}) +
            tc(WTP,   ind.indikator,        {gs:2}) +
            tc(WKET,  ind.ketercapaian,     {al:'center'}) +
            tc(WIDUK, '',                   {vm:'cont'}) +
            tc(WGURU, '',                   {gs:2, vm:'cont'}) +
            tc(WDESC, '',                   {vm:'cont'})
        );
    }

    dataRowsXml += tr_row(
        tc(WSKOR, grp.skorLabel, {gs:4, bg:'D9D9D9', bold:true}) +
        tc(WIDUK, '',            {bg:'D9D9D9'}) +
        tc(WGURU, '',            {gs:2, bg:'D9D9D9'}) +
        tc(WDESC, '',            {bg:'D9D9D9'})
    );
}

function iRow(lbl, val) {
    const font = `<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>`;
    function icell(w, txt) {
        return `<w:tc><w:tcPr><w:tcW w:w="${w}" w:type="dxa"/>` +
               `<w:tcBorders><w:top w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:left w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:bottom w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:right w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/></w:tcBorders>` +
               `<w:tcMar><w:top w:w="36" w:type="dxa"/><w:left w:w="0" w:type="dxa"/><w:bottom w:w="36" w:type="dxa"/><w:right w:w="0" w:type="dxa"/></w:tcMar></w:tcPr>` +
               `<w:p><w:pPr><w:spacing w:before="0" w:after="0"/></w:pPr>` +
               `<w:r><w:rPr>${font}<w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t xml:space="preserve">${x(txt)}</w:t></w:r></w:p></w:tc>`;
    }
    const w2 = TW - 2694 - 283;
    return `<w:tr>${icell(2694, lbl)}${icell(283, ' : ')}${icell(w2, val)}</w:tr>`;
}

const identitasXml =
    iRow('Nama Peserta Didik',   data.nama       || '') +
    iRow('Konsentrasi Keahlian', data.konke      || '') +
    iRow('IDUKA Tempat PKL',     data.iduka      || '') +
    iRow('Nama Instruktur',      data.instruktur || '') +
    iRow('Nama Guru Pembimbing', data.guru       || '');

function para(text, opts = {}) {
    const sz   = opts.sz  || 19;
    const al   = opts.al  || 'left';
    const bold = opts.bold ? '<w:b/>' : '';
    const ital = opts.ital ? '<w:i/>' : '';
    const jc   = al === 'center' ? '<w:jc w:val="center"/>' : (al === 'right' ? '<w:jc w:val="right"/>' : '');
    const after = opts.after !== undefined ? opts.after : 0;
    const font = `<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>`;
    return `<w:p><w:pPr>${jc}<w:spacing w:before="0" w:after="${after}"/></w:pPr>` +
           `<w:r><w:rPr>${bold}${ital}${font}<w:sz w:val="${sz}"/><w:szCs w:val="${sz}"/></w:rPr>` +
           `<w:t xml:space="preserve">${x(text)}</w:t></w:r></w:p>`;
}

const half = Math.floor(TW / 2);
function dotLine() {
    return `<w:p><w:pPr><w:spacing w:before="0" w:after="40"/></w:pPr>` +
           `<w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr>` +
           `<w:t>\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026</w:t></w:r></w:p>`;
}
function noteCell(w, label) {
    const font = `<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>`;
    const bdr  = `<w:tcBorders><w:top w:val="single" w:sz="6" w:space="0" w:color="000000"/><w:left w:val="single" w:sz="6" w:space="0" w:color="000000"/><w:bottom w:val="single" w:sz="6" w:space="0" w:color="000000"/><w:right w:val="single" w:sz="6" w:space="0" w:color="000000"/></w:tcBorders>`;
    const head = `<w:p><w:pPr><w:spacing w:before="0" w:after="0"/></w:pPr><w:r><w:rPr><w:b/>${font}<w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t>${label}</w:t></w:r></w:p>`;
    return `<w:tc><w:tcPr><w:tcW w:w="${w}" w:type="dxa"/>${bdr}` +
           `<w:tcMar><w:top w:w="80" w:type="dxa"/><w:left w:w="120" w:type="dxa"/><w:bottom w:w="320" w:type="dxa"/><w:right w:w="120" w:type="dxa"/></w:tcMar></w:tcPr>` +
           head + dotLine() + dotLine() + dotLine() + `</w:tc>`;
}
const catatanXml = `<w:tr>${noteCell(half,'Catatan Guru Pembimbing:')}${noteCell(TW-half,'Catatan Instruktur IDUKA:')}</w:tr>`;

const w1 = 3400, w2 = TW - 3400;
function nilaiRow(label, value, boldVal = false) {
    const font      = `<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>`;
    const nb        = `<w:tcBorders><w:top w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:left w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:bottom w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:right w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/></w:tcBorders>`;
    const bold_open = boldVal ? '<w:b/>' : '';
    return `<w:tr>` +
           `<w:tc><w:tcPr><w:tcW w:w="${w1}" w:type="dxa"/>${nb}</w:tcPr><w:p><w:pPr><w:spacing w:before="0" w:after="0"/></w:pPr><w:r><w:rPr><w:b/>${font}<w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t xml:space="preserve">${x(label)}</w:t></w:r></w:p></w:tc>` +
           `<w:tc><w:tcPr><w:tcW w:w="${w2}" w:type="dxa"/>${nb}</w:tcPr><w:p><w:pPr><w:spacing w:before="0" w:after="0"/></w:pPr><w:r><w:rPr>${bold_open}${font}<w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t xml:space="preserve">${x(value)}</w:t></w:r></w:p></w:tc>` +
           `</w:tr>`;
}
const nilaiXml =
    nilaiRow('Nilai Instruktur Iduka', '= Rata-rata nilai seluruh indikator  =  ' + data.nilaiIduka) +
    nilaiRow('Nilai Guru Pembimbing',  '= Rata-rata nilai seluruh indikator  =  ' + data.nilaiGuru) +
    nilaiRow('Nilai Akhir PKL',        '= (Nilai Instruktur Iduka + Nilai Guru Pembimbing) / 2') +
    nilaiRow('', '= (' + data.nilaiIduka + ' + ' + data.nilaiGuru + ') / 2  =  ' + data.nilaiAkhir, true);

function rtRow(r, p, isHeader = false) {
    const bg   = isHeader ? `<w:shd w:val="clear" w:color="auto" w:fill="D9D9D9"/>` : '';
    const bld  = isHeader ? '<w:b/>' : '';
    const font = `<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>`;
    const bdr  = `<w:tcBorders><w:top w:val="single" w:sz="6" w:space="0" w:color="000000"/><w:left w:val="single" w:sz="6" w:space="0" w:color="000000"/><w:bottom w:val="single" w:sz="6" w:space="0" w:color="000000"/><w:right w:val="single" w:sz="6" w:space="0" w:color="000000"/></w:tcBorders>`;
    function rcell(w, txt, al = 'left') {
        const jc = al === 'center' ? '<w:jc w:val="center"/>' : '';
        return `<w:tc><w:tcPr><w:tcW w:w="${w}" w:type="dxa"/>${bg}${bdr}</w:tcPr>` +
               `<w:p><w:pPr>${jc}<w:spacing w:before="0" w:after="0"/></w:pPr>` +
               `<w:r><w:rPr>${bld}${font}<w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t xml:space="preserve">${x(txt)}</w:t></w:r></w:p></w:tc>`;
    }
    return `<w:tr>${rcell(1400, r, 'center')}${rcell(1400, p)}</w:tr>`;
}
const rentangXml =
    rtRow('Rentang Nilai', 'Predikat', true) +
    rtRow('86 \u2013 100', 'Sangat Baik') +
    rtRow('71 \u2013 85',  'Baik') +
    rtRow('56 \u2013 70',  'Cukup');

function tbl(colWidths, rowsXml, opts = {}) {
    const totalW   = colWidths.reduce((a,b) => a+b, 0);
    const gridCols = colWidths.map(w => `<w:gridCol w:w="${w}"/>`).join('');
    return `<w:tbl>` +
           `<w:tblPr><w:tblW w:w="${totalW}" w:type="dxa"/>` +
           (opts.noTblBorder
               ? `<w:tblBorders><w:top w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:left w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:bottom w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:right w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:insideH w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/><w:insideV w:val="none" w:sz="0" w:space="0" w:color="FFFFFF"/></w:tblBorders>`
               : '') +
           `<w:tblLook w:val="0000"/></w:tblPr>` +
           `<w:tblGrid>${gridCols}</w:tblGrid>` +
           rowsXml +
           `</w:tbl>`;
}

function ttdPara(left, right) {
    const font = `<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>`;
    return `<w:p><w:pPr><w:tabs><w:tab w:val="right" w:pos="${TW}"/></w:tabs><w:spacing w:before="0" w:after="0"/></w:pPr>` +
           `<w:r><w:rPr>${font}<w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t xml:space="preserve">${x(left)}</w:t></w:r>` +
           `<w:r><w:rPr>${font}<w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:tab/></w:r>` +
           `<w:r><w:rPr>${font}<w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t xml:space="preserve">${x(right)}</w:t></w:r>` +
           `</w:p>`;
}

const bodyContent =
    `<w:p><w:pPr><w:jc w:val="center"/><w:spacing w:before="0" w:after="160"/></w:pPr>` +
    `<w:r><w:rPr><w:b/><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="28"/><w:szCs w:val="28"/></w:rPr>` +
    `<w:t>LEMBAR OBSERVASI DAN PENILAIAN</w:t></w:r></w:p>` +

    tbl([2694, 283, TW-2977], identitasXml, {noTblBorder:true}) +
    para('', {after:80}) +

    tbl(G, hdr1 + hdr2 + dataRowsXml) +
    para('', {after:80}) +

    tbl([half, TW-half], catatanXml) +
    para('', {after:100}) +

    tbl([w1, w2], nilaiXml, {noTblBorder:true}) +
    para('', {after:80}) +

    `<w:p><w:pPr><w:spacing w:before="0" w:after="100"/></w:pPr>` +
    `<w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t xml:space="preserve">dengan predikat:   </w:t></w:r>` +
    `<w:r><w:rPr><w:b/><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t xml:space="preserve">${x(data.predikat)}</w:t></w:r>` +
    `<w:r><w:rPr><w:i/><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="19"/><w:szCs w:val="19"/></w:rPr><w:t xml:space="preserve">   (Sangat Baik / Baik / Cukup)</w:t></w:r></w:p>` +

    para('Rentang Nilai & Kategori', {bold:true, after:60}) +
    tbl([1400, 1400], rentangXml) +
    para('', {after:80}) +

    para('\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026\u2026, 2026', {al:'right', after:80}) +

    ttdPara('Guru Pembimbing,', 'Instruktur IDUKA,') +
    para('') + para('') + para('') + para('') +
    ttdPara(data.guru || '(..............................)', '(......................................)') +
    para('NIP. ..............................');

const documentXml = `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
            xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
            xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
            xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
            xmlns:v="urn:schemas-microsoft-com:vml"
            xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
            xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
            xmlns:w10="urn:schemas-microsoft-com:office:word"
            xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
            xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
            xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
            xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
            xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
            xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape"
            mc:Ignorable="w14 w15 wp14">
  <w:body>
    ${bodyContent}
    <w:sectPr>
      <w:pgSz w:w="12242" w:h="18722"/>
      <w:pgMar w:top="1134" w:right="1440" w:bottom="1134" w:left="1440" w:header="708" w:footer="708" w:gutter="0"/>
    </w:sectPr>
  </w:body>
</w:document>`;

const contentTypes = `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml"  ContentType="application/xml"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml"   ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
  <Override PartName="/word/settings.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.settings+xml"/>
</Types>`;

const relsMain = `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>`;

const relsWord = `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles"   Target="styles.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings" Target="settings.xml"/>
</Relationships>`;

const stylesXml = `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
          xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <w:docDefaults>
    <w:rPrDefault>
      <w:rPr>
        <w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>
        <w:sz w:val="19"/><w:szCs w:val="19"/>
        <w:lang w:val="id-ID" w:eastAsia="id-ID" w:bidi="ar-SA"/>
      </w:rPr>
    </w:rPrDefault>
  </w:docDefaults>
  <w:style w:type="paragraph" w:default="1" w:styleId="Normal">
    <w:name w:val="Normal"/>
    <w:pPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/></w:pPr>
  </w:style>
</w:styles>`;

const settingsXml = `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:settings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:defaultTabStop w:val="720"/>
</w:settings>`;

const tmpDir = fs.mkdtempSync(path.join(os.tmpdir(), 'docx_'));
fs.mkdirSync(path.join(tmpDir, '_rels'));
fs.mkdirSync(path.join(tmpDir, 'word'));
fs.mkdirSync(path.join(tmpDir, 'word', '_rels'));

fs.writeFileSync(path.join(tmpDir, '[Content_Types].xml'), contentTypes);
fs.writeFileSync(path.join(tmpDir, '_rels', '.rels'), relsMain);
fs.writeFileSync(path.join(tmpDir, 'word', 'document.xml'), documentXml);
fs.writeFileSync(path.join(tmpDir, 'word', '_rels', 'document.xml.rels'), relsWord);
fs.writeFileSync(path.join(tmpDir, 'word', 'styles.xml'), stylesXml);
fs.writeFileSync(path.join(tmpDir, 'word', 'settings.xml'), settingsXml);

const zipCmd = `cd "${tmpDir}" && zip -r "${OUTPUT_PATH}" . -x "*.DS_Store"`;
execSync(zipCmd, { stdio: 'pipe' });

fs.rmSync(tmpDir, { recursive: true, force: true });

console.log('OK');
JS;

        return $header . $body;
    }
}
