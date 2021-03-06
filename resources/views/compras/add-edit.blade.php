
@extends('voyager::master')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', ($type == 'edit' ? 'Editar' : 'Agregar').' Compras')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-receipt"></i>
        {{ ($type == 'edit' ? 'Editar' : 'Agregar').' Compras' }}
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <form role="form" action="{{ $type == 'edit' ? route('compras.update', ["compra" => $reg->id]) : route('compras.store') }}" method="POST" enctype="multipart/form-data">
                        @if($type == 'edit')
                            {{ method_field("PUT") }}
                        @endif

                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (session('error_detalle'))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ session('error_detalle') }}</li>
                                    </ul>
                                </div>
                            @endif

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-8" style="max-height: 400px; overflow-y: auto; padding: 0px">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Productos</label>
                                            <select id="select-productos_tipo_id" class="form-control select2">
                                                <option disabled selected value="">-- Seleccionar productos --</option>
                                                @foreach ($categorias as $categoria)
                                                    @if (count($categoria->tipos) > 0)
                                                        <optgroup label="{{ $categoria->nombre }}">
                                                            @foreach ($categoria->tipos as $item)
                                                                <option data-item='@json($item)'>{{ $item->marca->nombre }} {{ $item->nombre }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="table-responsive">
                                            <div class="col-md-12" style="margin-top: 20px" id="div-empty-list">
                                                <h3 class="text-muted text-center">Lista vac??a</h3>
                                            </div>
                                            <table class="table" style="display: none" id="table-list">
                                                <thead>
                                                    <th>Tipo</th>
                                                    <th>IMEI/N&deg; de serie</th>
                                                    <th style="width: 110px">Precio compra</th>
                                                    <th style="width: 110px">Venta contado </th>
                                                    <th style="width: 110px">Venta cr??dito</th>
                                                    <th @if (setting('ventas.precios_credito') != 2) style="display: none" @else style="width: 100px" @endif >Venta cr??dito</th>
                                                    <th style="width: 20px"></th>
                                                </thead>
                                                <tbody id="table-detalle"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding-left: 0px">
                                    <div class="form-group">
                                        <label>Proveedor</label>
                                        <select name="proveedor_id" id="select-proveedor_id" class="form-control select2" required>
                                            <option disabled selected value="">-- Seleccionar proveedor --</option>
                                            @foreach ($proveedores as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre_completo }} - {{ $item->nit ?? 'NN' }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('proveedor_id'))
                                            @foreach ($errors->get('proveedor_id') as $error)
                                                <span class="help-block text-danger">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <textarea name="observaciones" class="form-control" rows="3" placeholder="Observaciones">{{ isset($reg) ? $reg->observaciones : old('observaciones') }}</textarea>
                                    </div>
                                    {{-- <div class="form-group">
                                        <h3 class="text-right" id="label-total">0.00 Bs.</h3>
                                    </div> --}}
                                    {{-- <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="proforma" value="1" id="flexCheckChecked">
                                        <label class="form-check-label" for="flexCheckChecked">
                                          Generar solo proforma
                                        </label>
                                    </div> --}}
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">Guardar compra <i class="voyager-check"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        th{
            font-size: 11px !important
        }
        td{
            padding: 4px !important;
        }
    </style>
@endsection

