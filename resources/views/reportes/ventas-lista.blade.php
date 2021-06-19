<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Cliente</th>
                <th>Equipo(s)</th>
                <th>Subtotal</th>
                <th>IVA</th>
                <th>Descuento</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $venta->cliente->nombre_completo }}</td>
                    <td>
                        @foreach ($venta->detalles as $detalle)
                            {{ $detalle->producto->tipo->marca->nombre }} <b>{{ $detalle->producto->tipo->nombre }}</b> <small>IMEI {{ $detalle->producto->imei }}</small> <br>
                        @endforeach
                    </td>
                    <td>{{ $venta->detalles->sum('precio') }}</td>
                    <td>{{ $venta->iva }}</td>
                    <td>{{ $venta->descuento }}</td>
                    <td>{{ $venta->detalles->sum('precio') - $venta->descuento + $venta->iva }}</td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="7"><h4 class="text-center">No se encontraron resultados</h4></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>