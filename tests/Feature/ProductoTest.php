<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;

class ProductoTest extends TestCase
{
    use RefreshDatabase; // Usa la BDD 'fruver_test'

    protected $admin;

    /**
     * Esta función se ejecuta ANTES de cada prueba.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $rolAdmin = Role::create(['name' => 'Administrador']);
        $rolSuperAdmin = Role::create(['name' => 'SuperAdministrador']);
        
        $this->admin = User::create([
            'name' => 'Admin de Prueba CRUD',
            'email' => 'admin_crud@prueba.com',
            'password' => bcrypt('password'),
            'role_id' => $rolAdmin->id
        ]);
    }

    // ---------------------------------------------------------------
    // PRUEBAS DEL CRUD
    // ---------------------------------------------------------------

    /**
     * [CREATE]
     * Prueba la ruta POST /products (ProductController@store)
     */
    public function test_admin_puede_crear_un_producto(): void
    {
        $productData = [
            'nombre' => 'Uva de Prueba',
            'descripcion' => 'Uva importada',
            'precio' => 7000.00,
            'stock' => 50.5
        ];

        $response = $this->actingAs($this->admin)->post('/products', $productData);

        $this->assertDatabaseHas('products', [
            'nombre' => 'Uva de Prueba',
            'precio' => 7000.00,
            'stock' => 50.5,
            'is_active' => true 
        ]);
        $response->assertRedirect(route('products.index'));
    }

    /**
     * [READ - Lista]
     * Prueba la ruta GET /products (ProductController@index)
     */
    public function test_admin_puede_ver_la_lista_de_productos(): void
    {
        Product::create(['nombre' => 'Fresa de Prueba', 'precio' => 4000, 'stock' => 10]);
        $response = $this->actingAs($this->admin)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Fresa de Prueba');
    }

    /**
     * [UPDATE]
     * Prueba la ruta PUT /products/{product} (ProductController@update)
     */
    public function test_admin_puede_actualizar_un_producto(): void
    {
        $product = Product::create(['nombre' => 'Producto Antiguo', 'precio' => 10, 'stock' => 10]);
        $updateData = [
            'nombre' => 'Producto Actualizado',
            'descripcion' => $product->descripcion, // Reenviamos datos no nulos
            'precio' => 50.00,
            'stock' => 25.00
        ];

        $response = $this->actingAs($this->admin)->put('/products/' . $product->id, $updateData);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'nombre' => 'Producto Actualizado',
            'precio' => 50.00
        ]);
        $response->assertRedirect(route('products.index'));
    }
    
    /**
     * [DELETE - Soft Delete]
     * Prueba la ruta DELETE /products/{product} (ProductController@destroy)
     */
    public function test_admin_puede_deshabilitar_un_producto(): void
    {
        $product = Product::create(['nombre' => 'Producto a Deshabilitar', 'precio' => 10, 'stock' => 10, 'is_active' => true]);
        $response = $this->actingAs($this->admin)->delete('/products/' . $product->id);
        
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_active' => false 
        ]);
        
        $response->assertRedirect(route('products.index'));
    }

    /**
     * [EXTRA - ENABLE]
     * Prueba la ruta POST /products/{product}/enable (ProductController@enable)
     */
    public function test_admin_puede_habilitar_un_producto(): void
    {
        $product = Product::create(['nombre' => 'Producto Inactivo', 'precio' => 10, 'stock' => 10, 'is_active' => false]);
        $this->assertFalse((bool)$product->is_active);
        
        $response = $this->actingAs($this->admin)->post(route('products.enable', $product->id));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_active' => true 
        ]);
        $response->assertRedirect();
    }
    
    /**
     * [SEGURIDAD]
     * Prueba que un invitado no puede acceder al índice de productos.
     */
    public function test_invitado_no_puede_acceder_al_crud_de_productos(): void
    {
        $response = $this->get('/products');
        $response->assertRedirect('/login');

        $response = $this->post('/products', ['nombre' => 'Test', 'precio' => 1, 'stock' => 1]);
        $response->assertRedirect('/login');
    }
    
    /**
     * [VALIDACIÓN]
     * Prueba la regla 'unique' del método store()
     */
    public function test_admin_no_puede_crear_producto_con_nombre_duplicado(): void
    {
        Product::create(['nombre' => 'Uva Repetida', 'precio' => 7000, 'stock' => 50]);
        $productData = [
            'nombre' => 'Uva Repetida', // Nombre duplicado
            'descripcion' => '...',
            'precio' => 8000.00,
            'stock' => 20
        ];

        $response = $this->actingAs($this->admin)->post('/products', $productData);

        $response->assertSessionHasErrors('nombre');
        $this->assertDatabaseCount('products', 1);
    }
}