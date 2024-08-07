<?php

namespace App\Http\Controllers;

use App\Entidades\Categoria;
use App\Entidades\Sistema\Patente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControladorCategoria extends Controller
{
    public function index()
    {
        $titulo = "Lista de Categorías de Productos";

        return view("sistema.categoria-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nueva Categoría de Producto";

        $categoria = new Categoria();
        return view("sistema.categoria-nuevo", compact("titulo", "categoria"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Categoría de Productos";

        if ($categoria = Categoria::find($request->id)) {
            $permisoEditar = Patente::autorizarOperacion("CATEGORIAMODIFICACION");
            $permisoBaja = Patente::autorizarOperacion("CATEGORIABAJA");
            return view("sistema.categoria-nuevo", compact("titulo", "categoria", "permisoEditar", "permisoBaja"));
        }

        Session::flash("msg", [
            "ESTADO" => MSG_ERROR,
            "MSG" => "La categoría de producto especificada no existe."
        ]);

        return redirect("/admin/categorias");
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Categoría de Productos";

        $categoria = Categoria::findOrNew($request->input('id'));

        $categoria->cargarDesdeRequest($request);

        if (empty($categoria->nombre)) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Ingrese todos los datos requeridos."
            ]);

            if ($categoria->exists) {
                $categoria->refresh();
            }

            return view("sistema.categoria-nuevo", compact("titulo", "categoria"));
        }

        try {
            $categoria->save();

            $_POST["id"] = $categoria->idcategoria;
            Session::flash("msg", [
                "ESTADO" => MSG_SUCCESS,
                "MSG" => OKINSERT
            ]);

            return redirect("/admin/categorias");
        } catch (Exception $e) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => ERRORINSERT
            ]);
        }

        if ($categoria->exists) {
            $categoria->refresh();
        }

        return view("sistema.categoria-nuevo", compact("titulo", "categoria"));
    }

    public function eliminar(Request $request)
    {
        try {
            Categoria::destroy($request->id);

            $aResultado["err"] = EXIT_SUCCESS;
            $aResultado["msg"] = "Categoría de productos eliminada exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo eliminar la categoría de productos.";
        }

        return json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        $orderColumn = $request->order[0]['column'];
        $orderDirection = $request->order[0]['dir'];
        $offset = $request->start ?? 0;
        $limit = $request->length ?? 25;

        $count = Categoria::count();
        $aSlice = Categoria::grilla($orderColumn, $orderDirection)->offset($offset)->limit($limit)->get();

        $data = [];
        foreach ($aSlice as $categoria) {
            $row = [
                '<a href="/admin/categoria/' . $categoria->idcategoria . '">' . $categoria->nombre . '</a>',
                $categoria->posicion
            ];
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
