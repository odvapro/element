# Installation

* [Requirements](#requirements)
* [Installation via composer](#installation-via-composer)
* [Server settings](#server-settings)
* [Configuration](#configuration)
* [Authorization](#authorization)

## Requirements
`composer` `php7` `phalcon`

## Installation via composer
To install in your project, execute a command in the root of your project
```
composer create-project odvapro/element --no-dev
```

## Server settings
### Apache
Ensure mod_rewrite is enabled

### Nginx
Ensure php-file processing is active on your site.
Add the following rows into your site's configuration file.

```
if ( $uri ~ "^/element(.*)" ) {
	set $elementPrefix "/element/public";
	set $elementExecFile "/index.html";
	set $elementFile $1;
}

if ( $uri ~ "^/element/api(.*)" ) {
	set $elementPrefix "/element/api";
	set $elementExecFile "/index.php";
	set $elementFile $1;
}

if ( $uri ~ "^/element/public(.*)" ) {
	set $elementPrefix "/element/public";
	set $elementExecFile "/index.html";
	set $elementFile $1;
}

location /element/ {
	try_files $elementPrefix$elementFile $elementPrefix$elementFile/ $elementPrefix$elementExecFile?$args;
}
```

## Configuration
Go to `<your domain>/element/`
If you are opening this address for the first time, you will see a form to configure a connection to a database
![Image of install form](/documentation/img/install.png)
Fill in all of the fields and click the "Install" button


## Authorization
Then you will see the authorization form
![Image of auth form](/documentation/img/auth.png)
Login "admin", password "adminpass"
After the authorization don't forget to change the standard password