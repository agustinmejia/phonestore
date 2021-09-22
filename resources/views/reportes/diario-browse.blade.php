@extends('voyager::master')

@section('page_title', 'Reporte Diario')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <h1 class="page-title">
                    <i class="voyager-list"></i> Reporte Diario
                </h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="form" class="form-inline" action="{{ route('diario.lista') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        De <input type="time" name="inicio" value="08:00" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        A <input type="time" name="fin" value="18:00" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <select name="user_id" class="form-control">
                                            <option value="">--Todos los usuarios--</option>
                                            @foreach (\App\Models\User::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Generar</button>
                                </form>
                                <small>Ingrese el usuario y la fecha de la que quiere generar el reporte.</small>
                            </div>
                            <div class="col-md-12" style="height: 150px; display: none" id="div-loading">
                                <h3 class="text-center" style="margin-top: 50px"> <img src="{{ asset('images/loading.gif') }}" alt="loading" width="50px"> Generando reporte...</h3>
                            </div>
                            <div class="col-md-12" id="div-reporte">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#form').submit(function(e){
                $('#div-reporte').empty();
                $('#div-loading').css('display', 'block');
                e.preventDefault();
                $.post($('#form').attr('action'), $('#form').serialize(), function(res){
                    $('#div-loading').css('display', 'none');
                    $('#div-reporte').html(res);
                });
            });
        });
    </script>
@stop
