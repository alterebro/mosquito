<!--
title : Quick Start
description : How to start with mosquito in some simple steps
order : 1
-->

## Quick Start

**mosquito** is simple and easy, you only need a couple of minutes to have a fully operational website running on
your computer. Just follow these steps :

- Download the latest **mosquito** release from the project repository : https://github.com/alterebro/mosquito/releases/latest and unzip the package or clone it `git clone --depth=1 git://github.com/alterebro/mosquito.git`
- Open a terminal and go wherever you have put the unpacked mosquito files <br />`cd /my/folder/mosquito/`
- Run the build script <br />`$ php -f index.php build` <br />This command will generate the static site on the folder `/dist`
- Preview your generated site, with the following command <br />`$ php -S localhost:8000 -t dist` <br />a local web server will start on the URL http://localhost:8000
- That's all, now you can modify the contents of the site by creating/editing markdown files on the `/content` folder.

## Ultra-Fast Start

Using this other option is even faster, open a terminal, run the following one-liner command and you'll have a mosquito site running on the URL http://localhost:8000 in a matter of seconds.

```bash
mkdir mosquito && curl -L https://github.com/alterebro/mosquito/tarball/v0.1.0 | tar zx -C mosquito --strip-components=1 && cd mosquito && mkdir media && php -f index.php build && php -S localhost:8000 -t dist
```

The line above execute the following commands altogether:
```bash
# It creates the mosquito folder
$ mkdir mosquito
# Downloads the mosquito files and extracts them on the previous created folder
$ curl -L https://github.com/alterebro/mosquito/tarball/v0.1.0 | tar zx -C mosquito --strip-components=1
# It gets inside the created folder
$ cd mosquito
# create the assets folder
$ mkdir media
# Builds and generates the Static Site.
$ php -f index.php build
# Starts a web server on http://localhost:8000
$ php -S localhost:8000 -t dist
```

Later on, the two commands you'll be using will be the last two:

- To generate the Static Site `php -f index.php build`
- To Start a local web server for testing `php -S localhost:8000 -t dist`
<br />By default, the site is generated on the `/dist` folder, if you change that on the settings, you should also change it on the command.
- To stop anytime the local web server created with the command above, you can do it with the keyboard shortcut `ctrl + c`


### Creating Content

The content you create has to be stored on the `/content` folder (you can change this on the settings), simply create and modify your content in markdown files with a `.md` extension, store them on the content folder and *mosquito* will parse it and create the HTML page. Just remember when you create a subfolder, it has to have a `index.md` file within it.


### Configuration

The main configuration of mosquito is done on the `index.php` file located on the root of the project folder. Open the file and you'll see at the top of the code an array named `$_CONFIG`, that stores the main settings of the mosquito app. There you'll be able to change according to your needs.
