<?php

namespace Tir\Crud\Tests\Unit;

use Tir\Crud\Tests\TestCase;
use Tir\Crud\Services\DataService;
use Tir\Crud\Tests\Models\TestModel;
use Tir\Crud\Tests\Scaffolders\TestScaffolder;

class DataServiceTest extends TestCase
{
    public function test_data_service_class_exists()
    {
        $this->assertTrue(class_exists('Tir\Crud\Services\DataService'));
    }

    public function test_test_model_can_be_created()
    {
        $model = TestModel::create([
            'name' => 'Test Model',
            'email' => 'test@example.com'
        ]);

        $this->assertInstanceOf(TestModel::class, $model);
        $this->assertEquals('Test Model', $model->name);
        $this->assertEquals('test@example.com', $model->email);
    }

    public function test_test_model_soft_delete_works()
    {
        $model = TestModel::create([
            'name' => 'Delete Test',
            'email' => 'delete@example.com'
        ]);

        $model->delete();

        // Test that the model is soft deleted
        $this->assertSoftDeleted('test_models', [
            'id' => $model->id
        ]);

        // Test that we can restore it
        $model->restore();
        $this->assertDatabaseHas('test_models', [
            'id' => $model->id,
            'deleted_at' => null
        ]);
    }

    public function test_scaffolder_returns_correct_model()
    {
        $scaffolder = new \Tir\Crud\Tests\Scaffolders\TestScaffolder();
        $this->assertEquals(TestModel::class, $scaffolder->model());
    }
}
