<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Provider;
use App\Models\Product;
use App\Models\Purchase;

class ProviderTest extends TestCase
{
    use RefreshDatabase; 

    protected $admin;
    protected $superAdmin;
    protected $cliente;
    protected $provider;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adminRole = Role::create(['name' => 'Administrador']);
        $this->superAdminRole = Role::create(['name' => 'SuperAdministrador']);
        $this->clienteRole = Role::create(['name' => 'Cliente']);

        $this->admin = User::create([
            'name' => 'Admin de Proveedores',
            'email' => 'admin_prov@prueba.com',
            'password' => bcrypt('password'),
            'role_id' => $this->adminRole->id
        ]);
        
        $this->superAdmin = User::create([
            'name' => 'SuperAdmin de Proveedores',
            'email' => 'superadmin_prov@prueba.com',
            'password' => bcrypt('password'),
            'role_id' => $this->superAdminRole->id
        ]);
        
        $this->cliente = User::create([
            'name' => 'Cliente Curioso',
            'email' => 'cliente_prov@prueba.com',
            'password' => bcrypt('password'),
            'role_id' => $this->clienteRole->id
        ]);
        
        $this->provider = Provider::create([
            'name' => 'Proveedor Inicial',
            'phone' => '111222333'
        ]);
    }

    // ---------------------------------------------------------------
    // PRUEBAS DEL CRUD
    // ---------------------------------------------------------------

    public function test_admin_puede_crear_un_proveedor(): void
    {
        $providerData = [ 'name' => 'Nuevo Proveedor', 'phone' => '987654321', ];
        $response = $this->actingAs($this->admin)->post('/providers', $providerData);
        $this->assertDatabaseHas('providers', ['name' => 'Nuevo Proveedor', 'phone' => '987654321']);
        $response->assertRedirect(route('providers.index'));
        $response->assertSessionHas('success');
    }

    public function test_admin_puede_ver_la_lista_de_proveedores(): void
    {
        $response = $this->actingAs($this->admin)->get('/providers');
        $response->assertStatus(200);
        $response->assertSee('Proveedor Inicial');
    }

    public function test_admin_puede_ver_la_pagina_de_editar_proveedor(): void
    {
        $response = $this->actingAs($this->admin)->get(route('providers.edit', $this->provider->id));
        $response->assertStatus(200);
        $response->assertSee('Proveedor Inicial');
    }

    public function test_admin_puede_actualizar_un_proveedor(): void
    {
        $updateData = [ 'name' => 'Proveedor Actualizado', 'phone' => '555555555', ];
        $response = $this->actingAs($this->admin)->put(route('providers.update', $this->provider->id), $updateData);
        $this->assertDatabaseHas('providers', ['id' => $this->provider->id, 'name' => 'Proveedor Actualizado']);
        $response->assertRedirect(route('providers.index'));
        $response->assertSessionHas('success');
    }
    
    public function test_admin_puede_eliminar_un_proveedor_sin_compras(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('providers.destroy', $this->provider->id));
        $this->assertDatabaseMissing('providers', ['id' => $this->provider->id]);
        $response->assertRedirect(route('providers.index'));
        $response->assertSessionHas('success');
    }
    
    public function test_admin_no_puede_eliminar_un_proveedor_con_compras_asociadas(): void
    {
        $product = Product::create(['nombre' => 'Test Product', 'precio' => 10, 'stock' => 10]);
        Purchase::create([
            'provider_id' => $this->provider->id,
            'user_id' => $this->admin->id,
            'total_cost' => 100,
            'status' => 'completada',
        ]);
        $response = $this->actingAs($this->admin)->delete(route('providers.destroy', $this->provider->id));
        $this->assertDatabaseHas('providers', ['id' => $this->provider->id]);
        $response->assertRedirect(route('providers.index'));
        $response->assertSessionHas('error');
    }

    // ---------------------------------------------------------------
    // PRUEBAS DE SEGURIDAD (ROLES)
    // ---------------------------------------------------------------

    public function test_superadmin_puede_ver_pero_no_crear_o_editar(): void
    {
        // 1. PRUEBA DE LECTURA (Debe funcionar)
        $responseRead = $this->actingAs($this->superAdmin)->get(route('providers.index'));
        $responseRead->assertStatus(200);

        // 2. PRUEBA DE FORMULARIO CREAR (Debe fallar, según tu código)
        $responseCreateForm = $this->actingAs($this->superAdmin)->get(route('providers.create'));
        
        // --- ¡¡¡CORRECCIÓN HECHA AQUÍ!!! ---
        // Tu ruta bloquea al SuperAdmin. La prueba ahora confirma eso.
        $responseCreateForm->assertStatus(403); 

        // 3. PRUEBA DE CREAR (POST) (Debe fallar)
        $responseCreate = $this->actingAs($this->superAdmin)->post('/providers', ['name' => 'Test', 'phone' => '123']);
        $responseCreate->assertStatus(403);

        // 4. PRUEBA DE EDITAR (PUT) (Debe fallar)
        $responseUpdate = $this->actingAs($this->superAdmin)->put(route('providers.update', $this->provider->id), ['name' => 'Update']);
        $responseUpdate->assertStatus(403);
    }
    
    public function test_cliente_no_puede_acceder_al_modulo_proveedores(): void
    {
        $responseRead = $this->actingAs($this->cliente)->get(route('providers.index'));
        $responseRead->assertStatus(403);

        $responseCreate = $this->actingAs($this->cliente)->post('/providers', ['name' => 'Test', 'phone' => '123']);
        $responseCreate->assertStatus(403);
    }
}