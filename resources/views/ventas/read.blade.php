@extends('voyager::master')

@section('page_title', 'Ver Venta')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-basket"></i> Viendo Venta
        <a href="{{ route('ventas.index') }}" class="btn btn-warning">
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
                                <h3 class="panel-title">Cliente</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->cliente->nombre_completo }} - Telf: <a href="tel: {{ $reg->cliente->telefono }}">{{ $reg->cliente->telefono }}</a></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Garante(s)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @foreach ($reg->garantes as $item)
                                    {{ $item->persona->nombre_completo }} - Telf: <a href="tel: {{ $item->persona->telefono }}">{{ $item->persona->telefono }}</a> &nbsp;
                                    @endforeach
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Atendido por</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $reg->empleado->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de venta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d-M-Y', strtotime($reg->fecha)) }} <small>{{ \Carbon\Carbon::parse($reg->fecha)->diffForHumans() }}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
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
                                <h3 class="panel-title">Detalle de la venta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Detalle</th>
                                                <th style="text-align: right">Precio</th>
                                                <th style="text-align: right">Monto pagado</th>
                                                <th style="text-align: right">Deuda</th>
                                                <th style="text-align: right">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reg->detalles as $item)
                                                @php
                                                    $pagos = 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ $item->id }}</td>
                                                    <td>
                                                        @php
                                                            $img = asset('images/default.jpg');
                                                            $imagenes = [];
                                                            if ($item->producto->tipo->imagenes) {
                                                                $imagenes = json_decode($item->producto->tipo->imagenes);
                                                                $img = url('storage/'.str_replace('.', '-cropped.', $imagenes[0]));
                                                            }

                                                            foreach ($item->cuotas as $cuota) {
                                                                foreach ($cuota->pagos as $pago) {
                                                                    if(!$pago->deleted_at){
                                                                        $pagos += $pago->monto;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        <table>
                                                            <tr>
                                                                <td><img src="{{ $img }}" alt="#" width="50px" /></td>
                                                                <td>
                                                                    <b>{{ $item->producto->tipo->nombre }}</b><br>
                                                                    <small>{{ $item->producto->tipo->marca->nombre }}</small><br>
                                                                    <small>IMEI/N&deg; de serie {{ $item->producto->imei }}</small>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td style="text-align: right">Bs. {{ $item->precio }}</td>
                                                    <td style="text-align: right">Bs. {{ $pagos }}</td>
                                                    <td style="text-align: right">Bs. {{ $item->precio - $pagos }}</td>
                                                    <td style="text-align: right">
                                                        <button class="btn btn-success btn-sm btn-detalle" data-toggle="modal" data-target="#detalle_modal" data-cuotas='@json($item->cuotas)'>
                                                            <i class="voyager-list"></i> <span class="hidden-xs hidden-sm">Detalles</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
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

    @include('partials.modal-delete')

    {{-- Detalle de cuotas --}}
    <form action="{{ route('ventas.pago.store') }}" method="post">
        <div class="modal modal-success fade" tabindex="-1" id="detalle_modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-list"></i> Detalles de cuota</h4>
                    </div>
                    <div class="modal-body" style="padding-bottom: 0px">
                        <div role="tabpanel">
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active">
                                    <a href="#home" id="tab-home" aria-controls="home" role="tab" data-toggle="tab">Lista de cuotas</a>
                                </li>
                                <li role="presentation">
                                    <a href="#profile" id="tab-profile" aria-controls="profile" role="tab" data-toggle="tab">Pago parcial</a>
                                </li>
                            </ul>
                        
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Tipo</th>
                                                    <th>Monto</th>
                                                    <th>Deuda</th>
                                                    <th>Estado</th>
                                                    <th>Pagos</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-detalle"></tbody>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label data-toggle="tooltip" title="En caso de que el pago no sea en efectivo seleccione ésta opción"><input type="checkbox" name="deposito" value="1">Transferencia bancaria</label>
                                          </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Descuento</label>
                                        <input type="number" name="descuento" step="0.5" min="0" value="0" id="input-descuento" onchange="total()" onkeyup="total()" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Observaciones..."></textarea>
                                    </div>
                                    <div class="text-right">
                                        <h2 id="label-total" style="margin: 0px">0 <small>Bs.</small></h2>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="profile">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $reg->id }}">
                                            <input type="hidden" name="ventas_detalle_id" id="input-ventas_detalle_id">
                                            <div class="form-group">
                                                <label>Monto a pagar</label>
                                                <input type="number" name="pago" id="input-pago" step="1" min="0" class="form-control" required />
                                                <small>En caso de que el monto sobrepase la deuda de la cuota, el saldo pasará a registrarse como pago de la siguiente cuota.</small>
                                            </div>
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <label data-toggle="tooltip" title="En caso de que el pago no sea en efectivo seleccione ésta opción"><input type="checkbox" name="deposito_alt" value="1">Transferencia bancaria</label>
                                                  </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Observaciones</label>
                                                <textarea name="observaciones_alt" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="btn-save">Realizar pago <span class="voyager-dollar"></span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        .btn-sm{
            padding: 2px 8px !important;
            font-weight: 500 !important
        }
    </style>
@endsection

@section('javascript')
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/moment-with-locales.js') }}"></script>
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function () {
            moment.locale('es');
            $('[data-toggle="tooltip"]').tooltip();
            $('.btn-detalle').click(function(){
                let data = $(this).data('cuotas');
                let detalle = '';
                data.map(item => {
                    let totalPago = 0;
                    let fecha = new Date(item.fecha+' 00:00:00');
                    let pagos = '';
                    item.pagos.map(pago => {
                        if(!pago.deleted_at){
                            totalPago += parseFloat(pago.monto)
                        }
                        pagos += `  <tr>
                                        <td><span style="${pago.deleted_at ? 'text-decoration: line-through; color: #f96868' : ''}">${moment(pago.created_at).format('DD/MMMM/YYYY')} ${parseFloat(pago.monto).toFixed(0)} Bs.</span> <br> <small class="${pago.deleted_at ? 'text-danger' : ''}">${pago.observaciones ?  pago.observaciones : ''}</small></td>
                                        <td><button type="button" class="btn btn-danger btn-sm" ${pago.deleted_at ? 'disabled' : ''} data-toggle="modal" data-target="#delete_modal" onclick="deleteItem('${"{{ url('admin/ventas/pago/delete') }}/"+pago.id}');$('#detalle_modal').modal('hide');"><span class="voyager-trash"></span></button></td>
                                    </tr>`;
                    });

                    detalle += `
                        <tr>
                            <td><input type="checkbox" ${item.estado == 'pagada' ? 'disabled' : ''} name="cuotas[]" class="checkbox-cuotas" onclick="total()" value="${item.id}" data-monto="${item.monto - totalPago}" /></td>
                            <td><b>${item.tipo}</b> <br> ${moment(fecha).format('D [de] MMMM, YYYY')}</td>
                            <td>${parseFloat(item.monto)}</td>
                            <td>${item.monto - totalPago}</td>
                            <td><span class="text-${item.estado == 'pagada' ? 'success' : 'danger'}">${item.estado}</span></td>
                            <td style="padding: 0px"><table class="table" style="margin: 0px">${pagos}</table></td>
                        </tr>
                    `;
                });
                $('#table-detalle').html(detalle);
                $('#label-total').html(`0 <small>Bs.</small>`);
                $('#input-descuento').val(0);
                $('#btn-save').fadeOut();

                // Pago parcial
                let cuota = null;
                data.map(item => {
                    if(item.estado == 'pendiente' && !cuota){
                        cuota = item;
                    }
                });

                if(cuota){
                    let monto = cuota.monto;
                    let montoPagado = 0;
                    cuota.pagos.map(pago => {
                        if(!pago.deleted_at){
                            montoPagado += parseFloat(pago.monto);
                        }
                    });
                    $('#input-ventas_detalle_id').val(cuota.ventas_detalle_id);
                    $('#input-pago').val(monto-montoPagado);
                    // $('#input-pago').attr('max', monto-montoPagado);
                }
            });

            $('#tab-home').click(function(){
                $('#btn-save').fadeOut();
                // $('.checkbox-cuotas').prop('checked', false);
            });

            $('#tab-profile').click(function(){
                $('#btn-save').fadeIn();
                $('#input-descuento').val(0);
                $('.checkbox-cuotas').prop('checked', false);
                $('#label-total').html(`0 <small>Bs.</small>`);
            });

            $('.btn-delete').click(function(){
                $('#detalle_modal').modal('hide');
            });
        });

        function total(){
            let total = 0;
            let descuento = $('#input-descuento').val() ? parseFloat($('#input-descuento').val()) : 0;
            let primer_pago = null;
            $('.checkbox-cuotas').each(function(){
                if($(this).is(':checked')){
                    total += parseFloat($(this).data('monto'));
                    if(!primer_pago){
                        primer_pago = parseFloat($(this).data('monto'));
                    }
                }
            });
            $('#label-total').html(`${total-descuento} <small>Bs.</small>`);

            if(total > 0){
                $('#btn-save').fadeIn();
            }else{
                $('#btn-save').fadeOut();
            }

            if(descuento > primer_pago){
                toastr.error('El decuento debe ser menor al monto de la primera cuota', 'Error');
                $('#btn-save').fadeOut();
            }else{
                $('#btn-save').fadeIn();
            }
        }
    </script>
@stop
