<?php

namespace App\Http\Controllers;

use App\Kriteria;
use App\PKriteria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PKriteriaController extends Controller
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
        $nilai = PKriteria::all();
        return view('pkriteria.index', compact('nilai'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail()
    {
        $this->eigenVector();
        $data = Kriteria::all();
        $nilai = PKriteria::all();
        return view('pkriteria.detail', compact(['data', 'nilai']));
    }

    public function store(Request $request)
    {
        $model = new PKriteria();

        // $model->id_kriteria_1 = $request->id_kriteria_1;
        // $model->id_kriteria_2 = $request->id_kriteria_2;
        // $model->nilai = $request->nilai;

        // try {
        //     $model->save();
        // } catch (\Exception $e) {
        //     return $e->getMessage();
        // }

        $nilai = $request->nilai;
        if ($nilai[0] != $nilai[2]) {
            //     $model->id_kriteria_1 = $request->id_kriteria_2;
            //     $model->id_kriteria_2 = $request->id_kriteria_1;
            //     $model->nilai = $nilai[2].'/'.$nilai[0];
            //     $model->save();
            \DB::table('nilai_kriteria')->insert([
                [
                    'id_kriteria_1' => $request->id_kriteria_1,
                    'id_kriteria_2' => $request->id_kriteria_2,
                    'nilai' => $nilai
                ],
                [
                    'id_kriteria_1' => $request->id_kriteria_2,
                    'id_kriteria_2' => $request->id_kriteria_1,
                    'nilai' => $nilai[2] . '/' . $nilai[0]
                ],
            ]);
        } else {
            $model->id_kriteria_1 = $request->id_kriteria_1;
            $model->id_kriteria_2 = $request->id_kriteria_2;
            $model->nilai = $request->nilai;

            $model->save();
        }


        return redirect()
            ->back()
            ->with('sukses', 'Penilaiaan Kriteria Berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kriteria  $kriteria
     * @return \Illuminate\Http\Response
     */
    public function show(PKriteria $pkriteria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kriteria  $pkriteria
     * @return \Illuminate\Http\Response
     */
    public function edit(PKriteria $pkriteria)
    {
        return view('kriteria.add', compact('kriteria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kriteria  $pkriteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PKriteria $pkriteria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kriteria  $pkriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(PKriteria $pkriteria)
    {
        $rk = PKriteria::findOrFail($pkriteria->id);
        $rk2 = PKriteria::where('id_kriteria_1', $rk->id_kriteria_2)
        ->where('id_kriteria_2', $rk->id_kriteria_1);
        try {
            $rk->delete();
            $rk2->delete();
        } catch (Exception $e) {
            return back()->withError($e)->withInput();
        }

        return redirect()->route('pkriteria.index');
    }
}
