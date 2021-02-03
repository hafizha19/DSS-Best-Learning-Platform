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
use \Phpml\Math\Matrix;

class PAlternatifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function cetakMatriks(array $arr){
        echo "<table border='1' cellspacing='0' cellpadding='10'>";
        for ($i=0; $i<sizeof($arr); $i++) {
            echo "<tr>";
            for ($j=0; $j<sizeof($arr[$i]); $j++) {
                echo "<td>". round($arr[$i][$j], 100) ."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
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

    public function nilai(int $a, int $b, int $k)
    {
        $nilai = \DB::table('nilai_alternatif')->select('nilai')
            // ->where('user_id','=', Auth::user()->id)
            ->where('id_alternatif_1', '=', $a)
            ->where('id_alternatif_2', '=', $b)
            ->where('id_kriteria', '=', $k)
            ->first()
            ->nilai;

        $eval = eval('return ' . $nilai . ';');
        return $eval;
    }

    public function eigenVector()
    {
        // ambil id alternatif terkait
        $as = \DB::table('nilai_alternatif')
            ->select('id_alternatif_1')
            ->orderBy('id_alternatif_1', 'asc')
            ->groupBy('id_alternatif_1')
            ->pluck('id_alternatif_1')
            ->toArray();
        $bs = \DB::table('nilai_alternatif')
            ->select('id_alternatif_2')
            ->orderBy('id_alternatif_2', 'asc')
            ->groupBy('id_alternatif_2')
            ->pluck('id_alternatif_2')
            ->toArray();

        $ks = \DB::table('nilai_kriteria')
            ->select('id_kriteria_2')
            ->orderBy('id_kriteria_2', 'asc')
            ->groupBy('id_kriteria_2')
            ->pluck('id_kriteria_2')
            ->toArray();

        // ambil nilai eigen vector kriteria 
        $nks[] = \DB::table('kriteria')
        ->select('eigen')
        ->orderBy('id', 'asc')
        ->pluck('eigen')
        ->toArray();

        // buat matriks m (3x3) alternatif1 vs alternatif2 per kriteria
        $mk = array(); // matriks kriteria
        $m = array(); // perbaris alternatif vs alternatif
        $temp = array();
        foreach ($ks as $k) {
            $m = [];
            foreach ($as as $a) {
                $temp = [];
                foreach ($bs as $b) {
                    $nilai = $this->nilai($a, $b, $k);
                    array_push($temp, $nilai);
                }
                array_push($m, $temp);
            }
            // $mk[$k] = $m;
            array_push($mk, $m);
        }

        
        // kali matriks
        $kali = [];
        foreach ($mk as $mkk) {
            $k = $this->perkalian_matriks($mkk, $mkk);
            array_push($kali, $k);
        }
        
        
        // jumlah perbaris simpan di matriks b (3x1)
        $b = array();
        for ($i = 0; $i < count($kali); $i++) {
            for ($j = 0; $j < count($bs); $j++) {
                $b[$ks[$i]][$as[$j]] = array_sum($kali[$i][$j]);
            }
        }
        // $this->cetakMatriks($b[6]);

        // dd($b);
        // rangking, sum baris / sum all
        $rs = array();
        for ($i = 0; $i < count($kali); $i++) {
            for ($j = 0; $j < count($bs); $j++) {
                $rs[$i][$j] = $b[$ks[$i]][$as[$j]] / array_sum($b[$ks[$i]]);
                // $rs[$ks[$i]][$as[$j]] = $b[$ks[$i]][$as[$j]] / array_sum($b[$ks[$i]]);
            }
            
        }

        // $this->cetakMatriks($rs);

        // transpose matrix using lib phpml
        $rs_matrix = new Matrix($rs);
        $rs_trans = ($rs_matrix->transpose())->toArray();
        
        // $this->cetakMatriks($rs_trans);
        
        // tranpose nilai eigen kriteria
        $nks_matrix = new Matrix($nks);
        $nks_trans = ($nks_matrix->transpose())->toArray();

        // $this->cetakMatriks($nks_trans);

        $nks_rs = $this->perkalian_matriks($rs_trans, $nks_trans);
        // $this->cetakMatriks($nks_rs);

        // simpen ke db
        for ($i=0; $i < count($nks_rs); $i++) {
            $alternatif = Alternatif::find($as[$i]);
            $alternatif->eigen = $nks_rs[$i][0];
            $alternatif->save();
        }

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
            } catch (Exception $e) {
                return Redirect::back()->withErrors($e);
            }
        } else {
            $model->id_kriteria = $request->id_kriteria;
            $model->id_alternatif_1 = $request->id_alternatif_1;
            $model->id_alternatif_2 = $request->id_alternatif_2;
            $model->nilai = $request->nilai;

            try {
                $model->save();
            } catch (Exception $e) {
                return Redirect::back()->withErrors($e);
            }
        }

        // todo : kalo udah ada, kasi error

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
        $rk2 = PAlternatif::where('id_kriteria', $rk->id_kriteria)
        ->where('id_alternatif_1', $rk->id_alternatif_2)
        ->where('id_alternatif_2', $rk->id_alternatif_1);
        try {
            $rk->delete();
            $rk2->delete();
        } catch (Exception $e) {
            return back()->withError($e)->withInput();
        }

        return redirect()->route('palternatif.index');
    }
}
