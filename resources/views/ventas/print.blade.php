<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Imprimir Kardex</title>
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
</head>
<body>
    <table width="100%">
        <tr>
            <td style="width:100px; text-align: center">
                <img src="{{ $admin_favicon == '' ? asset('images/icon.png') : Voyager::image($admin_favicon) }}" alt="{{ setting('admin.title') }}" width="60px">
                <h4>{{ setting('admin.title') }}</h4>
            </td>
            <td><h1 style="text-align: right">Kardex N&deg; {{ str_pad($reg->id, 5, "0", STR_PAD_LEFT) }}</h1></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="150px"><b>Cliente</b></td>
            <td width="50px">:</td>
            <td>{{ $reg->cliente->nombre_completo }}</td>
            <td width="150px"><b>N&deg; de Celular</b></td>
            <td width="50px">:</td>
            <td>{{ $reg->cliente->telefono }}</td>
        </tr>
        <tr>
            <td width="150px"><b>Garante(s)</b></td>
            <td width="50px">:</td>
            <td>
                @foreach ($reg->garantes as $item)
                    {{ $item->persona->nombre_completo }} &nbsp;
                @endforeach
            </td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td width="150px"><b>Observaciones</b></td>
            <td width="50px">:</td>
            <td colspan="4">{{ $item->observaciones ?? 'Sin observaciones' }}</td>
        </tr>
    </table>
    <br>
    <table width="100%" cellspacing="0" cellpadding="3" border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Detalle</th>
                <th style="text-align: right">Precio</th>
                <th style="text-align: right">Descuento</th>
                {{-- <th style="text-align: right">Monto pagado</th>
                <th style="text-align: right">Deuda</th> --}}
                <th style="text-align: right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reg->detalles as $item)
                @php
                    $total = 0;
                    $pagos = 0;
                    $descuento = 0;
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

                                $descuento += $cuota->descuento;
                            }

                            $total += $item->precio - $descuento;
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
                    <td style="text-align: right">Bs. {{ $descuento }}</td>
                    {{-- <td style="text-align: right">Bs. {{ $pagos }}</td> --}}
                    {{-- @php
                        $deuda = $item->precio - $pagos- $descuento;
                    @endphp --}}
                    {{-- <td style="text-align: right">Bs. {{ $deuda > 0 ? $deuda : 0 }}</td> --}}
                    <td style="text-align: right">Bs. {{ number_format($item->precio - $descuento, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"><b>TOTAL</b></td>
                <td style="text-align: right"><b>Bs. {{ number_format($item->precio - $descuento, 2, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>
    <script>
        window.print()
    </script>
</body>
</html>
