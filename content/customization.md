<!--
order : 3
image : https://pbs.twimg.com/profile_images/378800000532546226/dbe5f0727b69487016ffd67a6689e75a.jpeg
-->

## Customization

The content directory, file type and some defaults can be changed on the `CONFIG` array located within the main file `ìndex.php`

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
