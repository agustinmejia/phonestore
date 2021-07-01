<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;

// Models
use App\Models\VentasDetallesCuota;
use App\Models\Venta;
use App\Models\VentasDetalle;
use App\Models\RegistrosCaja;
use App\Models\VentasDetallesCuotasPago;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index_deudas(){
        return view('reportes.deudas-browse');
    }

    public function index_deudas_list($rango){
        $fecha = date('Y-m-d');
        switch ($rango) {
            case 'semana':
                $fecha = date('Y-m-d', strtotime($fecha.' + 7 days'));
                break;
            case 'mes':
                $fecha = date('Y-m-d', strtotime($fecha.' + 1 month'));
                break;
        }
        $data = VentasDetallesCuota::with(['detalle.venta.cliente', 'detalle.venta.garantes.persona'])
                    ->where('deleted_at', NULL)->where('estado', 'pendiente')
                    ->where('fecha', '<=', date('Y-m-d', strtotime($fecha)))->get();
        // return $data;

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('cliente', function($row){
                return '
                    <div class="col-md-12">
                        <b>'.$row->detalle->venta->cliente->nombre_completo.'</b><br>
                        <small>Telf: '.('<a href="tel:'.$row->detalle->venta->cliente->telefono.'">'.$row->detalle->venta->cliente->telefono.'</a>' ?? 'No definido').'</small>
                    </div>';
            })
            ->addColumn('garante', function($row){
                $garantes = '';
                foreach ($row->detalle->venta->garantes as $item) {
                    $garantes .= '<li>'.$item->persona->nombre_completo.' <br> <small>Telf: '.('<a href="tel:'.$item->persona->telefono.'">'.$item->persona->telefono.'</a>' ?? 'No definido').'</small></li>';
                }
                return '
                    <div class="col-md-12">
                        <ul>'.$garantes.'</ul>
                    </div>';
            })
            ->addColumn('equipo', function($row){
                return $row->detalle->producto->tipo->marca->nombre.' <b>'.$row->detalle->producto->tipo->nombre.'</b>';
            })
            ->addColumn('fecha', function($row){
                return date('d/m/Y', strtotime($row->fecha)).'<br><small>'.Carbon::parse($row->fecha)->diffForHumans().'</small>';
            })
            ->addColumn('action', function($row){
                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        <a href="'.route('ventas.show', ['venta' => $row->detalle->venta_id]).'" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                    </div>
                        ';
                return $actions;
            })
            ->rawColumns(['cliente', 'garante', 'equipo', 'fecha', 'action'])
            ->make(true);
    }

    public function index_ventas(){
        return view('reportes.ventas-browse');
    }

    public function ventas_lista(Request $request){
        $ventas = Venta::with(['cliente', 'garantes', 'detalles.producto.tipo.marca', 'detalles.cuotas.pagos'])
                    ->where('deleted_at', NULL)->where('fecha', '>=', $request->inicio)
                    ->where('fecha', '<=', $request->fin)->get();
        return view('reportes.ventas-lista', compact('ventas'));
    }

    public function index_diario(){
        return view('reportes.diario-browse');
    }

    public function diario_lista(Request $request){
        $query_user = $request->user_id ? 'user_id = '.$request->user_id : 1;
        $registros_cajas = RegistrosCaja::whereDate('created_at', date('Y-m-d', strtotime($request->fecha)))->whereRaw($query_user)->withTrashed()->get();
        $pagos = VentasDetallesCuotasPago::with(['cuota.detalle.venta.cliente', 'cuota.detalle.producto.tipo.marca'])
                    ->whereDate('created_at', date('Y-m-d', strtotime($request->fecha)))->withTrashed()->get();
        $ventas = VentasDetalle::with(['producto.tipo.marca', 'venta.cliente'])->where('deleted_at', NULL)->get();
        // dd($ventas);
        return view('reportes.diario-lista', compact('registros_cajas', 'pagos', 'ventas'));
    }
}
