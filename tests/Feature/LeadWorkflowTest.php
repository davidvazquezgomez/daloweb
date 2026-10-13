<?php

namespace Tests\Feature;

use App\Enums\RolUsuario;
use App\Models\Lead;
use App\Models\Usuario;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class LeadWorkflowTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2) . '/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        $app->instance('env', 'testing');
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'app.key' => 'base64:' . base64_encode(str_repeat('t', 32)), 'app.env' => 'testing',
            'session.driver' => 'file', 'session.secure' => false, 'cache.default' => 'array',
            'database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'leads.captura' => true, 'mail.default' => 'smtp', 'mail.mailers.smtp.host' => 'smtp.example.test',
        ]);
        $migration = require database_path('migrations/2026_10_13_000001_create_leads_table.php');
        $migration->up();
    }

    public function test_enabled_capture_saves_request_and_marks_delivered_email(): void
    {
        Mail::fake();
        $this->post('/contacto', $this->data())->assertSessionHas('success');
        $lead = Lead::firstOrFail();
        $this->assertSame('nuevo', $lead->estado);
        $this->assertSame('enviado', $lead->correo_estado);
        $this->assertSame('publicidad', $lead->origen);
        $this->assertSame('web-profesional', $lead->pack);
    }

    public function test_failed_email_keeps_request_available_without_success(): void
    {
        Mail::fake();
        config(['mail.default' => 'log']);
        $this->post('/contacto', $this->data())->assertSessionHas('error')->assertSessionMissing('success');
        $this->assertSame('fallido', Lead::firstOrFail()->correo_estado);
        Mail::assertNothingSent();
    }

    public function test_disabled_capture_does_not_persist_contact(): void
    {
        Mail::fake();
        config(['leads.captura' => false]);
        $this->post('/contacto', $this->data())->assertSessionHas('success');
        $this->assertSame(0, Lead::count());
    }

    public function test_invalid_origin_or_money_does_not_change_records(): void
    {
        Mail::fake();
        $this->post('/contacto', array_replace($this->data(), ['origen' => ['publicidad']]))->assertSessionHasErrors('origen');
        $this->get('/')->assertOk();
        $this->assertSame(0, Lead::count());
        $admin = $this->user(RolUsuario::Admin);
        $lead = Lead::create($this->data());
        $this->actingAs($admin)->put('/admin/oportunidades/' . $lead->id, [
            'estado' => 'ganado', 'origen' => 'publicidad', 'venta' => '200.123', 'coste' => '-1',
        ])->assertSessionHasErrors(['venta', 'coste']);
        $this->assertSame('nuevo', $lead->fresh()->estado);
        $this->actingAs($admin)->put('/admin/oportunidades/' . $lead->id, [
            'estado' => 'ganado', 'origen' => 'publicidad',
        ])->assertSessionHasErrors('venta');
        $this->put('/admin/oportunidades/' . $lead->id, [
            'estado' => 'nuevo', 'origen' => 'publicidad', 'coste' => [100], 'notas' => ['nota'],
        ])->assertSessionHasErrors(['coste', 'notas']);
        $this->get('/admin/oportunidades/' . $lead->id)->assertOk();
    }

    public function test_guests_and_non_admins_cannot_access_or_edit_leads(): void
    {
        $lead = Lead::create($this->data());
        $this->get('/admin/oportunidades')->assertRedirect('/login');
        $this->delete('/admin/oportunidades/' . $lead->id)->assertRedirect('/login');
        $user = $this->user(RolUsuario::Usuario);
        $this->actingAs($user)->put('/admin/oportunidades/' . $lead->id, [
            'estado' => 'ganado', 'origen' => 'directo', 'venta' => 500,
        ])->assertRedirect('/login');
        $this->assertSame('nuevo', $lead->fresh()->estado);
    }

    public function test_admin_updates_pipeline_and_metrics_exclude_unknown_costs(): void
    {
        $admin = $this->user(RolUsuario::Admin);
        $lead = Lead::create($this->data());
        $this->actingAs($admin)->put('/admin/oportunidades/' . $lead->id, [
            'estado' => 'ganado', 'origen' => 'publicidad', 'venta' => '500', 'coste' => '150',
        ])->assertRedirect(route('admin.leads.show', $lead));
        $this->assertSame(350.0, $lead->fresh()->margen);
        Lead::create($this->data() + ['estado' => 'ganado', 'venta' => 800]);
        $response = $this->actingAs($admin)->get('/admin/oportunidades')->assertOk();
        $this->assertEquals(350, $response->viewData('metrics')->contribucion);
        $this->assertEquals(1, $response->viewData('metrics')->con_coste);
        $this->get('/admin/oportunidades?origen=organico')->assertOk()->assertViewHas('metrics', fn ($m) => $m->total == 0);
        $this->get('/admin/oportunidades/' . $lead->id)->assertOk()->assertSee('500.00');
    }

    public function test_admin_can_remove_contact_data(): void
    {
        $admin = $this->user(RolUsuario::Admin);
        $lead = Lead::create($this->data());
        $this->actingAs($admin)->delete('/admin/oportunidades/' . $lead->id)->assertRedirect(route('admin.leads'));
        $this->assertNull(Lead::find($lead->id));
    }

    public function test_admin_sees_setup_message_without_new_table(): void
    {
        Schema::drop('leads');
        $this->actingAs($this->user(RolUsuario::Admin))->get('/admin/oportunidades')
            ->assertOk()->assertSee('Seguimiento preparado');
    }

    public function test_utm_origin_classification_does_not_echo_raw_parameters(): void
    {
        $this->get('/?utm_medium=cpc&utm_source=identificador@example.test')->assertOk()
            ->assertSee('name="origen" value="publicidad"', false)
            ->assertDontSee('identificador@example.test');
        $this->get('/?origen[]=publicidad')->assertOk()->assertSee('name="origen" value="directo"', false);
        $this->withHeader('referer', 'https://www.google.es/search?q=consulta-personal')->get('/')
            ->assertOk()->assertSee('name="origen" value="organico"', false)->assertDontSee('consulta-personal');
    }

    private function data(): array
    {
        return ['nombre' => 'Contacto de prueba', 'email' => 'contacto@example.test', 'tipo' => 'web', 'pack' => 'web-profesional', 'origen' => 'publicidad', 'mensaje' => 'Solicito una web.'];
    }

    private function user(RolUsuario $role): Usuario
    {
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function ($table) {
                $table->id(); $table->string('nombre'); $table->string('correo');
                $table->string('contrasena'); $table->string('rol'); $table->timestamps();
                $table->timestamp('creado_en')->nullable(); $table->timestamp('actualizado_en')->nullable();
            });
        }
        return Usuario::create(['nombre' => 'Prueba', 'correo' => 'admin@example.test', 'contrasena' => 'test-password', 'rol' => $role]);
    }
}
