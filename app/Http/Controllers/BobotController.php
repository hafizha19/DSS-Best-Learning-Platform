<?php

namespace App\Http\Controllers;

use App\Bobot;
use Exception;
use Illuminate\Http\Request;

class BobotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = \DB::table('bobot')->select('*')->get();
        return view('bobot.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Bobot();

        $model->bobot = $request->bobot;
        $model->deskripsi = $request->deskripsi;

        try {
            $model->save();
        } catch (Exception $e) {
            return back()->withError($e)->withInput();
        }

        return redirect()->route('bobot.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // todo
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bobot $bobot)
    {
        $rk = Bobot::findOrFail($bobot->id);
        try {
            $rk->delete();
        } catch (Exception $e){
            return back()->withError($e)->withInput();
        }

        return redirect()->route('bobot.index');
    }
}
