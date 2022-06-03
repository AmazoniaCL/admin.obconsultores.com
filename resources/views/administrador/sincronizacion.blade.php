@extends('layouts.app')

@section('title_content') Dashboard @endsection

@section('myScripts') <script src="{{ asset('assets/js/sincronizar.js') }}"></script> @endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <h4>Bienvenido {{ \Auth::user()->name }}</h4>
    </div>
</div>

<div class="section-body mt-5">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ribbon">
                        <button type="button" class="btn btn-primary" onclick="sincronizar()">SINCRONIZAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


