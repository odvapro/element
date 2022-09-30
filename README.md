# Element CMF
![Current tag](https://img.shields.io/github/v/tag/odvapro/element.svg?color=blueviolet)
![License](https://img.shields.io/github/license/odvapro/element)

Admin panel for everything
Element is a beautifully designed administration panel for Everything. Carefully crafted by the O₂ team to make you the most productive developer in the galaxy.

[Site](/documentation)

[Documentation](https://element.odva.pro/docs/)

[Demo](https://element-demo.odva.pro/element/)

```
login: admin
password: adminpass
```

# Quick installation

Dependencies: docker, docker-compose, nginx

To install an element in your project, run the command at the root of your project:

```
composer create-project odvapro/element --no-dev

  or

git clone https://github.com/odvapro/element.git
```

Navigate to the created "element" folder and run the following command:

```
docker-compose up -d
```

## Nginx settings

Enter this rule in nginx config and restart nginx.service:
```
location /element {
    proxy_pass             http://127.0.0.1:85;
    proxy_set_header       Host $host;
}
```

## Configuration

Go to ``` <your domain>/element/ ``` If you are opening this address for the first time, you will see a form to configure a connection to a database.
Fill in all the fields provided, leave the database host unchanged and click the install button.

## Authorization

Then you will see the authorization form

Login "admin", password "adminpass" After the authorization don't forget to change the standard password
