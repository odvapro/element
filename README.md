# Element CMF
Admin panel on Phalcon + Vue

## Run tests
```
vendor/bin/codecept run
vendor/bin/codecept run api CatalogCest:getProducts
vendor/bin/codecept run api CatalogCest:getProducts --debug
```

## Migrations
Generate
```
vendor/bin/phalcon.php migration generate
```
Run
```
vendor/bin/phalcon.php migration run
```
