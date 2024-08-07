<?php

namespace App\Entidades;

use Database\Factories\CategoriaFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $primaryKey = 'idcategoria';

    public $timestamps = false;

    protected $fillable = [
        'idcategoria', 'nombre', 'posicion'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'fk_idcategoria');
    }

    protected static function booted() {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('posicion', 'desc')->orderBy('nombre', 'asc');
        });
    }

    public function scopeGrilla(Builder $query, int $orderColumnIdx = 0, string $orderDirection = "desc")
    {
        $columnas = ['nombre', 'posicion'];

        $orderColumn = $columnas[$orderColumnIdx] ?? 'posicion';
        $orderDirection = $orderDirection == 'asc' ? 'asc' : 'desc';

        return $query->withoutGlobalScope('order')
            ->orderBy($orderColumn, $orderDirection)
            ->select('idcategoria', 'nombre', 'posicion');
    }

    public function cargarDesdeRequest(Request $request)
    {
        $this->nombre = $request->input('txtNombre');
        $this->posicion = $request->input('txtPosicion', 0);
    }

    protected static function newFactory()
    {
        return CategoriaFactory::new();
    }
}