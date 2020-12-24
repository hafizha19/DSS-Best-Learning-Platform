@extends('layouts.app')

@section('title')
Kriteria
@endsection

@section('title')
Bobot
@endsection

@section('content')
{{-- @php
    $k = \App\Kriteria::where('user_id', '=', Auth::user()->id)->get();    
@endphp
@dump($k->pluck('id')->values()->toArray()) --}}
<div class="container-fluid">

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="row justify-content-center mt-3">
        <div class="col-lg-12">

            <div class="row justify-content-center mb-3">
                <div class="col-lg-12">
                    <form method="POST" action="{{route('bobot.store')}}">
                        @csrf
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="nama" class="sr-only">Bobot</label>
                            <input class="form-control" id="nama" placeholder="Masukkan bobot" name="bobot" required>
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="deskripsi" class="sr-only">Deskripsi</label>
                            <input class="form-control" id="nama" placeholder="Masukkan deskripsi" name="deskripsi"
                                required>
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <input type="submit" class="btn btn-primary" value="Tambahkan">
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <div class=" row justify-content-center">
                                <h5>Nilai Bobot</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Bobot</th>
                                            <th>Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $d)
                                        <tr>
                                            <td>
                                                {{$d->bobot}}
                                            </td>
                                            <td>
                                                {{$d->deskripsi}}
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