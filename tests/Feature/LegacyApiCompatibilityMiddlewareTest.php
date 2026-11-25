<?php

namespace Tests\Feature;

use App\Http\Middleware\LegacyApiCompatibilityMiddleware;
use App\Models\ApiClient;
use App\Models\ApiToken;
use App\Support\ApiCredentialContext;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class LegacyApiCompatibilityMiddlewareTest extends TestCase
{
    public function test_legacy_credential_comparison_requires_matching_active_key(): void
    {
        $middleware = new class extends LegacyApiCompatibilityMiddleware {
            public function credentialIsValid(?ApiToken $credential, string $provided): bool
            {
                return $this->legacyCredentialIsValid($credential, $provided);
            }
        };

        $active = new ApiToken(['token' => 'test-only-legacy-key', 'status' => 1]);
        $inactive = new ApiToken(['token' => 'test-only-legacy-key', 'status' => 0]);

        $this->assertTrue($middleware->credentialIsValid($active, 'test-only-legacy-key'));
        $this->assertFalse($middleware->credentialIsValid($active, 'different-key'));
        $this->assertFalse($middleware->credentialIsValid($inactive, 'test-only-legacy-key'));
        $this->assertFalse($middleware->credentialIsValid(null, 'test-only-legacy-key'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (!extension_loaded('pdo_sqlite')) {
            return;
        }

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        Schema::connection('sqlite')->create('api_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_unit_id');
            $table->string('token');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function test_active_legacy_bearer_key_is_accepted_and_audit_context_contains_no_key(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('The pdo_sqlite extension is required for the isolated legacy-key test.');
        }

        DB::table('api_tokens')->insert([
            'organization_unit_id' => 27,
            'token' => 'test-only-legacy-key',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $request = Request::create('/api/v1/tender', 'POST', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer test-only-legacy-key',
        ]);

        $response = (new LegacyApiCompatibilityMiddleware())->handle(
            $request,
            fn ($nextRequest) => response()->json([
                'organization_unit_id' => $nextRequest->attributes->get('api_credential')->organizationUnitId,
                'audit_id' => $nextRequest->attributes->get('api_credential')->auditIdentifier(),
            ]),
            'tender:read',
            'bearer'
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(27, $response->getData()->organization_unit_id);
        $this->assertSame('legacy:1', $response->getData()->audit_id);
        $this->assertStringNotContainsString('test-only-legacy-key', $response->getContent());
    }

    public function test_inactive_legacy_key_is_rejected(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('The pdo_sqlite extension is required for the isolated legacy-key test.');
        }

        DB::table('api_tokens')->insert([
            'organization_unit_id' => 27,
            'token' => 'test-only-inactive-key',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $request = Request::create('/api/v1/tender', 'POST', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer test-only-inactive-key',
        ]);
        $response = (new LegacyApiCompatibilityMiddleware())->handle(
            $request,
            fn () => response()->json(['unexpected' => true]),
            'tender:read',
            'bearer'
        );

        $this->assertSame(401, $response->getStatusCode());
    }

    public function test_sanctum_api_client_requires_the_route_ability_and_agency_scope(): void
    {
        $client = Mockery::mock(ApiClient::class)->makePartial();
        $client->id = 9;
        $client->organization_unit_id = 27;
        $client->shouldReceive('isActive')->once()->andReturn(true);

        $accessToken = Mockery::mock();
        $accessToken->shouldReceive('can')->once()->with('tender:read')->andReturn(true);
        $accessToken->shouldReceive('getKey')->once()->andReturn(51);
        $client->shouldReceive('currentAccessToken')->once()->andReturn($accessToken);

        $guard = Mockery::mock();
        $guard->shouldReceive('user')->once()->andReturn($client);
        Auth::shouldReceive('guard')->once()->with('sanctum')->andReturn($guard);
        Auth::shouldReceive('shouldUse')->once()->with('sanctum');

        $request = Request::create('/api/v1/tender', 'POST', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer test-only-sanctum-token',
        ]);

        $response = (new LegacyApiCompatibilityMiddleware())->handle(
            $request,
            fn ($nextRequest) => response()->json([
                'mechanism' => $nextRequest->attributes->get('api_credential')->mechanism,
                'client_id' => $nextRequest->attributes->get('api_credential')->clientId,
            ]),
            'tender:read',
            'bearer'
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('sanctum', $response->getData()->mechanism);
        $this->assertSame(9, $response->getData()->client_id);
    }

    public function test_both_bearer_and_body_credentials_are_rejected_as_ambiguous(): void
    {
        $request = Request::create('/api/v1/tender', 'POST', [
            'token' => 'test-only-body-key',
        ], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer test-only-bearer-key',
        ]);

        $response = (new LegacyApiCompatibilityMiddleware())->handle(
            $request,
            fn () => response()->json(['unexpected' => true]),
            'tender:read',
            'bearer'
        );

        $this->assertSame(400, $response->getStatusCode());
    }
}
