<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Provider;
use App\Models\Product;
use App\Models\Purchase; // Asegúrate de que tu modelo Purchase exista
use App\Models\PurchaseDetail; // Asegúrate de que tu modelo PurchaseDetail exista

// Renombramos la clase a 'CompraTest' (singular)
class CompraTest extends TestCase 
{
    use RefreshDatabase; // Usa la BDD 'fruver_test'

    protected $admin;
    protected $superAdmin;
    protected $cliente;
    protected $provider;
    protected $product;

    /**
     * Esta función se ejecuta ANTES de cada prueba.
     * Crea todos los roles, usuarios y datos necesarios.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // 1. Creamos los Roles (basado en tu BDD)
        $this->adminRole = Role::create(['name' => 'Administrador']);
        $this->superAdminRole = Role::create(['name' => 'SuperAdministrador']);
        $this->clienteRole = Role::create(['name' => 'Cliente']);

        // 2. Creamos los Usuarios para cada rol
        $this->admin = User::create([
            'name' => 'Admin de Compras',
            'email' => 'compras@prueba.com',
            'password' => bcrypt('password'),
            'role_id' => $this->adminRole->id
        ]);
        
        $this->superAdmin = User::create([
            'name' => 'SuperAdmin de Compras',
            'email' => 'superadmin@prueba.com',
            'password' => bcrypt('password'),
            'role_id' => $this->superAdminRole->id
        ]);
        
        $this->cliente = User::create([
            'name' => 'Cliente Curioso',
            'email' => 'cliente@prueba.com',
            'password' => bcrypt('password'),
            'role_id' => $this->clienteRole->id
        ]);
        
        // 3. Creamos un Proveedor de prueba
        $this->provider = Provider::create([
            'name' => 'Proveedor de Prueba',
            'phone' => '1234567'
        ]);
        
        // 4. Creamos un Producto de prueba (con 50 unidades de stock inicial)
        $this->product = Product::create([
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
     * Prueba la ruta POST /purchases (PurchaseController@store)
     * Verifica la validación, la creación y la actualización de stock.
     */
    public function test_admin_puede_registrar_una_compra_y_actualizar_stock(): void
    {
        // --- 1. PREPARAR (Arrange) ---
        // Datos del formulario que coinciden con las reglas de validación de store()
        $purchaseData = [
            'provider_id' => $this->provider->id,
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 10.5, // 10.5 Kg
                    'cost' => 2000.00   // Costo de adquisición
                ]
            ]
        ];

        // --- 2. ACTUAR (Act) ---
        // Actuamos como el admin y hacemos POST a '/purchases'
        $response = $this->actingAs($this->admin)->post('/purchases', $purchaseData);

        // --- 3. CONFIRMAR (Assert) ---
        
        // 3.1. Confirmamos que la BDD tiene la cabecera de la compra
        $this->assertDatabaseHas('purchases', [
            'provider_id' => $this->provider->id,
            'user_id' => $this->admin->id,
            'total_cost' => 21000.00, // (10.5 * 2000)
            'status' => 'completada'
        ]);
        
        // 3.2. Confirmamos que la BDD tiene el detalle
        $this->assertDatabaseHas('purchase_details', [
            'product_id' => $this->product->id,
            'quantity' => 10.5,
            'cost' => 2000.00
        ]);

        // 3.3. ¡CRÍTICO! Confirmamos que el stock se INCREMENTÓ
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 60.5 // (Stock inicial 50 + 10.5 comprados)
        ]);
        
        // 3.4. Confirmamos que nos redirige al 'index'
        $response->assertRedirect(route('purchases.index'));
    }

    /**
     * [READ - Lista]
     * Prueba la ruta GET /purchases (PurchaseController@index)
     */
    public function test_admin_puede_ver_la_lista_de_compras(): void
    {
        // --- 1. PREPARAR (Arrange) ---
        Purchase::create([
            'provider_id' => $this->provider->id,
            'user_id' => $this->admin->id,
            'total_cost' => 12345,
            'status' => 'completada',
        ]);

        // --- 2. ACTUAR (Act) ---
        $response = $this->actingAs($this->admin)->get('/purchases');

        // --- 3. CONFIRMAR (Assert) ---
        $response->assertStatus(200); // 200 = OK
        $response->assertSee('Proveedor de Prueba'); // Vemos el nombre del proveedor
    }

    /**
     * [DELETE - Anular]
     * Prueba la ruta POST /purchases/{purchase}/void (PurchaseController@void)
     * Verifica que el estado cambie a 'anulada' y se revierta el stock.
     */
    public function test_admin_puede_anular_una_compra_y_revertir_stock(): void
    {
        // --- 1. PREPARAR (Arrange) ---
        // 1.1. Simulamos una compra que ya existe y está 'completada'
        $purchase = Purchase::create([
            'provider_id' => $this->provider->id,
            'user_id' => $this->admin->id,
            'total_cost' => 20000,
            'status' => 'completada', // Tu controlador requiere esto
        ]);
        // 1.2. Le añadimos un detalle
        $purchase->details()->create([
            'product_id' => $this->product->id,
            'quantity' => 10.0,
            'cost' => 2000.00
        ]);
        
        // 1.3. Actualizamos el stock (50 + 10 = 60)
        $this->product->increment('stock', 10.0);
        $this->assertDatabaseHas('products', ['id' => $this->product->id, 'stock' => 60.0]);

        // --- 2. ACTUAR (Act) ---
        // Apuntamos a la ruta 'void' como un POST
        $response = $this->actingAs($this->admin)->post(route('purchases.void', $purchase->id));

        // --- 3. CONFIRMAR (Assert) ---
        
        // 3.1. Confirmamos que la compra AÚN EXISTE, pero su estado cambió
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'anulada' // No se borró, se actualizó
        ]);
        
        // 3.2. ¡CRÍTICO! Confirmamos que el stock se REVIRTIÓ
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 50.00 // (Stock 60 - 10 revertidos = 50)
        ]);
        
        // 3.3. Confirmamos la redirección
        $response->assertRedirect(route('purchases.index'));
    }

    // ---------------------------------------------------------------
    // PRUEBAS DE SEGURIDAD (ROLES)
    // ---------------------------------------------------------------

    /**
     * [SEGURIDAD - SuperAdmin]
     * Prueba que el SuperAdmin puede VER pero NO ANULAR.
     */
    public function test_superadmin_puede_ver_lista_pero_no_anular(): void
    {
        // --- 1. PREPARAR (Arrange) ---
        $purchase = Purchase::create([
            'provider_id' => $this->provider->id,
            'user_id' => $this->admin->id,
            'total_cost' => 12345,
            'status' => 'completada',
        ]);

        // --- 2. ACTUAR (Read) ---
        $responseRead = $this->actingAs($this->superAdmin)->get('/purchases');
        
        // --- 3. CONFIRMAR (Read) ---
        $responseRead->assertStatus(200); // Puede ver la lista

        // --- 4. ACTUAR (Void) ---
        // El SuperAdmin intenta anular la compra
        $responseVoid = $this->actingAs($this->superAdmin)->post(route('purchases.void', $purchase->id));
        
        // --- 5. CONFIRMAR (Void) ---
        // El middleware 'role:Administrador' debe detenerlo
        $responseVoid->assertStatus(403); // 403 = Prohibido
    }

    /**
     * [SEGURIDAD - Cliente]
     * Prueba que un rol no autorizado (Cliente) no pueda acceder a compras.
     */
    public function test_cliente_no_puede_acceder_al_modulo_compras(): void
    {
        // --- 1. ACTUAR (Read) ---
        $responseRead = $this->actingAs($this->cliente)->get('/purchases');
        
        // --- 2. CONFIRMAR (Read) ---
        // El middleware 'role:SuperAdministrador,Administrador' debe detenerlo
        $responseRead->assertStatus(403);

        // --- 3. ACTUAR (Create) ---
        $responseCreate = $this->actingAs($this->cliente)->post('/purchases', []);
        
        // --- 4. CONFIRMAR (Create) ---
        $responseCreate->assertStatus(403);
    }
}