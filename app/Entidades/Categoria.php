<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Categoria extends Model
{
    protected $table = 'categorias';
    public $timestamps = false;

    protected $fillable = [
        'idcategoria', 'nombre'
    ];

    // protected $hidden = [];

    public function cargarDesdeRequest(Request $request)
    {
        $this->idcategoria = $request->input('id') != "0" ? $request->input('id') : $this->idcategoria;

        $this->nombre = trimIfString($request->input('txtNombre'));
    }

    public function insertar() {
        $sql = "INSERT INTO categorias (
                  nombre
                ) VALUES (?)";

        DB::insert($sql, [$this->nombre]);
        $this->idcategoria = DB::getPdo()->lastInsertId();

        return $this->idcategoria;
    }

    public function actualizar() {
        $sql = "UPDATE categorias SET
                  nombre = ?
                WHERE idcategoria = ?";

        DB::update($sql, [$this->nombre, $this->idcategoria]);
    }

    public function eliminar() {
        $sql = "DELETE FROM categorias WHERE idcategoria = ?";
        DB::delete($sql, [$this->idcategoria]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $categoria = new Categoria();
        $categoria->idcategoria = $fila->idcategoria;
        $categoria->nombre = $fila->nombre;

        return $categoria;
    }

    public static function obtenerPorId($idcategoria)
    {
        $sql = "SELECT
                  idcategoria,
                  nombre
                FROM categorias WHERE idcategoria = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$idcategoria]));
    }

    public static function obtenerTodos()
    {
        $sql = "SELECT
                  idcategoria,
                  nombre
                FROM categorias ORDER BY nombre";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }

    public static function contarRegistros()
    {
        $sql = "SELECT COUNT(*) AS total FROM categorias";

        if ($fila = DB::selectOne($sql)) {
            return $fila->total;
        }

        return 0;
    }

    public static function obtenerPaginado(int $inicio = 0, int $cantidad = 25)
    {
        $sql = "SELECT
                  idcategoria,
                  nombre
                FROM categorias ORDER BY nombre LIMIT $inicio, $cantidad";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}