<?php

namespace Tests\Feature;

use App\Mail\CorreoContacto;
use App\Models\Usuario;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Encryption\MissingAppKeyException;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ProductionReadinessTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        $app->instance('env', 'testing');

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'app.env' => 'testing',
            'app.key' => 'base64:'.base64_encode(str_repeat('t', 32)),
            'session.driver' => 'file',
            'session.secure' => false,
            'cache.default' => 'array',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
    }

    public function test_missing_key_reproduces_the_production_exception(): void
    {
        config(['app.key' => null]);
        $this->withoutExceptionHandling();
        $this->expectException(MissingAppKeyException::class);
        $this->get('/');
    }

    public function test_public_pages_work_without_database_tables(): void
    {
        $this->get('/')->assertOk()->assertSee('DaloWeb')->assertSee('name="_token"', false);
        $this->get('/login')->assertOk();
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_contact_sends_mail_and_displays_server_confirmation(): void
    {
        Mail::fake();
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => 'smtp.example.test']);
        $this->post('/contacto', $this->contactData())
            ->assertRedirect(route('inicio').'#contacto')
            ->assertSessionHas('success');
        Mail::assertSent(CorreoContacto::class, fn ($mail) => $mail->hasTo('hola@daloweb.es') && $mail->email === 'cliente@example.test');
        $this->get('/')->assertSee('Mensaje enviado correctamente.');
    }

    public function test_log_mailer_does_not_report_false_success(): void
    {
        Mail::fake();
        config(['mail.default' => 'log']);
        $this->post('/contacto', $this->contactData())
            ->assertSessionHas('error')->assertSessionMissing('success')
            ->assertSessionHasInput('nombre', 'Cliente');
        Mail::assertNothingSent();
    }

    public function test_smtp_failure_preserves_the_message_and_displays_error(): void
    {
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => 'smtp.example.test']);
        Mail::shouldReceive('to')->once()->with('hola@daloweb.es')->andReturnSelf();
        Mail::shouldReceive('send')->once()->andThrow(new \RuntimeException('SMTP unavailable'));
        $this->post('/contacto', $this->contactData())
            ->assertSessionHas('error')->assertSessionMissing('success')
            ->assertSessionHasInput('mensaje', 'Necesito una página web.');
        $this->get('/')->assertSee('Error al enviar el mensaje.');
    }

    public function test_contact_validation_and_throttling(): void
    {
        Mail::fake();
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/contacto', [])->assertSessionHasErrors(['nombre', 'email', 'tipo', 'mensaje']);
        }
        $this->post('/contacto', [])->assertStatus(429);
        Mail::assertNothingSent();
    }

    public function test_login_rehashes_the_spanish_password_column(): void
    {
        Schema::create('usuarios', function ($table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('contrasena');
            $table->string('rol');
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
        $oldHash = Hash::make('test-password', ['rounds' => 4]);
        $user = Usuario::create([
            'nombre' => 'Admin', 'correo' => 'admin@example.test',
            'contrasena' => $oldHash, 'rol' => 'admin',
        ]);
        $this->assertTrue(Auth::attempt(['correo' => $user->correo, 'password' => 'test-password']));
        $this->assertNotSame($oldHash, $user->fresh()->contrasena);
        $this->assertTrue(Hash::check('test-password', $user->fresh()->contrasena));
    }

    public function test_production_check_rejects_missing_key_and_tables(): void
    {
        config(['app.key' => null]);
        $this->artisan('app:check-production')
            ->expectsOutputToContain('APP_KEY ausente o inválida')
            ->expectsOutputToContain('Tabla usuarios disponible')
            ->assertExitCode(1);
    }

    public function test_production_check_accepts_complete_configuration(): void
    {
        $this->app->instance('env', 'production');
        config([
            'app.url' => 'https://www.daloweb.es', 'app.debug' => false,
            'session.secure' => true,
            'mail.default' => 'smtp', 'mail.mailers.smtp.host' => 'smtp.example.test',
        ]);
        foreach (['usuarios', 'tareas', 'comentarios_tareas', 'gastos', 'ingresos', 'demos', 'personal_access_tokens'] as $table) {
            Schema::create($table, fn ($blueprint) => $blueprint->id());
        }
        $this->artisan('app:check-production')->assertSuccessful();
    }

    private function contactData(): array
    {
        return ['nombre' => 'Cliente', 'email' => 'cliente@example.test', 'tipo' => 'web', 'mensaje' => 'Necesito una página web.'];
    }
}
