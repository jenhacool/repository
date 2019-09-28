# Laravel Repositories

Simple package used to abstract the data layer

## Install

```terminal
composer require jenhacool/repository
```

## Generator

To generate repository for your Model, run this command:

```terminal
php artisan make:repository Model
```

## Usage

### Create a Model

```php
namespace App;

class People extends Model {
    protected $fillable = [
        'name',
        'gender
    ]
}
```

### Create a Repository

```php
namespace App\Repositories;

use Jenhacool\Repository\AbstractRepository;
use App\People;

class PeopleRepository extends BaseRepository {
    protected $model = People::class;
}
```

## Methods

- all($columns = array('*'))
- paginate($limit = null, $columns = ['*'])
- find($id, $columns = ['*'])
- findByField($field, $value, $columns = ['*'])
- findWhere(array $where, $columns = ['*'])
- findWhereIn($field, array $where, $columns = [*])
- create(array $data)
- update(array $data, $id)
- delete($id)
- deleteWhere(array $where)
- orderBy($column, $direction = 'asc')

## Contact
Open an issue on GitHub if you have any problems or suggestions.

## License
The contents of this repository is released under the [MIT license](http://opensource.org/licenses/MIT).