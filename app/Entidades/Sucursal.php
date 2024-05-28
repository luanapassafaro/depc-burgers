<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Sucursal extends Model
{
    protected $table = 'sucursales';
    public $timestamps = false;

    protected $fillable = [
        'idsucursal', 'nombre', 'direccion', 'telefono', 'maps_url'
    ];

    public function cargarDesdeRequest(Request $request)
    {
        $this->idsucursal = $request->input('id') != "0" ? $request->input('id') : $this->idsucursal;

        $this->nombre = trimIfString($request->input('txtNombre'));
        $this->direccion = trimIfString($request->input('txtDireccion'));
        $this->telefono = trimIfString($request->input('txtTelefono'));
        $this->maps_url = trimIfString($request->input('txtMapsUrl'));
    }

    public function insertar() {
        $sql = "INSERT INTO sucursales (
                  nombre,
                  direccion,
                  telefono,
                  maps_url
                ) VALUES (?, ?, ?, ?)";

        DB::insert($sql, [$this->nombre, $this->direccion, $this->telefono, $this->maps_url]);
        $this->idsucursal = DB::getPdo()->lastInsertId();

        return $this->idsucursal;
    }

    public function actualizar() {
        $sql = "UPDATE sucursales SET
                  nombre = ?,
                  direccion = ?,
                  telefono = ?,
                  maps_url = ?
                WHERE idsucursal = ?";

        DB::update($sql, [
            $this->nombre,
            $this->direccion,
            $this->telefono,
            $this->maps_url,
            $this->idsucursal
        ]);
    }

    public function eliminar() {
        $sql = "DELETE FROM sucursales WHERE idsucursal = ?";
        DB::delete($sql, [$this->idsucursal]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $sucursal = new Sucursal();
        $sucursal->idsucursal = $fila->idsucursal;
        $sucursal->nombre = $fila->nombre;
        $sucursal->direccion = $fila->direccion;
        $sucursal->telefono = $fila->telefono;
        $sucursal->maps_url = $fila->maps_url;

        return $sucursal;
    }

    public static function obtenerPorId($idSucursal)
    {
        $sql = "SELECT
                  idsucursal,
                  nombre,
                  direccion,
                  telefono,
                  maps_url
                FROM sucursales WHERE idsucursal = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$idSucursal]));
    }

    public static function obtenerTodos()
    {
        $sql = "SELECT
                  idsucursal,
                  nombre,
                  direccion,
                  telefono,
                  maps_url
                FROM sucursales ORDER BY nombre";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }

    public static function contarRegistros()
    {
        $sql = "SELECT COUNT(*) AS total FROM sucursales";

        if ($fila = DB::selectOne($sql)) {
            return $fila->total;
        }

        return 0;
    }

    public static function obtenerPaginado(int $inicio = 0, int $cantidad = 25)
    {
        $sql = "SELECT
                  idsucursal,
                  nombre,
                  direccion,
                  telefono,
                  maps_url
                FROM sucursales ORDER BY nombre LIMIT $inicio, $cantidad";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}