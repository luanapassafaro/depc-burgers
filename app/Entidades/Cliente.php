<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Cliente extends Model
{
    protected $table = 'clientes';
    public $timestamps = false;

    protected $fillable = [
        'idcliente', 'nombre', 'apellido', 'dni', 'email', 'clave', 'telefono'
    ];

    // protected $hidden = [];

    public function cargarDesdeRequest(Request $request)
    {
        $this->idcliente = $request->input('id') != "0" ? $request->input('id') : $this->idcliente;

        // No nulleables
        if ($request->filled('txtNombre'))
            $this->nombre = trimIfString($request->input('txtNombre'));

        if ($request->filled('txtApellido'))
            $this->apellido = trimIfString($request->input('txtApellido'));

        if ($request->filled('txtDNI'))
            $this->dni = trimIfString($request->input('txtDNI'));

        if ($request->filled('txtEmail'))
            $this->email = trimIfString($request->input('txtEmail'));

        if (is_null($this->idcliente)) {
            // Creando cliente, nueva clave
            $this->clave = trimIfString($request->input('txtClave'));
        } elseif ($request->filled('txtClave') && $request->filled('txtClaveAntigua')) {
            // Editando cliente, cambiando clave
            $claveNueva = trimIfString($request->input('txtClave'));
            $claveAntigua = trimIfString($request->input('txtClaveAntigua'));

            if (is_string($claveNueva) && is_string($claveAntigua) && !is_null($claveDB = $this->cargarClave()) && password_verify($claveAntigua, $claveDB)) {
                $this->clave = password_hash($claveNueva, PASSWORD_DEFAULT);
            }
        }

        // Nulleables
        if ($request->has('txtTelefono'))
            $this->telefono = trimIfString($request->input('txtTelefono'));
    }

    public function cargarClave()
    {
        if (!isset($this->idcliente))
            return null;

        $sql = "SELECT clave FROM clientes WHERE idcliente = ?";

        if ($fila = DB::selectOne($sql, [$this->idcliente])) {
            $this->clave = $fila->clave;
            return $this->clave;
        }

        return null;
    }

    public function insertar() {
        $sql = "INSERT INTO clientes (
                  nombre,
                  apellido,
                  dni,
                  email,
                  clave,
                  telefono
                ) VALUES (?, ?, ?, ?, ?, ?)";

        DB::insert($sql, [$this->nombre, $this->apellido, $this->dni, $this->email, $this->clave, $this->telefono]);
        $this->idcliente = DB::getPdo()->lastInsertId();

        return $this->idcliente;
    }

    public function actualizar() {
        $aCampos = [];
        $aValores = [];

        if (isset($this->nombre)) {
            $aCampos[] = "nombre = ?";
            $aValores[] = $this->nombre;
        }

        if (isset($this->apellido)) {
            $aCampos[] = "apellido = ?";
            $aValores[] = $this->apellido;
        }

        if (isset($this->dni)) {
            $aCampos[] = "dni = ?";
            $aValores[] = $this->dni;
        }

        if (isset($this->email)) {
            $aCampos[] = "email = ?";
            $aValores[] = $this->email;
        }

        if (isset($this->clave)) {
            $aCampos[] = "clave = ?";
            $aValores[] = $this->clave;
        }

        if (isset($this->telefono)) {
            $aCampos[] = "telefono = ?";
            $aValores[] = $this->telefono;
        }

        if (empty($aCampos)) {
            return;
        }

        $aValores[] = $this->idcliente;

        $sql = "UPDATE clientes SET " . implode(", ", $aCampos) . " WHERE idcliente = ?";

        DB::update($sql, $aValores);
    }

    public function eliminar() {
        $sql = "DELETE FROM clientes WHERE idcliente = ?";
        DB::delete($sql, [$this->idcliente]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $cliente = new Cliente();
        $cliente->idcliente = $fila->idcliente;
        $cliente->nombre = $fila->nombre;
        $cliente->apellido = $fila->apellido;
        $cliente->dni = $fila->dni;
        $cliente->email = $fila->email;
        $cliente->clave = $fila->clave;
        $cliente->telefono = $fila->telefono;

        return $cliente;
    }

    public static function obtenerPorId($idCliente)
    {
        $sql = "SELECT
                  idcliente,
                  nombre,
                  apellido,
                  dni,
                  email,
                  clave,
                  telefono
                FROM clientes WHERE idcliente = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$idCliente]));
    }

    public static function obtenerTodos()
    {
        $sql = "SELECT
                  idcliente,
                  nombre,
                  apellido,
                  dni,
                  email,
                  clave,
                  telefono
                FROM clientes ORDER BY nombre";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }

    public static function contarRegistros()
    {
        $sql = "SELECT COUNT(*) AS total FROM clientes";

        if ($fila = DB::selectOne($sql)) {
            return $fila->total;
        }

        return 0;
    }

    public static function obtenerPaginado(int $inicio = 0, int $cantidad = 25)
    {
        $sql = "SELECT
                  idcliente,
                  nombre,
                  apellido,
                  dni,
                  email,
                  clave,
                  telefono
                FROM clientes ORDER BY nombre LIMIT $inicio, $cantidad";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}