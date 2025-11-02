<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    protected $adminRole, $empleadoRole, $clienteRole;
    protected $empleado;
    protected $producto;

    protected function setUp(): void
    {
        parent::setUp();
        
        // 1. Creamos TODOS los roles que el sistema necesita
        $this->adminRole = Role::create(['name' => 'Administrador']);
        $this->empleadoRole = Role::create(['name' => 'Empleado']);
        $this->clienteRole = Role::create(['name' => 'Cliente']);

        // 2. Creamos un Empleado
        $this->empleado = User::create([
            'name' => 'Empleado de Prueba',
            'email' => 'empleado@prueba.com',
            'password' => bcrypt('password'),
            'role_id' => $this->empleadoRole->id
        ]);
        
        // 3. Creamos un Producto
        $this->producto = Product::create([
            'nombre' => 'Manzana de Prueba',
            'descripcion' => 'Una manzana',
            'precio' => 3000,
            'stock' => 50.00
        ]);
    }

    // ---------------------------------------------------------------
    // PRUEBAS DEL CRUD
    // ---------------------------------------------------------------

    /**
     * [CREATE]
     * Prueba la ruta POST /sales (sales.store)
     * ¡VERSIÓN CORREGIDA!
     */
    public function test_empleado_puede_crear_una_venta_y_descontar_stock(): void
    {
        // --- 1. PREPARAR (Arrange) ---
        
        // 1.1. Creamos el carrito como un array de PHP
        // Tu controlador espera 'id' y 'quantity' 
        $cartArray = [
            [
                'id' => $this->producto->id,
                'quantity' => 5.00 
            ]
        ];

        // 1.2. Datos del formulario (coincidiendo con la validación)
        $saleData = [
            'cliente' => 'Cliente Mostrador',
            'metodo_pago' => 'efectivo',
            'monto_recibido' => 20000.00, // Tu controlador valida que sea >= al total
            
            // 1.3. Convertimos el array a un string JSON 
            'cart' => json_encode($cartArray) 
        ];

        // --- 2. ACTUAR (Act) ---
        $response = $this->actingAs($this->empleado)->post('/sales', $saleData);

        // --- 3. CONFIRMAR (Assert) ---
        
        // 3.1. Confirmamos que la venta SÍ se creó
        // El controlador calcula el total (5 * 3000 = 15000)
        // y el cambio (20000 - 15000 = 5000)
        $this->assertDatabaseHas('sales', [
            'cliente' => 'Cliente Mostrador',
            'total' => 15000.00,
            'cambio' => 5000.00
        ]);
        
        // 3.2. Confirmamos que el detalle SÍ se creó
        $this->assertDatabaseHas('sale_details', [
            'product_id' => $this->producto->id,
            'cantidad' => 5.00
        ]);

        // 3.3. Confirmamos que el stock SÍ se descontó
        $this->assertDatabaseHas('products', [
            'id' => $this->producto->id,
            'stock' => 45.00 // (Stock inicial 50 - 5 vendidos = 45)
        ]);
        
        // 3.4. Confirmamos la redirección
        $response->assertStatus(302);
    }

    /**
     * [READ - Lista]
     * Prueba la ruta GET /sales (sales.index)
     */
    public function test_empleado_puede_ver_la_lista_de_ventas(): void
    {
        Sale::create(['user_id' => $this->empleado->id, 'cliente' => 'Cliente para Lista', 'total' => 1000.00, 'metodo_pago' => 'efectivo']);
        $response = $this->actingAs($this->empleado)->get('/sales');
        $response->assertStatus(200);
        $response->assertSee('Cliente para Lista');
    }
    
    /**
     * [READ - Detalle]
     * Prueba la ruta GET /sales/{sale} (sales.show)
     */
    public function test_empleado_puede_ver_el_detalle_de_una_venta(): void
    {
        $sale = Sale::create(['user_id' => $this->empleado->id, 'cliente' => 'Cliente Detalle', 'total' => 12345.00, 'metodo_pago' => 'transferencia']);
        $response = $this->actingAs($this->empleado)->get('/sales/' . $sale->id);
        $response->assertStatus(200);
        $response->assertSee('Cliente Detalle');
        $response->assertSee('$12,345'); 
    }

    /**
     * [DELETE / CANCELAR]
     * Prueba la ruta DELETE /sales/{sale} (sales.destroy)
     */
    public function test_empleado_puede_cancelar_una_venta_y_restituir_stock(): void
    {
        // --- 1. PREPARAR (Arrange) ---
        $sale = Sale::create([
            'user_id' => $this->empleado->id,
            'cliente' => 'Cliente a Cancelar',
            'total' => 6000.00,
            'metodo_pago' => 'efectivo',
            'estado' => 'pagada' // Requerido por tu controlador
        ]);
        $sale->details()->create([
            'product_id' => $this->producto->id,
            'cantidad' => 2.00,
            'precio_unitario' => 3000.00,
            'subtotal' => 6000.00
        ]);
        $this->producto->update(['stock' => 48.00]);

        // --- 2. ACTUAR (Act) ---
        $response = $this->actingAs($this->empleado)->delete('/sales/' . $sale->id);

        // --- 3. CONFIRMAR (Assert) ---
        
        // 3.1. Confirmamos que la venta AHORA ESTÁ 'cancelada'.
        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'estado' => 'cancelada' 
        ]);
        
        // 3.2. Confirmamos que los detalles AÚN EXISTEN
        $this->assertDatabaseHas('sale_details', [
            'sale_id' => $sale->id
        ]);
        
        // 3.3. Confirmamos que el stock se restituyó (48 + 2 = 50)
        $this->assertDatabaseHas('products', [
            'id' => $this->producto->id, 
            'stock' => 50.00
        ]);
        
        $response->assertStatus(302);
    }
    
    /**
     * [SEGURIDAD]
     * Prueba que un rol no autorizado (Cliente) no pueda acceder a ventas.
     */
    public function test_cliente_no_puede_acceder_al_modulo_de_ventas(): void
    {
        $cliente = User::create([
            'name' => 'Cliente Curioso',
            'email' => 'cliente@prueba.com',
            'password' => bcrypt('password'),
            'role_id' => $this->clienteRole->id 
        ]);
        $response = $this->actingAs($cliente)->get('/sales');
        $response->assertStatus(403);
    }
}