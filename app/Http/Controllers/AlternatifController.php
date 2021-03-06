<?php

namespace App\Http\Controllers;

use App\Alternatif;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlternatifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Alternatif::all();

        return view('alternatif.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('kriteria.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Alternatif();

        $model->nama = $request->nama;

        // if ($model::where('user_id', '=', Auth::user()->id)->count() < 3) {
        try {
            $model->save();
        // } else {
        } catch (Exception $e) {
            return back()->withError($e)->withInput();
        }

        return redirect()->route('alternatif.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kriteria  $kriteria
     * @return \Illuminate\Http\Response
     */
    public function show(Alternatif $alternatif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kriteria  $kriteria
     * @return \Illuminate\Http\Response
     */
    public function edit(Alternatif $alternatif)
    {
        return view('alternatif.add', compact('alternatif'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kriteria  $kriteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alternatif $alternatif)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kriteria  $kriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alternatif $alternatif)
    {
        $rk = Alternatif::findOrFail($alternatif->id);
        $rk->delete();

        return redirect()->route('alternatif.index');
    }
}