@section('javascript')  
    <script>
        $(document).ready(function(){
            var indexTable = 0;
            $('#select-productos_tipo_id').change(function(){
                let producto = $('#select-productos_tipo_id option:selected').data('item');
                if(producto){
                    addTr(indexTable, producto);
                    indexTable += 1;
                }
            });
        });
        function addTr(indexTable, data){
            let cantidad_precios = "{{ setting('ventas.precios_credito') }}";

            $('#table-detalle').append(`
                <tr id="tr-${indexTable}" class="tr-item">
                    <td><input type="hidden" name="productos_tipo_id[]" class="form-control" placeholder="PC Sure 2021" value="${data.id}" required/>${data.marca.nombre} ${data.nombre}</td>
                    <td><input type="text" name="imei[]" class="form-control" placeholder="123456789..." required/></td>
                    <td><input type="number" step="1" min="1" name="precio_compra[]" class="form-control imput-sm" onchange="subTotal(${indexTable})" onkeyup="subTotal(${indexTable})" value="" id="input-precio_compra-${indexTable}" required/></td>
                    <td>
                        <input type="number" step="1" min="1" name="precio_venta_contado[]" class="form-control" onchange="subTotal(${indexTable})" onkeyup="subTotal(${indexTable})" id="input-precio_venta_contado-${indexTable}" required/>
                        <small style="font-size: 11px" id="ganancia_contado-${indexTable}"></small>
                    </td>
                    <td>
                        <input type="number" step="1" min="1" name="precio_venta[]" class="form-control" onchange="subTotal(${indexTable})" onkeyup="subTotal(${indexTable})" id="input-precio_venta-${indexTable}" required/>
                        <small style="font-size: 11px" id="ganancia_credito-${indexTable}"></small>
                    </td>
                    <td ${cantidad_precios != 2 ? 'style="display:none"' : ''}>
                        <input type="number" step="1" min="1" name="precio_venta_alt[]" class="form-control" onchange="subTotal(${indexTable})" onkeyup="subTotal(${indexTable})" id="input-precio_venta_alt-${indexTable}"/>
                        <small style="font-size: 11px" id="ganancia_credito_alt-${indexTable}"></small>
                    </td>
                    <td><button type="button" style="padding: 0px" onclick="removeTr(${indexTable})" class="btn btn-link"><i class="voyager-trash text-danger"></i></button></td>
                </tr>
            `);
            showHelp();
            $('#select-productos_tipo_id').val('').trigger('change');
        }
        function subTotal(index){
            let precio_compra = $(`#input-precio_compra-${index}`).val() ? parseFloat($(`#input-precio_compra-${index}`).val()) : 0;
            let precio_venta_contado = $(`#input-precio_venta_contado-${index}`).val() ? parseFloat($(`#input-precio_venta_contado-${index}`).val()) : 0;
            let precio_venta = $(`#input-precio_venta-${index}`).val() ? parseFloat($(`#input-precio_venta-${index}`).val()) : 0;
            let precio_venta_alt = $(`#input-precio_venta_alt-${index}`).val() ? parseFloat($(`#input-precio_venta_alt-${index}`).val()) : 0;
            
            let ganancia_contado = precio_venta_contado - precio_compra;
            let ganancia_credito = precio_venta - precio_compra;
            let ganancia_credito_alt = precio_venta_alt - precio_compra;
            
            $(`#ganancia_contado-${index}`).html(`${ganancia_contado <= 0 ? 'P??rdida' : 'Ganancia'} ${ganancia_contado} Bs.`);
            ganancia_contado <= 0 ? $(`#ganancia_contado-${index}`).addClass('text-danger') : $(`#ganancia_contado-${index}`).removeClass('text-danger');

            $(`#ganancia_credito-${index}`).html(`${ganancia_credito <= 0 ? 'P??rdida' : 'Ganancia'} ${ganancia_credito} Bs.`);
            ganancia_credito <= 0 ? $(`#ganancia_credito-${index}`).addClass('text-danger') : $(`#ganancia_credito-${index}`).removeClass('text-danger');

            $(`#ganancia_credito_alt-${index}`).html(`${ganancia_credito_alt <= 0 ? 'P??rdida' : 'Ganancia'} ${ganancia_credito_alt} Bs.`);
            ganancia_credito_alt <= 0 ? $(`#ganancia_credito_alt-${index}`).addClass('text-danger') : $(`#ganancia_credito_alt-${index}`).removeClass('text-danger');
        }
        function removeTr(index){
            $(`#tr-${index}`).remove();
            showHelp();
        }
        function showHelp(){
            let show = document.getElementsByClassName("tr-item").length > 0 ? false : true;
            if(show){
                $('#div-empty-list').fadeIn('fast');
                $('#table-list').fadeOut();
            }else{
                $('#div-empty-list').fadeOut('fast');
                $('#table-list').fadeIn();
            }
        }
    </script>
@stop