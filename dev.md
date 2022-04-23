#For developers
Instructions for developers

## Installation - docker-compose
- git clone https://github.com/odvapro/element.git
- cd to elemenet
- cp sample.env .env
- change .env - describe
- docker-compose up -d
- set nginx
- build vueproject
- open


## Run tests
```
vendor/bin/codecept run
```

## Run cypress tests
```
run vue on localhost:8080
-> npx cypress open
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
