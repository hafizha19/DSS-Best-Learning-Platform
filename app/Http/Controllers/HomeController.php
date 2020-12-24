<?php

namespace App\Http\Controllers;

use App\Alternatif;
use App\Kriteria;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        $ks = Kriteria::all();
        $kriteria['key'] = [];
        $kriteria['eigen'] = [];

        foreach($ks as $k){
            array_push($kriteria['key'], $k->nama);
            array_push($kriteria['eigen'], $k->eigen);
        }

        $as = Alternatif::all();
        $alternatif['key'] = [];
        $alternatif['eigen'] = [];

        foreach($as as $a){
            array_push($alternatif['key'], $a->nama);
            array_push($alternatif['eigen'], $a->eigen);
        }
        // dd($kriteria);
        return view('dashboard', compact(['kriteria', 'alternatif']));
    }

    
    public function alternatif()
    {
        return view('form.alternatif');
    }
}
