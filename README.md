# mosquito

**mosquito** is a Static Site Generator written in PHP that can also be used as a simple Content Management System. It converts your markdown files into HTML using the folder structure as URL router.

## Requirements

- PHP ( *>= 5.4.0* ), when using mosquito as CMS, the Apache `mod_rewrite` is required in order to create the friendly URLs.

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

## Libraries Used :

- **Text-template** : Single-Class PHP5 template engine with support for if/loops/filters by Matthias Leuffen. ( https://github.com/dermatthes/text-template , https://dermatthes.github.io/text-template/ )
- **ParseDown** : GitHub flavoured Markdown Parser in PHP by Emanuil Rusev. ( https://github.com/erusev/parsedown )
- **armazon.css** minimalistic, lightweight and super simple CSS3 Boilerplate ( https://github.com/alterebro/armazon.css , http://alterebro.github.io/armazon.css/ )
