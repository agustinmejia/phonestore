@extends('voyager::master')

@section('page_title', 'Lista de deudas')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <h1 class="page-title">
                    <i class="voyager-dollar"></i> Lista de deudas
                </h1>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="select-rango" style="margin-top: 40px">
                    <option value="hoy">Hoy</option>
                    <option value="semana">En una semana</option>
                    <option value="mes">En un mes</option>
                </select>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                            </table>
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
            let searchParams = new URLSearchParams(window.location.search)
            let rango = searchParams.get('rango');
            rango = rango ? rango : 'hoy';
            $('#select-rango').val(rango);
            $('[data-toggle="tooltip"]').tooltip()
            let columns = [
                { data: 'fecha', title: 'Fecha de pago' },
                { data: 'cliente', title: 'Cliente' },
                { data: 'garante', title: 'Garante(s)' },
                { data: 'equipo', title: 'Equipo' },
                { data: 'tipo', title: 'Tipo' },
                { data: 'deuda', title: 'Deuda Bs.' },
                { data: 'action', title: 'Acciones', orderable: false, searchable: false },
            ]
            customDataTable("{{ url('admin/reportes/deudas') }}/"+rango, columns);

            $('#select-rango').change(function(){
                let val = $(this).val();
                if(val){
                    window.location = "{{ url('admin/reportes/deudas') }}?rango="+val;
                }else{
                    window.location = "{{ url('admin/reportes/deudas') }}";
                }
            });
        });
    </script>
@stop
