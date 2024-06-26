<?php

namespace App\Http\Controllers;

use App\Entidades\Carrito;
use App\Entidades\Cliente;
use App\Entidades\Pedido;
use App\Entidades\Sucursal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';
require app_path() . '/start/funciones_generales.php';

class ControladorWebCarrito extends Controller
{
    public function index()
    {
        $carrito = Carrito::select('idcarrito')->latest('idcarrito')->firstOrCreate(['fk_idcliente' => Cliente::autenticado()]);

        $aSucursales = Sucursal::get(['idsucursal', 'nombre']);
        return view('web.carrito', compact('carrito', 'aSucursales'));
    }

    public function editar(Request $request)
    {
        $carrito = Carrito::select('idcarrito')->latest('idcarrito')->firstOrCreate(['fk_idcliente' => Cliente::autenticado()]);

        $idproducto = $request->get('idproducto');

        if (isset($_POST["btnEliminar"])) {
            $carrito->productos()->detach($idproducto);
        } else {
            $cantidad = $request->input('txtCantidad', 1);

            $carrito->productos()->syncWithoutDetaching([$idproducto => ['cantidad' => $cantidad]], false);
        }

        Session::put('nCarrito', $carrito->nProductos);

        // TODO: No redireccionar, solo notificar. Popup Toast?
        return redirect('/carrito');
    }

    public function confirmar(Request $request)
    {
        $medioDePago = $request->input('lstMedioDePago');
        if (!$medioDePago) {
            Session::flash('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "Selecciona un medio de pago."
            ]);

            return redirect('/carrito');
        }

        $sucursal = Sucursal::select('idsucursal')->find($request->input('lstSucursal'));
        if (!$sucursal) {
            Session::flash('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "La sucursal seleccionada no está disponible."
            ]);

            return redirect('/carrito');
        }

        $cliente = Cliente::find(Cliente::autenticado());
        $carrito = $cliente->carrito;

        if (is_null($carrito) || $carrito->nProductos === 0) {
            return redirect('/carrito');
        }

        $pedido = new Pedido();
        $pedido->fk_idcliente = $carrito->fk_idcliente;
        $pedido->fk_idsucursal = $sucursal->idsucursal;

        $pedido->fk_idestado = 1; // Pendiente.
        if ($medioDePago == "sucursal") {
            $pedido->metodo_pago = 0;
        } else {
            $pedido->metodo_pago = 1;
        }

        $pedido->total = $carrito->total;
        $pedido->comentarios = $request->input('txtComentarios');

        try {
            $pedidoProductos = [];
            foreach ($carrito->productos as $producto) {
                $pedidoProductos[$producto->idproducto] = ['cantidad' => $producto->pivot->cantidad];
            }

            // TODO: Generar pedido con estado oculto, y cambiarlo una vez hayan sido agregados todos los productos.
            $pedido->save();
            $pedido->productos()->attach($pedidoProductos);
        } catch (Exception $e) {
            try {
                $pedido->delete();
            } catch (Exception $e) {}

            Session::flash('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "Algo salió mal generando tu pedido. Vuelve a intentar"
            ]);

            return redirect('/carrito');
        }

        $carrito->vaciar();
        Session::put('nCarrito', 0);

        if ($pedido->metodo_pago == 0) {
            return redirect("/micuenta");
        }

        return redirect("/mercadopago/pagar/$pedido->idpedido");
    }
}
