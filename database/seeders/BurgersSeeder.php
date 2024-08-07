<?php

namespace Database\Seeders;

use App\Entidades\Categoria;
use App\Entidades\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BurgersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clientes')->insert([
            'nombre' => 'Test',
            'apellido' => 'Test',
            'dni' => '12345678',
            'email' => 'test@example.com',
            'clave' => password_hash('1234', PASSWORD_DEFAULT),
            'telefono' => '11 12345678',
        ]);

        DB::table('estados')->insert([
            ['nombre' => 'Pendiente', 'color' => 'warning'],
            ['nombre' => 'En preparación', 'color' => 'info'],
            ['nombre' => 'Entregado', 'color' => 'success'],
            ['nombre' => 'Cancelado', 'color' => 'danger'],
        ]);

        Categoria::factory()
            ->state(['nombre' => 'Hamburguesas', 'posicion' => 30])
            ->has(Producto::factory()->count(4))
            ->create();
        Categoria::factory()
            ->state(['nombre' => 'Papas Fritas', 'posicion' => 20])
            ->has(Producto::factory()->count(4))
            ->create();
        Categoria::factory()
            ->state(['nombre' => 'Menú Infantil', 'posicion' => 10])
            ->has(Producto::factory()->count(4))
            ->create();
        Categoria::factory()
            ->state(['nombre' => 'Bebidas', 'posicion' => 0])
            ->has(Producto::factory()->count(4))
            ->create();
        Categoria::factory()
            ->state(['nombre' => 'Extras', 'posicion' => -10])
            ->has(Producto::factory()->count(4))
            ->create();

        DB::table('sucursales')->insert([
            [
                'nombre' => 'Retiro',
                'direccion' => 'Calle Retiro 123',
                'telefono' => '+54 11 12345678',
                'maps_url' => 'https://maps.app.goo.gl/bCkiWxjMmrudAgD57',
            ],
            [
                'nombre' => 'San Nicolás',
                'direccion' => 'Calle San Nicolás 123',
                'telefono' => '+54 11 12345678',
                'maps_url' => 'https://maps.app.goo.gl/16QfjaLzZd7gnSkYA',
            ],
            [
                'nombre' => 'Puerto Madero',
                'direccion' => 'Calle Puerto Madero 123',
                'telefono' => '+54 11 12345678',
                'maps_url' => 'https://maps.app.goo.gl/MKBqPQoyFzFDTui68',
            ],
            [
                'nombre' => 'San Telmo',
                'direccion' => 'Calle San Telmo 123',
                'telefono' => '+54 11 12345678',
                'maps_url' => 'https://maps.app.goo.gl/GCVoneDncFt46aSG8',
            ],
            [
                'nombre' => 'Monserrat',
                'direccion' => 'Calle Monserrat 123',
                'telefono' => '+54 11 12345678',
                'maps_url' => 'https://maps.app.goo.gl/jyHF68ZRvJ6ExYxy7',
            ],
            [
                'nombre' => 'Constitución',
                'direccion' => 'Calle Constitución 123',
                'telefono' => '+54 11 12345678',
                'maps_url' => 'https://maps.app.goo.gl/bgAD3E3quUcjWQrp7',
            ],
        ]);
    }
}
