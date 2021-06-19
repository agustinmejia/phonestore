
@extends('voyager::master')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', ($type == 'edit' ? 'Editar' : 'Agregar').' Ventas')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-basket"></i>
        {{ ($type == 'edit' ? 'Editar' : 'Agregar').' Ventas' }}
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <form role="form" action="{{ $type == 'edit' ? route('ventas.update', ["ventas" => $reg->id]) : route('ventas.store') }}" method="POST" enctype="multipart/form-data">
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
                                <div class="col-md-8">
                                    <div class="col-md-12" style="min-height: 430px; max-height: 430px; overflow-y: auto; border: 1px solid #d8d8d8; padding: 0px">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            @foreach ($productos as $marca)
                                                <div class="panel panel-default" style="margin: 0px">
                                                    <div class="panel-heading" role="tab" id="headingOne">
                                                        <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $marca->id }}" aria-expanded="true" aria-controls="collapse-{{ $marca->id }}">
                                                            {{ $marca->nombre }}
                                                        </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse-{{ $marca->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                        <div class="panel-body">
                                                            @foreach ($marca->tipos as $tipo)
                                                                @if (count($tipo->productos) > 0)
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <ol class="breadcrumb">
                                                                            <li><a href="#">{{ $marca->nombre }}</a></li>
                                                                            <li class="active">{{ $tipo->nombre }}</li>
                                                                        </ol>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        @foreach ($tipo->productos as $item)
                                                                            @php
                                                                                $img = asset('images/phone-default.jpg');
                                                                                $imagenes = [];
                                                                                if ($item->tipo->imagenes) {
                                                                                    $imagenes = json_decode($item->tipo->imagenes);
                                                                                    $img = asset('storage/'.str_replace(".", "-cropped.", $imagenes[0]));
                                                                                }
                                                                            @endphp
                                                                            <div class="card col-md-2 card-phone" style="padding: 5px" data-item='@json($item)'>
                                                                                <div style="position: absolute; top: 3px; right: 3px">
                                                                                    <label class="label label-primary">{{ $item->precio_venta }}</label>
                                                                                </div>
                                                                                <img src="{{ $img }}" class="card-img-top" width="100%" alt="phone">
                                                                                <div class="card-body" style="padding: 5px">
                                                                                    <b style="white-space: nowrap">{{ $item->tipo->nombre }}</b> <br>
                                                                                    <small style="font-size: 10px white-space: nowrap">{{ $item->tipo->marca->nombre }}</small>
                                                                                </div>
                                                                                <div id="label-imei-{{ $item->id }}" style="position: absolute; bottom: 3px; left: 0px; right: 0px; background-color: rgba(0,0,0,0.8); display: none">
                                                                                    <p class="text-center" style="color: white; margin: 5px; font-size: 11px"><small style="font-size: 9px">IMEI</small> <br> {{ $item->imei }} </p>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cliente</label>
                                        <div class="input-group">
                                            <select name="cliente_id" id="select-cliente_id" class="form-control select2" required>
                                                <option disabled selected value="">-- Seleccionar cliente --</option>
                                                @foreach ($personas as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nombre_completo }} - {{ $item->nit ?? 'NN' }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-btn">
                                                <a href="{{ route('voyager.personas.create') }}" class="btn btn-primary" style="margin: 0px">Nuevo</a>
                                            </div>
                                        </div>
                                        @if ($errors->has('cliente_id'))
                                            @foreach ($errors->get('cliente_id') as $error)
                                                <span class="help-block text-danger">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Garante(s)</label>
                                        <div class="input-group">
                                            <select name="garante_id[]" id="select-garante_id" class="form-control select2" multiple required>
                                                @foreach ($personas as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nombre_completo }} - {{ $item->nit ?? 'NN' }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-btn">
                                                <a href="{{ route('voyager.personas.create') }}" class="btn btn-primary" style="margin: 0px">Nuevo</a>
                                            </div>
                                        </div>
                                        @if ($errors->has('garante_id'))
                                            @foreach ($errors->get('garante_id') as $error)
                                                <span class="help-block text-danger">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha de venta</label>
                                        <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        @if ($errors->has('fecha'))
                                            @foreach ($errors->get('fecha') as $error)
                                                <span class="help-block text-danger">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha de inicio de pago <span data-toggle="tooltip" data-placement="top" title="A partir de ésta fecha se generará el calendario de pagos"><span class="voyager-question"></span></span></label>
                                        <input type="date" name="fecha_inicio" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        @if ($errors->has('fecha_inicio'))
                                            @foreach ($errors->get('fecha') as $error)
                                                <span class="help-block text-danger">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <textarea name="observaciones" class="form-control" rows="3" placeholder="Observaciones">{{ isset($reg) ? $reg->observaciones : old('observaciones') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">Guardar venta <i class="voyager-check"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="col-md-12" style="margin-top: 20px" id="div-empty-list">
                                            <h3 class="text-muted text-center">Lista vacía</h3>
                                        </div>
                                        <table class="table" style="display: none" id="table-list">
                                            <thead>
                                                <th>Detalles del equipo</th>
                                                <th style="width: 120px">Precio</th>
                                                <th style="width: 150px">Cuota inicial Bs.</th>
                                                <th style="width: 120px">Cant. cuotas</th>
                                                <th>Periodo</th>
                                                <th style="width: 50px">Cuota Bs.</th>
                                                <th style="width: 50px"></th>
                                            </thead>
                                            <tbody id="table-detalle"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" style="text-align: right"><b>SUBTOTAL</b></td>
                                                    <td colspan="2"><input type="hidden" value="0" id="input-subtotal" /><h4 style="text-align: right" id="label-subtotal">0.00 <small>Bs.</small></h4></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right"><b>DESCUENTO</b></td>
                                                    <td colspan="2"><input type="number" name="descuento" step="0.1" min="0" value="0" class="form-control" id="input-descuento" onchange="total();total_pago()" onkeyup="total();total_pago()" onclick="$(this).select()" style="text-align: right; font-size: 18px; font-weight: 500; width: 120px" required></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right"><b><input type="checkbox" value="1" id="checkbox-iva" /> IVA</b></td>
                                                    <td colspan="2"><input type="hidden" value="0" id="input-iva" name="iva" /><h4 style="text-align: right" id="label-iva">0.00 <small>Bs.</small></h4></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right"><b>TOTAL</b></td>
                                                    <td colspan="2"><h4 style="text-align: right" id="label-total">0.00 <small>Bs.</small></h4></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right"><b>TOTAL A PAGAR</b></td>
                                                    <td colspan="2"><h4 style="text-align: right" id="label-pago">0.00 <small>Bs.</small></h4></td>
                                                </tr>
                                            </tfoot>
                                        </table>
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
        .card-phone:hover{
            border: 3px solid #1E5DB1;
            cursor: pointer;
            border-radius: 5px
        }
    </style>
@endsection

@section('javascript')
    <script>
        const IVA = parseFloat('{{ setting("ventas.iva") ?? 0.13 }}');
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            $('.card-phone').click(function(){
                let producto = $(this).data('item');
                if(!$(`#tr-${producto.id}`)[0]){
                    addTr(producto);
                }else{
                    toastr.warning('El equipo ya fue elegido', 'Advertencia');
                }
            });

            $('.card-phone').mouseenter(function() {
                let item = $(this).data('item');
                $(`#label-imei-${item.id}`).fadeIn();
            }).mouseleave(function(){
                let item = $(this).data('item');
                $(`#label-imei-${item.id}`).fadeOut();
            });
            $('#checkbox-iva').click(function(){
                if ($('#checkbox-iva').is(':checked')) {
                    let subtotal = parseFloat($('#input-subtotal').val());
                    $('#label-iva').html(`${(subtotal * IVA).toFixed(2)} <small>Bs.</small>`);
                    $('#input-iva').val(subtotal * IVA);
                }else{
                    $('#label-iva').html(`0.00 <small>Bs.</small>`);
                    $('#input-iva').val(0);
                }
                total();
            });
        });
        function addTr(data){
            img = "{{ asset('images/phone-default.jpg') }}";
            imagenes = [];
            if (data.tipo.imagenes) {
                imagenes = JSON.parse(data.tipo.imagenes);
                img = imagenes[0];
                img = "{{ url('storage') }}/"+img.replace('.', '-cropped.');
            }
            $('#table-detalle').append(`
                <tr id="tr-${data.id}" class="tr-item">
                    <td>
                        <table>
                            <tr>
                                <td><img src="${img}" alt="${data.tipo.nombre}" width="50px" /></td>
                                <td>
                                    <b>${data.tipo.nombre}</b><br>
                                    <small>${data.tipo.marca.nombre}</small><br>
                                    <b>${data.precio_venta_contado} - ${data.precio_venta} Bs.</b><br>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="producto_id[]" value="${data.id}" required/>
                        <input type="hidden" name="tipo_venta[]" id="input-tipo_venta-${data.id}" value="credito" required/>
                    </td>
                    <td>
                        <select name="precio[]" class="form-control select-precio" id="select-precio-${data.id}" onchange="subTotal(${data.id})" required>
                            <option value="${data.precio_venta}" data-type="credito">${data.precio_venta} - Crédito</option>
                            <option value="${data.precio_venta_contado}" data-type="contado">${data.precio_venta_contado} - Contado</option>
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" min="0" step="1" name="cuota_inicial[]" id="input-cuota_inicial-${data.id}" value="0" onchange="subTotal(${data.id})" onkeyup="subTotal(${data.id})" onclick="$(this).select()" class="form-control" />
                            <span class="input-group-addon">
                                <input type="checkbox" class="checkbox-cuota_inicial" onclick="total_pago()" checked name="cuota_inicial_pago[]" value="${data.id}" data-id="${data.id}" />
                            </span>
                        </div>
                        </td>
                    <td><input type="number" min="1" step="1" name="cuotas[]" id="input-cuotas-${data.id}" value="1" onchange="subTotal(${data.id})" onkeyup="subTotal(${data.id})" onclick="$(this).select()" class="form-control" required /></td>
                    <td>
                        <select name="periodo[]" class="form-control" required>
                            <option value="mensual">Mensual</option>
                            <option value="semanal">Semanal</option>
                            <option value="diario">Diario</option>
                        </select>
                    </td>
                    <td style="text-align: right"><input type="hidden" name="pago_cuota[]" id="input-pago_cuota-${data.id}" value="${data.precio_venta}" /><h4 id="label-pago_cuota-${data.id}">${data.precio_venta}</h4></td>
                    <td><button type="button" onclick="removeTr(${data.id})" class="btn btn-link"><i class="voyager-trash text-danger"></i></button></td>
                </tr>
            `);

            let subtotal = 0;
            $('.select-precio').each(function(){
                subtotal += parseFloat($(this).val());
            });
            $('#label-subtotal').html(`${subtotal.toFixed(2)} <small>Bs.</small>`);
            $('#input-subtotal').val(subtotal);

            showHelp();
            total();
            total_pago();
            toastr.info('Equipo agregado a las lista', 'Información');
        }
        function subTotal(index){

            let precio = $(`#select-precio-${index} option:selected`).val() ? parseFloat($(`#select-precio-${index} option:selected`).val()) : 0;
            let type = $(`#select-precio-${index} option:selected`).data('type');
            if(type == 'contado'){
                $(`#input-cuota_inicial-${index}`).val(precio);
                $(`#input-cuota_inicial-${index}`).attr('readonly', 'readonly');
            }else{
                // $(`#input-cuota_inicial-${index}`).val(0);
                $(`#input-cuota_inicial-${index}`).removeAttr('readonly');
            }
            $(`#input-tipo_venta-${index}`).val(type);

            let cuotaInicial = $(`#input-cuota_inicial-${index}`).val() ? parseFloat($(`#input-cuota_inicial-${index}`).val()) : 0;
            let cantidadCuotas = $(`#input-cuotas-${index}`).val() ? parseFloat($(`#input-cuotas-${index}`).val()) : 0;

            if(cantidadCuotas > 0){
                let cuota = (precio - cuotaInicial) / cantidadCuotas;
                $(`#label-pago_cuota-${index}`).text(`${cuota.toFixed(2)}`);
                $(`#input-pago_cuota-${index}`).val(cuota);
            }else{
                toastr.error('Debes ingresar el número de cuotas', 'Error');
            }
            total_pago();
        }
        function total(){
            let subtotal = parseFloat($('#input-subtotal').val());
            let descuento = $('#input-descuento').val() ? parseFloat($('#input-descuento').val()) : 0;
            let iva = parseFloat($('#input-iva').val());
            $('#label-total').html(`${(subtotal-descuento+iva).toFixed(2)} <small>Bs.</small>`);
        }
        function total_pago(){
            let total_pago = 0;
            let descuento = $('#input-descuento').val() ? parseFloat($('#input-descuento').val()) : 0;
            $('.checkbox-cuota_inicial').each(function(){
                if($(this).is(':checked')){
                    let id = $(this).data('id');
                    let monto = $(`#input-cuota_inicial-${id}`).val() ? $(`#input-cuota_inicial-${id}`).val() : '0';
                    total_pago += parseFloat(monto);
                }
            });
            $('#label-pago').html(`${(total_pago-descuento).toFixed(2)} <small>Bs.</small>`);
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
