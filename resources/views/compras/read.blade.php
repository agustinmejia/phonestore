@extends('voyager::master')

@section('page_title', 'Ver Compra')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i> Viendo Compra
        <a href="{{ route('compras.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Proveedor</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->proveedor->nombre_completo }} - Telf: <a href="tel: {{ $reg->proveedor->telefono }}">{{ $reg->proveedor->telefono }}</a></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Registrado por</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->empleado->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de registro</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d-M-Y', strtotime($reg->created_at)) }} <small>{{ \Carbon\Carbon::parse($reg->created_at)->diffForHumans() }}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->observaciones ?? 'Ninguna' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Detalle de la compra</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Detalle</th>
                                                <th style="text-align: right">Estado</th>
                                                <th style="text-align: right">Precio de venta al contado</th>
                                                <th style="text-align: right">Precio de venta al crédito</th>
                                                <th style="text-align: right">Precio de compra</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = 0;
                                            @endphp
                                            @foreach ($reg->producto as $item)
                                                <tr>
                                                    <td>
                                                        @php
                                                            if ($item->estado != 'eliminado') {
                                                                $total += $item->precio_compra;
                                                            }
                                                            $img = asset('images/phone-default.jpg');
                                                            $imagenes = [];
                                                            if ($item->tipo->imagenes) {
                                                                $imagenes = json_decode($item->tipo->imagenes);
                                                                $img = url('storage/'.str_replace('.', '-cropped.', $imagenes[0]));
                                                            }
                                                        @endphp
                                                        <table>
                                                            <tr>
                                                                <td><img src="{{ $img }}" alt="#" width="50px" /></td>
                                                                <td>
                                                                    <b>{{ $item->tipo->nombre }}</b><br>
                                                                    <small>{{ $item->tipo->marca->nombre }}</small><br>
                                                                    <small>IMEI {{ $item->imei }}</small>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td>
                                                    @switch($item->estado)
                                                        @case('disponible')
                                                            <label class="label label-success">{{ $item->estado }}</label>
                                                            @break
                                                        @case('crédito')
                                                            <label class="label label-info">{{ $item->estado }}</label>
                                                            @break
                                                        @case('vendido')
                                                            <label class="label label-primary">{{ $item->estado }}</label>
                                                            @break
                                                        @case('eliminado')
                                                            <label class="label label-danger">{{ $item->estado }}</label>
                                                            @break
                                                    @endswitch
                                                    </td>
                                                    <td style="text-align: right; @if($item->estado == 'eliminado') text-decoration: line-through @endif">Bs. {{ $item->precio_venta_contado }} <br> <small>Ganancia: {{ number_format($item->precio_venta_contado - $item->precio_compra, 2, ',', '.') }}</small> </td>
                                                    <td style="text-align: right; @if($item->estado == 'eliminado') text-decoration: line-through @endif">Bs. {{ $item->precio_venta }} <br> <small>Ganancia: {{ number_format($item->precio_venta - $item->precio_compra, 2, ',', '.') }}</small></td>
                                                    <td style="text-align: right; @if($item->estado == 'eliminado') text-decoration: line-through @endif">Bs. {{ $item->precio_compra }}</td>
                                                    {{-- <td style="text-align: right">
                                                        <button class="btn btn-success btn-sm btn-detalle" data-toggle="modal" data-target="#detalle_modal" data-cuotas='@json($item->cuotas)'>
                                                            <i class="voyager-list"></i> <span class="hidden-xs hidden-sm">Detalles</span>
                                                        </button>
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-right"><b>TOTAL</b></td>
                                                <td class="text-right"><h4>Bs. {{ number_format($total , 2, ',', '.') }}</h4></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            moment.locale('es');
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
