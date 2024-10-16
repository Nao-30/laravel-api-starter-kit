<?php

use App\Models\Foo;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('bcupu');
    Storage::fake('bcupr');
    $this->user = $this->getUserByEmail();
    $this->actingAs($this->user, 'sanctum');
});

it('can create a foo', function () {
    $data = [
        'name' => 'Test Foo',
        'image' => $this->getFakeImage('foo.jpg')
    ];

    $response = $this->postJson(env('SYSTEM_API').'api/v1/foo/create', $data);
    $response->assertStatus(201)
        ->assertJson([
            'message' => 'تمت العملية'
        ]);

    $this->assertDatabaseHas('foos', [
        'name' => 'Test Foo',
    ]);

    $foo = Foo::first();
    Storage::disk('bcupu')->assertExists($foo->getFirstMedia('image')->id . '/' . $foo->getFirstMedia('image')->file_name);
    Storage::disk('local')->put('responses/foo/foo.store.json', json_encode($response->collect()));

});

it('can update a foo', function () {
    $foo = Foo::factory()->create(['name' => 'Old Name']);

    $data = [
        'name' => 'Updated Name'
    ];

    $response = $this->patchJson(env('SYSTEM_API').'api/v1/foo/update/'.$foo->id, $data);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'تمت العملية'
        ]);

    $this->assertDatabaseHas('foos', [
        'id' => $foo->id,
        'name' => 'Updated Name',
    ]);
    Storage::disk('local')->put('responses/foo/foo.update.json', json_encode($response->collect()));
});

it('can show a foo', function () {
    $foo = Foo::factory()->create(['name' => 'Test Foo']);

    $response = $this->getJson(env('SYSTEM_API').'api/v1/foo/show/' . $foo->id);

    $response->assertStatus(200)
        ->assertJson([
            'result' => [
                'id' => $foo->id,
                'name' => 'Test Foo',
            ],
            'message' => 'تمت العملية'
        ]);
    Storage::disk('local')->put('responses/foo/foo.show.json', json_encode($response->collect()));
});

it('can show all foos', function () {
    Foo::factory()->count(3)->create();

    $response = $this->getJson(env('SYSTEM_API').'api/v1/foo/');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'result' => [
                'data' => [
                    '*' => ['id', 'name', 'image']
                ]
            ],
            'message'
        ]);
    Storage::disk('local')->put('responses/foo/foo.show.all.json', json_encode($response->collect()));
});

it('can delete a foo', function () {
    $foo = Foo::factory()->create(['name' => 'Test Foo']);

    $response = $this->deleteJson(env('SYSTEM_API').'api/v1/foo/delete/'.$foo->id);

    $response->assertStatus(204);

    $this->assertSoftDeleted('foos', [
        'id' => $foo->id,
    ]);
});
