<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
// uses(RefreshDatabase::class);
test('Employee can view logs', function () {
    
        $employee = User::factory()->create(['role' => 'employee']);
        
        $this->actingAs($employee);
        $response = $this->get(route('my.logs'));
    
        $response->assertStatus(200);
    
});
