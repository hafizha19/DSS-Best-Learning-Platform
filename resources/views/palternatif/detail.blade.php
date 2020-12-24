@extends('layouts.app')

@section('title')
Tambah Kriteria
@endsection
@section('header')
Detail Penilaian Alternatif
@endsection
@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <a href="{{ Route('pkriteria.index') }}" class="btn btn-danger">Kembali</a>
</div>

<div class="container-fluid">
    @if ( $status = \Session::get('sukses'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success d-block">{{ $status }}</div>
        </div>
    </div>
    @endif

    <div class="row  justify-content-center">

        <!-- Page Heading -->
        <h2 class="h3 mb-4 text-gray-800">Proses Penilaian Kriteria</h2>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class=" row justify-content-center">

                        <h5>Hasil Eigen Vector/Nilai Per-Kriteria</h5>
                    </div>
                </div>
                {{-- @dd(\DB::table('kriteria')->select('nama')->where('id','=',$data[0]->id_kriteria_1)->where('user_id','=', Auth::user()->id)->first()->nama) --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Kriteria</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $d)
                                            
                                        <tr>
                                            <td>
                                                <p>{{\DB::table('alternatif')->select('nama')->where('id','=',$d->id)->first()->nama}}</p>
                                            </td>
                                            <td>
                                                <p>
                                                    {{ $d->eigen }}
                                                </p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection