<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';

class ControladorCliente extends Controller
{
    public function index()
    {
        $titulo = "Lista de Clientes";

        return view("sistema.cliente-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nuevo Cliente";

        $cliente = new Cliente();
        return view("sistema.cliente-nuevo", compact("titulo", "cliente"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Cliente";

        if ($cliente = Cliente::find($request->id)) {
            return view("sistema.cliente-nuevo", compact("titulo", "cliente"));
        }

        $titulo = "Lista de Clientes";
        Session::flash("msg", [
            "ESTADO" => MSG_ERROR,
            "MSG" => "La cliente especificado no existe."
        ]);

        return redirect("/admin/clientes");
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Cliente";

        $cliente = Cliente::findOrNew($request->input('id'));
        $cliente->cargarDesdeRequest($request);

        if (empty($cliente->nombre) || empty($cliente->apellido) || empty($cliente->dni) || empty($cliente->email) || empty($cliente->clave)) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Ingrese todos los datos requeridos."
            ]);

            if ($cliente->exists) {
                $cliente->refresh();
            }

            return view("sistema.cliente-nuevo", compact("titulo", "cliente"));
        }

        try {
            $cliente->save();

            $_POST["id"] = $cliente->idcliente;
            Session::flash("msg", [
                "ESTADO" => MSG_SUCCESS,
                "MSG" => OKINSERT
            ]);

            return redirect('/admin/clientes');
        } catch (Exception $e) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => ERRORINSERT
            ]);
        }

        if ($cliente->exists) {
            $cliente->refresh();
        }

        return view("sistema.cliente-nuevo", compact("titulo", "cliente"));
    }

    public function eliminar(Request $request)
    {
        try {
            Cliente::destroy($request->id);

            $aResultado["err"] = EXIT_SUCCESS;
            $aResultado["msg"] = "Cliente eliminado exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo eliminar el cliente.";
        }

        return json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        $orderColumn = $request->order[0]['column'];
        $orderDirection = $request->order[0]['dir'];
        $offset = $request->start ?? 0;
        $limit = $request->length ?? 25;

        $count = Cliente::count();
        $aSlice = Cliente::grilla($orderColumn, $orderDirection)->offset($offset)->limit($limit)->get();

        $data = [];
        foreach ($aSlice as $cliente) {
            $row = [];
            $row[] = '<a href="/admin/cliente/' . $cliente->idcliente . '">' . $cliente->nombre . '</a>';
            $row[] = $cliente->apellido;
            $row[] = $cliente->dni;
            $row[] = $cliente->email;
            $row[] = $cliente->telefono;
            $data[] = $row;
        }

        $json_data = [
            "draw" => intval($request['draw']),
            "recordsTotal" => $count, //cantidad total de registros sin paginar
            "recordsFiltered" => count($aSlice), //cantidad total de registros en la paginacion
            "data" => $data
        ];

        return json_encode($json_data);
    }
}
