<?php

namespace App\Http\Controllers;

use App\Alternatif;
use App\Kriteria;
use App\PAlternatif;
use App\PKriteria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PAlternatifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function perkalian_matriks($matriks_a, $matriks_b)
    {
        $hasil = array();
        for ($i = 0; $i < sizeof($matriks_a); $i++) {
            for ($j = 0; $j < sizeof($matriks_b[0]); $j++) {
                $temp = 0;
                for ($k = 0; $k < sizeof($matriks_b); $k++) {
                    $temp += $matriks_a[$i][$k] * $matriks_b[$k][$j];
                }
                $hasil[$i][$j] = $temp;
            }
        }
        return $hasil;
    }

    public function nilai(int $a, int $b)
    {
        $nilai = \DB::table('nilai_kriteria')->select('nilai')
            // ->where('user_id','=', Auth::user()->id)
            ->where('id_kriteria_1', '=', $a)
            ->where('id_kriteria_2', '=', $b)
            ->first()
            ->nilai;

        $eval = eval('return ' . $nilai . ';');
        return $eval;
    }

    public function eigenVector()
    {
        // ambil id kriteria terkait
        $as = \DB::table('nilai_kriteria')
            ->select('id_kriteria_1')
            ->orderBy('id_kriteria_1', 'asc')
            ->groupBy('id_kriteria_1')
            ->pluck('id_kriteria_1')
            ->toArray();
        $bs = \DB::table('nilai_kriteria')
            ->select('id_kriteria_2')
            ->orderBy('id_kriteria_2', 'asc')
            ->groupBy('id_kriteria_2')
            ->pluck('id_kriteria_2')
            ->toArray();

        // buat matriks m (3x3)
        $m = array();
        $temp = array();
        foreach ($as as $a) {
            $temp = [];
            foreach ($bs as $b) {
                $nilai = $this->nilai($a, $b);
                array_push($temp, $nilai);
            }
            array_push($m, $temp);
            // $m[$a] = $temp;
        }
        // kali matriks
        $kali = $this->perkalian_matriks($m, $m);

        // jumlah perbaris simpan di matriks b (3x1)
        $b = array();
        for ($i = 0; $i < count($kali); $i++) {
            $b[$as[$i]] = array_sum($kali[$i]);
        }

        // rangking, sum baris / sum all
        $rs = array();
        for ($i = 0; $i < count($kali); $i++) {
            $rs[$as[$i]] = $b[$as[$i]] / array_sum($b);

            // simpen ke db
            $kriteria = Kriteria::find($as[$i]);
            $kriteria->eigen = $rs[$as[$i]];
            $kriteria->save();
        }

        // return $rs;
    }

    public function index()
    {
        $matriks = [];
        $nilai = PAlternatif::all();
        return view('palternatif.index', compact('nilai'));
    }

    public function detail()
    {
        $this->eigenVector();
        $data = Alternatif::all();
        $nilai = PAlternatif::all();
        return view('palternatif.detail', compact(['data', 'nilai']));
    }

    public function store(Request $request)
    {
        $model = new PAlternatif();

        $nilai = $request->nilai;
        if ($nilai[0] != $nilai[2]) {
            try {
            \DB::table('nilai_alternatif')->insert([
                [
                    'id_kriteria' => $request->id_kriteria,
                    'id_alternatif_1' => $request->id_alternatif_1,
                    'id_alternatif_2' => $request->id_alternatif_2,
                    'nilai' => $nilai
                ],
                [
                    'id_kriteria' => $request->id_kriteria,
                    'id_alternatif_1' => $request->id_alternatif_2,
                    'id_alternatif_2' => $request->id_alternatif_1,
                    'nilai' => $nilai[2] . '/' . $nilai[0]
                ],
            ]);
            } catch (Exception $e){
                return Redirect::back()->withErrors($e);
            }
        } else {
            $model->id_kriteria = $request->id_kriteria;
            $model->id_alternatif_1 = $request->id_alternatif_1;
            $model->id_alternatif_2 = $request->id_alternatif_2;
            $model->nilai = $request->nilai;

            try {
            $model->save();
            } catch (Exception $e){
                return Redirect::back()->withErrors($e);
            }
        }

        // todo

        return redirect()
            ->back()
            ->with('sukses', 'Penilaiaan Alternatif Berhasil ditambahkan!');
    }

    public function show(PAlternatif $palternatif)
    {
        //
    }

    public function edit(PAlternatif $palternatif)
    {
        return view('palternatif.add', compact('palternatif'));
    }

    public function update(Request $request, PAlternatif $palternatif)
    {
        //
    }

    public function destroy(PAlternatif $palternatif)
    {
        $rk = PAlternatif::findOrFail($palternatif->id);
        try {
            $rk->delete();
        } catch (Exception $e) {
            return back()->withError($e)->withInput();
        }

        return redirect()->route('palternatif.index');
    }
}
