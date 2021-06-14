<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;

// Models
use App\Models\Marca;
use App\Models\Producto;
use App\Models\TiposProducto;

class ProductosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('productos.browse');
    }

    public function list()
    {
        $data = Producto::with(['tipo.marca'])->where('deleted_at', NULL)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('equipo', function($row){
                $img = asset('images/phone-default.jpg');
                $imagenes = [];
                if ($row->tipo->imagenes) {
                    $imagenes = json_decode($row->tipo->imagenes);
                    $img = asset('storage/'.str_replace(".", "-cropped.", $imagenes[0]));
                }
                return '
                    <table>
                        <tr>
                            <td><img src="'.$img.'" class="card-img-top" width="60px" alt="phone"></td>
                            <td>
                                <b>'.$row->tipo->marca->nombre.' '.$row->tipo->nombre.'</b><br>
                                <small>IMEI: '.$row->imei.'</small><br>
                                <small>'.substr($row->tipo->detalles, 0, 50).'...</small>
                            </td>
                        </tr>
                    </table>';
            })
            ->addColumn('precios', function($row){
                return '
                    <div>
                        <small>Precio de compra:</small> <b>Bs. '.number_format($row->precio_compra                                                                                                                                                                                                                                                                             , 0, '', '').'</b><br>
                        <small>Precio de venta al contado:</small> <b title="Ganancia Bs. '.($row->precio_venta_contado-$row->precio_compra).'" style="cursor: pointer">Bs. '.number_format($row->precio_venta_contado, 0, '', '').'</b><br>
                        <small>Precio de venta al crédito:</small> <b title="Ganancia Bs. '.($row->precio_venta-$row->precio_compra).'" style="cursor: pointer">Bs. '.number_format($row->precio_venta, 0, '', '').'</b><br>
                    </div>';
            })
            ->rawColumns(['equipo', 'precios'])
            ->make(true);
    }

    public function list_group_type()
    {
        $data = TiposProducto::with(['marca', 'productos.venta.cuotas.pagos'])->where('deleted_at', NULL)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('equipo', function($row){
                $img = asset('images/phone-default.jpg');
                $imagenes = [];
                if ($row->imagenes) {
                    $imagenes = json_decode($row->imagenes);
                    $img = asset('storage/'.str_replace(".", "-cropped.", $imagenes[0]));
                }
                return '
                    <table>
                        <tr>
                            <td><img src="'.$img.'" class="card-img-top" width="60px" alt="phone"></td>
                            <td>
                                <b>'.$row->marca->nombre.' '.$row->nombre.'</b><br>
                                <small>'.substr($row->detalles, 0, 50).'...</small>
                            </td>
                        </tr>
                    </table>';
            })
            ->addColumn('stock', function($row){
                return count($row->productos->where("estado", "disponible"));
            })
            ->addColumn('stock_credito', function($row){
                return count($row->productos->where("estado", "crédito"));
            })
            ->addColumn('inversion', function($row){
                $inversion = 0;
                foreach ($row->productos as $item) {
                    if($item->estado != "vendido"){
                        $inversion += $item->precio_compra;
                    }
                }
                return $inversion;
            })
            ->addColumn('deuda', function($row){
                $total = 0;
                $pagos = 0;
                foreach ($row->productos as $producto) {
                    if($producto->venta){
                        $total = $producto->venta->precio;
                        foreach ($producto->venta->cuotas as $cuota) {
                            foreach ($cuota->pagos as $pago) {
                                $pagos += $pago->monto;
                            }
                        }
                    }
                }
                return $total-$pagos;
            })
            ->rawColumns(['equipo', 'stock', 'stock_credito', 'inversion', 'deuda'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
