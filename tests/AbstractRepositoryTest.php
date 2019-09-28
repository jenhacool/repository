<?php

namespace Jenhacool\Repository\Tests\Integration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Jenhacool\Repository\Tests\Fixtures\TestModel;
use Jenhacool\Repository\Tests\Fixtures\TestRepository;
use Jenhacool\Repository\Tests\TestCase;

class AbstractRepositoryTest extends TestCase
{
    protected $repo;

    protected function setUp() :void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->withFactories(__DIR__ . '/factories');

        $this->repo = new TestRepository();
    }

    protected function setUpDatabase(Application $app)
    {
        $app['db']->connection()->getSchemabuilder()->create('test_models', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('gender');
            $table->timestamps();
        });
    }

    /** @test */
    public function it_can_get_all_data()
    {
        factory(TestModel::class, 5)->create();

        $results = $this->repo->all();

        $this->assertCount(5, $results);
    }

    /** @test */
    public function it_can_get_all_data_with_pagination()
    {
        factory(TestModel::class, 20)->create();

        $results = $this->repo->paginate(10);

        $this->assertCount(10, $results);
    }

    /** @test */
    public function it_can_find_data_by_id()
    {
        $model = factory(TestModel::class)->create(['name' => 'abc']);

        $result = $this->repo->find($model->id);

        $this->assertEquals($model->name, $result->name);
    }

    /** @test */
    public function it_can_find_data_by_field_and_value()
    {
        $model1 = factory(TestModel::class)->create(['name' => 'abc']);
        $model2 = factory(TestModel::class)->create(['name' => 'abc']);
        $model3 = factory(TestModel::class)->create(['name' => 'def']);

        $results = $this->repo->findByField('name', 'abc');

        $this->assertCount(2, $results);
        $this->assertTrue($results->contains($model1));
        $this->assertTrue($results->contains($model2));
        $this->assertFalse($results->contains($model3));
    }

    /** @test */
    public function it_can_find_data_by_multiple_fields()
    {
        factory(TestModel::class, 5)->create(['name' => 'abc']);

        $condition = [
            'name' => 'abc',
            ['id', '>', 2]
        ];

        $results = $this->repo->findWhere($condition);

        $this->assertCount(3, $results);
    }

    /** @test */
    public function it_can_find_data_by_field_and_value_in_array()
    {
        $model1 = factory(TestModel::class)->create(['name' => 'abc']);
        $model2 = factory(TestModel::class)->create(['name' => 'def']);
        $model3 = factory(TestModel::class)->create(['name' => 'xyz']);

        $results = $this->repo->findWhereIn('name', ['abc', 'xyz']);

        $this->assertCount(2, $results);
        $this->assertTrue($results->contains($model1));
        $this->assertFalse($results->contains($model2));
        $this->assertTrue($results->contains($model3));
    }

    /** @test */
    public function it_can_create_new_entity()
    {
        $result = $this->repo->create(['name' => 'Tien', 'gender' => 1]);

        $this->assertEquals('Tien', $result->name);
    }

    /** @test */
    public function it_can_update_entity()
    {
        factory(TestModel::class)->create(['name' => 'abc']);

        $result = $this->repo->update(1, ['name' => 'abcdef']);

        $this->assertEquals('abcdef', $result->name);
    }

    /** @test */
    public function it_can_delete_entity()
    {
        factory(TestModel::class)->create(['name' => 'abc']);

        $this->assertTrue($this->repo->delete(1));
    }

    /** @test */
    public function it_can_delete_entity_by_conditions()
    {
        factory(TestModel::class, 5)->create(['name' => 'abc']);

        $condition = [
            'name' => 'abc',
            ['id', '>', 2]
        ];

        $this->assertTrue($this->repo->deleteWhere($condition));
    }

    /** @test */
    public function it_can_find_data_with_scope()
    {
        factory(TestModel::class, 3)->states('male')->create();
        factory(TestModel::class, 3)->states('female')->create();

        $results = $this->repo->maleOnly()->all();

        $this->assertCount(3, $results);
    }

    /** @test */
    public function it_can_sort_data_ascending()
    {
        factory(TestModel::class, 5)->create();

        $data = $this->repo->orderBy('name')->all();

        $sorted = $data->sortBy('name');

        $this->assertEquals($data->pluck('id'), $sorted->pluck('id'));
    }

    /** @test */
    public function it_can_sort_data_descending()
    {
        factory(TestModel::class, 5)->create();

        $data = $this->repo->orderBy('name', 'desc')->all();

        $sorted = $data->sortByDesc('name');

        $this->assertEquals($data->pluck('id'), $sorted->pluck('id'));
    }
}