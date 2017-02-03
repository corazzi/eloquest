# Eloquest
Add simple search from request queries to your models

## Installation
// todo

## Usage
Use the `Sachiano\Eloquest\Eloquest` trait in your model and set up Eloquest search mappings

```php
namespace Acme;

use Illuminate\Database\Eloquent\Model;
use Sachiano\Eloquest\Eloquest;

class Client extends Model
{
    use Eloquest;
    
    private $searchMappings = [
            'name' => [
                'clause' => 'whereLike',
                'match'  => 'loose',
                'aliases' => ['client_name']
            ],
            'whereHas' => [
                'clause' => 'whereHas',
                'aliases' => ['has']
            ],
            'whereDoesntHave' => [
                'clause' => 'whereDoesntHave',
                'aliases' => ['without']
            ]
        ];
}
```

Get your results by using the `searchByRequest` scope, e.g.

```php
$clients = Client::searchByRequest()->get();
```

// tofinish

## Contributing
// todo

## License
// todo