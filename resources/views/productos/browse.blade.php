@extends('voyager::master')

@section('page_title', 'Viendo Equipos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <h1 class="page-title">
                    <i class="voyager-phone"></i> Equipos
                </h1>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="select-group" style="margin-top: 40px">
                    <option value="">Todos</option>
                    <option value="type">Agrupago por tipos</option>
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
            let group = searchParams.get('group')
            $('#select-group').val(group);
            $('[data-toggle="tooltip"]').tooltip()
            if(!group){
                let columns = [
                    { data: 'id', title: 'id' },
                    { data: 'equipo', title: 'Equipo' },
                    { data: 'precios', title: 'Precios' },
                    // { data: 'precio_venta_contado', title: 'Precio venta contado' },
                    // { data: 'precio_venta', title: 'Precio venta crédito' },
                ];
                customDataTable("{{ url('admin/productos/ajax/list') }}", columns);
            }else if(group == 'type'){
                let columns = [
                    { data: 'id', title: 'id' },
                    { data: 'equipo', title: 'Equipo' },
                    { data: 'stock', title: 'Stock actual' },
                    { data: 'stock_credito', title: 'Stock a crédito' },
                    { data: 'inversion', title: 'Inversión Bs.' },
                    { data: 'deuda', title: 'Deuda Bs.' },
                    // { data: 'action', title: 'Acciones', orderable: false, searchable: false },
                ];
                customDataTable("{{ url('admin/productos/ajax/list/type') }}", columns);
            }

            $('#select-group').change(function(){
                let val = $(this).val();
                if(val){
                    window.location = "{{ url('admin/productos') }}?group=type";
                }else{
                    window.location = "{{ url('admin/productos') }}";
                }
            });
        });
    </script>
@stop
