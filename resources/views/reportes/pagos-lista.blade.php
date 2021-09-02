<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Producto</th>
                <th>Descripción</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th style="text-align: right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total = 0;
            @endphp
            @forelse ($pagos as $item)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>
                        {{ $item->cuota->detalle->producto->tipo->marca->nombre }} {{ $item->cuota->detalle->producto->tipo->nombre }} <br>
                        <small>{{ $item->cuota->detalle->venta->cliente->nombre_completo }}</small>
                    </td>
                    <td>{{ $item->observaciones }}</td>
                    <td>{{ $item->efectivo ? 'Efectivo' : 'Transferencia' }}</td>
                    <td>{{ date('d/M/Y', strtotime($item->created_at)) }} <br> <small>{{ date('H:i:s', strtotime($item->created_at)) }}</small> </td>
                    <td style="text-align: right">{{ $item->monto }}</td>
                </tr>
                @php
                    $cont++;
                    $total += $item->monto;
                @endphp
            @empty
                <tr>
                    <td colspan="6"><h4 class="text-center">No se encontraron resultados</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5">TOTAL</td>
                <td class="text-right"><h4><small>Bs.</small> {{ number_format($total, 2, ',', '.') }}</h4></td>
            </tr>
        </tbody>
    </table>
</div>