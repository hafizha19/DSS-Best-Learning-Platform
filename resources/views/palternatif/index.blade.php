@extends('layouts.app')

@section('title')
Alternatif
@endsection

@section('title')
penilaian alternatif
@endsection

@section('content')
<div class="container-fluid">

    @if (session('sukses'))
    <div class="alert alert-success">{{ session('sukses') }}</div>
    @endif
    {{-- @if ($errors->any())
    <div class="alert alert-danger">{{ $error[0] }}</div>
    @endif --}}

    <div class="row  justify-content-center">

        <!-- Page Heading -->
        <h2 class="h3 mb-4 text-gray-800">Penilaian Alternatif</h2>
    </div>

    <div class="row justify-content-center mb-3">
        <div class="col-lg-12">
            <a href="{{route('palternatif.detail')}}" class="btn btn-success">Detail Penilaian Alternatif</a>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" class="row justify-content-between" action="{{route('palternatif.store')}}">
                @csrf
                <div class="col-lg-3">
                    {!! App\Helpers\AppForm::selectModel('Kriteria', 'id_kriteria', \DB::table('kriteria')
                    ->select('id','nama')->get(), "id", "nama",true) !!}
                </div>
                <div class="col-lg-3">
                    {!! App\Helpers\AppForm::selectModel('Alternatif 1', 'id_alternatif_1', \DB::table('alternatif')
                    ->select('id','nama')->get(), "id", "nama",true) !!}
                </div>
                <div class="col-lg-3">
                    {{-- {!! App\Helpers\AppForm::input('text', '','nilai',true) !!}
                     --}}
                    {!! App\Helpers\AppForm::selectModel('Nilai', 'nilai', \DB::table('bobot')
                    ->select('bobot','deskripsi')->get(), "bobot", "deskripsi",true) !!}
                </div>
                <div class="col-lg-3">
                    {!! App\Helpers\AppForm::selectModel('Alternatif 2', 'id_alternatif_2', \DB::table('alternatif')
                    ->select('id','nama')->get(), "id", "nama",true) !!}
                </div>
                <input type="submit" class="btn btn-primary btn-block" value="Tambahkan penilaian">
            </form>
        </div>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class=" row justify-content-center">

                        <h5>Penilaian Alternatif</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Alternatif 1</th>
                                    <th>Nilai</th>
                                    <th>Alternatif 2</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilai as $d)
                                <tr>
                                    <td>
                                        <p>{{\DB::table('kriteria')->select('nama')->where('id','=',$d->id_kriteria)->first()->nama}}
                                        </p>
                                    </td>
                                    <td>
                                        <p>{{\DB::table('alternatif')->select('nama')->where('id','=',$d->id_alternatif_1)->first()->nama}}
                                        </p>
                                    </td>
                                    <td>
                                        <p>{{$a = \DB::table('bobot')->select('deskripsi')->where('bobot', '=', $d->nilai)->first()->deskripsi}}</p>
                                    </td>
                                    <td>
                                        <p>{{\DB::table('alternatif')->select('nama')->where('id','=',$d->id_alternatif_2)->first()->nama}}
                                        </p>
                                    </td>
                                    <td>
                                        <form action="/penilaian-alternatif/{{$d->id}}" method="POST">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Apakah anda yakin mengahapus data ini?');">Hapus</button>
                                        </form>
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
@endsection