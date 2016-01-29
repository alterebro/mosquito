<!--
order : 3
-->

## Customization

The content directory, file type and some defaults can be changed on the `CONFIG` array located within the main file `ìndex.php`. Most of these key-value pairs ar self explanatory :

```php
$_CONFIG = array(
    'title' => 'mosquito',
	'description' => 'Mosquito is a CMS + SSG built with PHP',
	'keywords' => 'mosquito, CMS, SSG, PHP, Markdown, static, site, generator, content, system',
	'language' => 'en',
	'author' => 'Jorge Moreno aka Moro ( moro.es, @alterebro )',

    'theme' => 'mosquito', // should be located on the /theme folder
    'content_folder' => 'content/',
	'file_extension' => '.md',
	'minify_output' => true,

    'dist_url' => 'http://localhost:8000/',
    'dist_folder' => 'dist/',
    'dist_extension' => '.html',

    'use_menu_global' => true,
    'use_breadcrumbs' => true,
);
```

You should take extra attention to the key value called `dist_url` as that one will define the final URL where your project will be hosted, when you are using mosquito on your local machine with the PHP built in server, the value `http://localhost:8000/` will be alright, but once you are ready to deploy on a remote server you should change that for the remote URL, if your website will be reached at `http://myamazingstaticwebsite.com` , that has to be the value.
