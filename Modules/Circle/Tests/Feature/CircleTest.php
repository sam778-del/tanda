<?php

namespace Modules\Circle\Tests\Feature;

use Modules\Circle\Models\Circle;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CircleTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_public_circle_object()
    {
        $circle = Circle::factory()->publicCircle()->withCircleMembers()->create();
        $this->assertEquals($circle->name, Circle::first()->name);
    }

    public function test_view_public_circle()
    {
        $this->withoutExceptionHandling();
        $circle = Circle::factory()->publicCircle()->withCircleMembers()->create();

        $response = $this->get(route('circle.public.show', $circle->id));

        $response->assertStatus(200)
            ->assertJsonFragment([
            'message' => 'success'
        ]);
    }
}
