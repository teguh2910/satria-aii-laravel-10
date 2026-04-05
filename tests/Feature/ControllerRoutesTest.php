<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerRoutesTest extends TestCase
{
    use RefreshDatabase;

    private function signInUser(): User
    {
        $user = User::create([
            'name' => 'ppic1',
            'email' => 'ppic1@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_dashboard_feature_routes_are_accessible_when_authenticated(): void
    {
        $this->signInUser();

        $this->get('/dashboard')->assertStatus(200);
        $this->get('/sj/dashboard')->assertStatus(200);
        $this->get('/sj_outstanding')->assertStatus(200);
        $this->get('/sj_outstanding_finance')->assertStatus(200);
        $this->get('/sj_error')->assertStatus(200);
    }

    public function test_customer_and_invoice_routes_are_accessible_when_authenticated(): void
    {
        $this->signInUser();

        $this->get('/customer')->assertStatus(200);
        $this->get('/customer/create')->assertStatus(200);
        $this->get('/invoice')->assertStatus(200);
        $this->get('/invoicing')->assertStatus(200);
    }

    public function test_sj_operation_routes_are_accessible_when_authenticated(): void
    {
        $this->signInUser();

        $this->get('/upload/sj/dashboard')->assertStatus(200);
        $this->get('/sj_balik')->assertStatus(200);
        $this->get('/terima_finance')->assertStatus(200);
        $this->get('/create/sj')->assertStatus(200);
    }
}
