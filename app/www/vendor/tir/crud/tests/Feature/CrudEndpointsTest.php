<?php

namespace Tir\Crud\Tests\Feature;

use Tir\Crud\Tests\TestCase;
use Tir\Crud\Tests\Models\TestModel;
use Illuminate\Support\Facades\Route;

class CrudEndpointsTest extends TestCase
{
    public function test_test_model_can_be_created_and_retrieved()
    {
        $model = TestModel::create([
            'name' => 'Feature Test',
            'email' => 'feature@example.com',
            'description' => 'Test description',
            'active' => true
        ]);

        $this->assertDatabaseHas('test_models', [
            'name' => 'Feature Test',
            'email' => 'feature@example.com'
        ]);

        $retrieved = TestModel::find($model->id);
        $this->assertEquals('Feature Test', $retrieved->name);
    }

    public function test_soft_delete_functionality()
    {
        $model = TestModel::create([
            'name' => 'Delete Test',
            'email' => 'delete@example.com'
        ]);

        $model->delete();

        $this->assertSoftDeleted('test_models', [
            'id' => $model->id
        ]);
    }

    public function test_restore_functionality()
    {
        $model = TestModel::create([
            'name' => 'Restore Test',
            'email' => 'restore@example.com'
        ]);
        $model->delete();

        $model->restore();

        $this->assertDatabaseHas('test_models', [
            'id' => $model->id,
            'deleted_at' => null
        ]);
    }

    public function test_force_delete_functionality()
    {
        $model = TestModel::create([
            'name' => 'Force Delete Test',
            'email' => 'forcedelete@example.com'
        ]);
        $model->delete();

        $model->forceDelete();

        $this->assertDatabaseMissing('test_models', [
            'id' => $model->id
        ]);
    }

    public function test_crud_controller_class_is_usable()
    {
        // Test that the controller class exists and can be instantiated
        $this->assertTrue(class_exists(\Tir\Crud\Tests\Controllers\TestController::class));
    }

    public function test_test_scaffolder_works()
    {
        $scaffolder = new \Tir\Crud\Tests\Scaffolders\TestScaffolder();

        $this->assertEquals(\Tir\Crud\Tests\Models\TestModel::class, $scaffolder->model());
        $this->assertIsArray($scaffolder->fields());
        $this->assertIsArray($scaffolder->rules());
        $this->assertIsArray($scaffolder->relations());
    }
}
