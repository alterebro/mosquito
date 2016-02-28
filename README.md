# mosquito

**mosquito** is a Static Site Generator written in PHP that can also be used as a simple Content Management System. It converts your markdown files into HTML using the folder structure as URL router.

Further documentation is available on the mosquito website, which was generated (♫♫ obviously ♫♫) using **mosquito** : http://alterebro.github.io/mosquito/

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

- **DIV** : Single class, one-file, template Engine for PHP with a powerful set of features. By DivEngine ( [https://github.com/divengine/div](https://github.com/divengine/div) ).
- **MarkdownExtra** : PHP Parser for Markdown Extra derived from the original John Gruber's Markdown.pl. By Michel Fortin ( [https://michelf.ca/projects/php-markdown/extra/](https://michelf.ca/projects/php-markdown/extra/) ,
[https://github.com/michelf/php-markdown](https://github.com/michelf/php-markdown) ).
- **armazon.css** minimalistic, lightweight and super simple CSS3 Boilerplate ( [https://github.com/alterebro/armazon.css](https://github.com/alterebro/armazon.css) , [http://alterebro.github.io/armazon.css/](http://alterebro.github.io/armazon.css/) ).
