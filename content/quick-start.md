<!--
order : 1
-->

## Quick Start

**mosquito** is simple and easy, you only need a couple of minutes to have a fully operational website running on
your computer. Just follow these steps :

- Download the latest **mosquito** release from the project repository : https://github.com/alterebro/mosquito/releases/latest
 and unzip the package.
- Open a terminal and go wherever you have put the unpacked mosquito files <br />`cd /my/folder/mosquito/`
- Run the build script <br />`$ php -f index.php build` <br />This command will generate the static site on the folder `/dist`
- Preview your generated site, with the following command <br />`$ php -S localhost:8000 -t dist` <br />a local web server will start on the URL http://localhost:8000
- That's all, now you can modify the contents of the site by creating/editing markdown files on the `/content` folder.

#### Ultra-Fast Start

Using this other option is even faster, open a terminal, run the following one-liner command and you'll have a mosquito site running on the URL http://localhost:8000 in a matter of seconds.

```bash
mkdir mosquito && curl -L https://github.com/alterebro/mosquito/tarball/v0.1.0 | tar zx -C mosquito --strip-components=1 && cd mosquito && php -f index.php build && php -S localhost:8000 -t dist
```

The line above execute the following commands altogether:
```bash
# It creates the mosquito folder
$ mkdir mosquito
# Downloads the mosquito files and extracts them on the previous created folder
$ curl -L https://github.com/alterebro/mosquito/tarball/v0.1.0 | tar zx -C mosquito --strip-components=1
# It gets inside the created folder
$ cd mosquito
# Builds and generates the Static Site.
$ php -f index.php build
# Starts a web server on http://localhost:8000
$ php -S localhost:8000 -t dist
```


### Creating Content

The content you create has to be stored on the `/content` folder (you can change this on the settings), simply create a normal markdown file with a `.md` extension and mosquito will parse it and create the HTML page. Just remember when you create a subfolder, it has to have a `index.md` file within it.


### Configuration

The main configuration of mosquito is done on the `index.php` file located on the root of the project folder. Open the file and you'll see at the top of the code an array named `$_CONFIG`, that stores the main settings of the mosquito app. There you'll be able to change according to your needs.

---

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

---
