<?php

namespace Jenhacool\Repository\Tests\Fixtures;

use Jenhacool\Repository\AbstractRepository;

class TestRepository extends AbstractRepository
{
    protected $model = TestModel::class;

    public function scopeMaleOnly()
    {
        return $this->addScope(function($query) {
            return $query->where('gender', '=', '1');
        });
    }
}