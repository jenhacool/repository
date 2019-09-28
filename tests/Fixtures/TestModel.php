<?php

namespace Jenhacool\Repository\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $fillable = ['name', 'gender'];
}