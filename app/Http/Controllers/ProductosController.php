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
        $productos = Producto::with(['tipo.marca', 'venta.cuotas.pagos'])->where('deleted_at', NULL)->get();
        return view('productos.browse', compact('productos'));
    }

    public function list()
    {
        $data = Producto::with(['tipo.marca', 'venta.cuotas.pagos'])->where('deleted_at', NULL)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('equipo', function($row){
                $img = asset('images/default.jpg');
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
                                <small>IMEI/N&deg; de serie: '.$row->imei.'</small><br>
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
                        <small>Precio de venta al crédito:</small> <b title="Ganancia Bs. '.($row->precio_venta_alt-$row->precio_compra).'" style="cursor: pointer">Bs. '.number_format($row->precio_venta_alt, 0, '', '').'</b><br>
                    </div>';
            })
            ->addColumn('estado', function($row){
                $clase = 'secondary';
                switch ($row->estado) {
                    case 'crédito':
                        $clase = 'info';
                        break;
                    case 'disponible':
                        $clase = 'success';
                        break;
                    case 'vendido':
                        $clase = 'primary';
                        break;
                }
                return '<label class="label label-'.$clase.'">'.$row->estado.'</label>';
            })
            ->addColumn('detalles', function($row){
                $detalles = '';
                if($row->estado == 'vendido'){
                    $detalles = '
                        <div>
                            <small>Precio de venta: </small><b>Bs. '.number_format($row->venta->precio, 0, '', '').'</b><br>
                            <small>Ganancia: </small><b>Bs. '.($row->venta->precio-$row->precio_compra).'</b>
                        </div>
                    ';
                }
                if($row->estado == 'crédito'){
                    $monto_pagado = 0;
                    foreach ($row->venta->cuotas as $cuota) {
                        foreach ($cuota->pagos as $pago) {
                            $monto_pagado += $pago->monto;
                        }
                    }
                    $detalles = '
                        <div>
                            <small>Precio de venta: </small><b>Bs. '.number_format($row->venta->precio, 0, '', '').'</b><br>
                            <small>Ganancia: </small><b>Bs. '.($row->venta->precio-$row->precio_compra).'</b><br>
                            <small>Nro de cuotas: </small><b>'.count($row->venta->cuotas->where('tipo', 'cuota')).'</b>
                            <small> - pagadas: </small><b>'.count($row->venta->cuotas->where('estado', 'pagada')->where('tipo', 'cuota')).'</b> <br>
                            <small>Monto pagado: </small><b>Bs. '.$monto_pagado.'</b> <br> <small>Deuda: </small><b>Bs. '.($row->venta->precio - $monto_pagado).'</b>
                        </div>
                    ';
                }
                return $detalles;
            })
            ->addColumn('action', function($row){
                $edit = '';
                $delete = ' ';
                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        <button '.($row->estado == 'vendido' ? 'disabled' : '').' data-toggle="modal" data-target="#modalequipo" onclick="edit('.$row->id.', '.$row->imei.', '.$row->precio_compra.', '.$row->precio_venta_contado.', '.$row->precio_venta.', '.$row->precio_venta_alt.', '.($row->estado == 'disponible' ? true : false).')" title="Editar" class="btn btn-sm btn-info edit">
                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                        </button>
                        <button '.($row->estado == 'vendido' || $row->estado == 'crédito' ? 'disabled' : '').' title="Borrar" class="btn btn-sm btn-danger delete" data-toggle="modal" data-target="#delete_modal" onclick="deleteItem('."'".url("admin/productos/".$row->id)."'".')">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                        </button>
                    </div>
                        ';
                return $actions;
            })
            ->rawColumns(['equipo', 'precios', 'estado', 'detalles', 'action'])
            ->make(true);
    }

    public function list_group_type()
    {
        $data = TiposProducto::with(['marca', 'productos.venta.cuotas.pagos'])->where('deleted_at', NULL)->limit(10)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('equipo', function($row){
                $img = asset('images/default.jpg');
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
                return $row->productos->where("estado", "disponible")->count();
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
            ->addColumn('pagos', function($row){
                $pagos = 0;
                foreach ($row->productos as $producto) {
                    if($producto->venta && $producto->estado != "vendido"){
                        foreach ($producto->venta->cuotas as $cuota) {
                            foreach ($cuota->pagos as $pago) {
                                $pagos += $pago->monto;
                            }
                        }
                    }
                }
                return $pagos;
            })
            ->addColumn('deuda', function($row){
                $total = 0;
                $pagos = 0;
                foreach ($row->productos as $producto) {
                    if($producto->venta){
                        $total += $producto->venta->precio;
                        foreach ($producto->venta->cuotas as $cuota) {
                            foreach ($cuota->pagos as $pago) {
                                $pagos += $pago->monto;
                            }
                        }
                    }
                }
                return $total-$pagos;
            })
            ->addColumn('ganancia', function($row){
                $inversion = 0;
                $costo = 0;
                foreach ($row->productos as $producto) {
                    if($producto->venta && $producto->estado != "vendido"){
                        $inversion += $producto->precio_compra;
                        $costo += $producto->venta->precio;
                    }
                }
                return $costo - $inversion;
            })
            ->rawColumns(['equipo', 'stock', 'stock_credito', 'inversion', 'pagos', 'deuda', 'ganancia'])
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
        DB::beginTransaction();
        try {
            $producto = Producto::find($id);
            $producto->precio_compra = $request->precio_compra;
            $producto->precio_venta = $request->precio_venta;
            $producto->precio_venta_alt = $request->precio_venta_alt;
            $producto->precio_venta_contado = $request->precio_venta_contado;
            $producto->save();

            $productos = Producto::where('tipos_producto_id', $producto->tipos_producto_id)->where('estado', 'disponible')->where('deleted_at', NULL)->get();
            // dd($productos);
            foreach ($productos as $value) {
                if($request->check_precio_venta_contado){
                    Producto::where('id', $value->id)->update(['precio_venta_contado' => $request->precio_venta_contado]);
                }
                if($request->check_precio_venta){
                    Producto::where('id', $value->id)->update(['precio_venta' => $request->precio_venta]);
                }
                if($request->check_precio_venta_alt){
                    Producto::where('id', $value->id)->update(['precio_venta_alt' => $request->precio_venta_alt]);
                }
            }

            DB::commit();
            return redirect()->route('productos.index')->with(['message' => 'Producto actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('ventas.index')->with(['message' => 'Ocurrio un error al actualizar la venta.', 'alert-type' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Producto::where('id', $id)->update([
                'estado' => 'eliminado',
                'deleted_at' => Carbon::now()
            ]);
            DB::commit();
            return redirect()->route('productos.index')->with(['message' => 'Producto eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('ventas.index')->with(['message' => 'Ocurrio un error al eliminar el producto.', 'alert-type' => 'error']);
        }
    }
}
