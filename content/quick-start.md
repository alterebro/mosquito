<!--
order : 1
-->

## Basic Info

Generating a Static Site :

- Get the mosquito generator, you can clone it from the github repo :

```bash
$ mkdir mosquito
$ cd mosquito
$ git clone --depth=1 git://github.com/alterebro/mosquito.git
```

- Create / modify your content and store it on the `/content` folder.
- Build your mosquito site :

```bash
$ # build static site
$ php -f index.php build
```
- By default, the site is generated on the `/dist` folder (you can change that) to check everything worked as expected, start a local web server :

```bash
$ # starts server
$ php -S localhost:8000 -t dist 	
```
