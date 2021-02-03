@extends('layouts.app')

@section('title')
Dashboard
@endsection

@section('header')
Pemilihan Platfrom Pembelajaran Terbaik Selama Pandemi Covid
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-3">
        <div class="col-lg-12">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Desicion Support System: Analytic Hierarchy Process (AHP)</h1>
            <p class="mb-4"> 
                <b class='text-primary'>Decision Support System atau Sistem Pendukung Keputusan jenis AHP</b> adalah metode untuk
                memecahkan suatu situasi yang komplek tidak terstruktur kedalam beberapa komponen dalam susunan yang
                hirarki, dengan memberi nilai subjektif tentang pentingnya setiap variabel secara relatif, dan
                menetapkan variabel mana yang memiliki prioritas paling tinggi guna mempengaruhi hasil pada situasi
                tersebut.</p>

                
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-10">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Perankingan Alternatif</h6>
                        </div>
                        <div class="card-body">
                            {{-- <div class="chart-bar"> --}}
                                <canvas id="alternatifChart"></canvas>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center d-none">
                <div class="col-xl-9 col-lg-9">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Grafik Prioritas Kriteria</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-bar">
                                <canvas id="kriteriaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@section('jspage')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
@include('charts')
@endsection