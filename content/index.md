## Mosquito

**Static Site Generator and Content Management System**

*Mosquito* is a ultra Lightweight flat file CMS that compiles Markdown into HTML.
Mosquito is also a Static Site Generator wannabe' (that part is still in progress)

### Customization

The content directory, file type and some defaults can be changed on the `CONFIG` array located within the main file `ìndex.php`

```php
$_CONFIG = array(
    'title' => 'mosquito',
	'description' => 'Mosquito is a CMS + SSG built with PHP',
	'keywords' => 'mosquito, CMS, SSG, PHP, Markdown, static, site, generator, content, system',
	'language' => 'en',
	'author' => 'Jorge Moreno aka Moro ( moro.es, @alterebro )',

    'theme' => 'default', // should be located on the /theme folder
    'content_folder' => 'content/',
	'file_extension' => '.md',
	'minify_output' => false,

    'dist_url' => 'http://localhost/dist/',
    'dist_folder' => 'dist/',
    'dist_extension' => '.html',
);

```

### URLs

Using *mosquito* as CMS :

- A file at `content/index.md` will be accessed at `/`.  
- A file at `content/text.md` will be accessed at `/text`.  
- A file at `content/sub/index.md` will be accessed at `/sub/`.  
- A file at `content/sub/text.md` will be accessed at `/sub/text`.  
- If a file does not exist or cannot be found, `content/404.md` will be used in its place.

If you are using *mosquito* as Static Site Generator :

- A file at `content/index.md` will be accessed at `/`.  
- A file at `content/text.md` will be accessed at `/text.html`.  
- A file at `content/sub/index.md` will be accessed at `/sub/`.  
- A file at `content/sub/text.md` will be accessed at `/sub/text.html`.  
- If a file does not exist or cannot be found, should be directed to `/404.html` which is created from the file `content/404.md`.


### Credits

Copyright (c) 2016 Jorge Moreno ( [@alterebro](https://twitter.com/alterebro) )
