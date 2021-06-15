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
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Resumen</h3>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Stock de equipos</th>
                                            <th>Equipos a crédito</th>
                                            <th>Inversión total <span data-toggle="tooltip" data-placement="top" title="Incluye tanto los equipos en stock como los equipos que están a crédito"><span class="voyager-question"></span></span></th>
                                            <th>Pagos realizados</th>
                                            <th>Deuda total</th>
                                            <th>Ganancia esperada <span data-toggle="tooltip" data-placement="top" title="No toma en cuenta los productos existentes en stock"><span class="voyager-question"></span></span> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $inversion = 0;
                                            $inversion_credito = 0;
                                            $pagos = 0;
                                            $total = 0;
                                            $costo = 0;
                                            foreach ($productos as $producto) {
                                                if($producto->estado != "vendido"){
                                                    $inversion += $producto->precio_compra;
                                                }
                                                if($producto->venta && $producto->estado != "vendido"){
                                                    $total += $producto->venta->precio;
                                                    foreach ($producto->venta->cuotas as $cuota) {
                                                        foreach ($cuota->pagos as $pago) {
                                                            $pagos += $pago->monto;
                                                        }
                                                    }
                                                    $inversion_credito += $producto->precio_compra;
                                                    $costo += $producto->venta->precio;
                                                }
                                            }
                                        @endphp     
                                        <tr>
                                            <td><h4>{{ $productos->where("estado", "disponible")->count() }} Unids.</h4></td>
                                            <td><h4>{{ $productos->where("estado", "crédito")->count() }} Unids.</h4></td>
                                            <td><h4>{{ number_format($inversion, 0, ',', '.') }} Bs.</h4></td>
                                            <td><h4>{{ number_format($pagos, 0, ',', '.') }} Bs.</h4></td>
                                            <td><h4>{{ number_format($total - $pagos, 0, ',', '.') }} Bs.</h4></td>
                                            <td><h4>{{ number_format($costo - $inversion_credito, 0, ',', '.') }} Bs.</h4></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <small>En el cuadro superior no se toman en cuenta los equipos vendidos al contado ni los que ya fueron cancelados en su totalidad.</small>
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
            let searchParams = new URLSearchParams(window.location.search)
            let group = searchParams.get('group')
            $('#select-group').val(group);
            $('[data-toggle="tooltip"]').tooltip()
            if(!group){
                let columns = [
                    { data: 'id', title: 'id' },
                    { data: 'equipo', title: 'Equipo' },
                    { data: 'precios', title: 'Precios' },
                    { data: 'estado', title: 'Estado' },
                    { data: 'detalles', title: 'Detalles' },
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
                    { data: 'pagos', title: 'Pagos Bs.' },
                    { data: 'deuda', title: 'Deuda Bs.' },
                    { data: 'ganancia', title: 'Ganancia Bs.' },
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
