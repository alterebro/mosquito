<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('UTC');
require_once 'lib/MarkdownExtra.inc.php';
require_once 'lib/div.php';
require_once 'lib/mosquito-functions.php';

$_CONFIG = array(
    'title' => 'mosquito',
	'description' => 'Mini flyweight Static Site Generator written in PHP that can also be used as simple CMS, compiles Markdown files into HTML using the given folder structure as URL router model.',
	'keywords' => 'mosquito, CMS, SSG, PHP, Markdown, static, site, generator, content, system',
	'language' => 'en',
	'author' => 'Jorge Moreno aka Moro ( moro.es, @alterebro )',

    'theme' => 'mosquito', // should be located on the /theme folder
    'content_folder' => 'content/',
	'file_extension' => '.md',
	'minify_output' => true,

    'dist_url' => 'http://localhost:8000/',
    // 'dist_url' => 'http://alterebro.github.io/mosquito/',
    'dist_folder' => 'dist/',
    'dist_extension' => '.html',

    'use_menu_global' => true,
    'use_breadcrumbs' => false,
);

$_PATH = array(
	'path' => (PHP_SAPI == "cli")
        ? $_CONFIG['dist_url']
        : dirname($_SERVER['PHP_SELF']) . ((dirname($_SERVER['PHP_SELF']) == '/') ? '' : '/'),
	'url' => (PHP_SAPI == "cli")
        ? $_CONFIG['dist_url']
        : 'http://' . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['PHP_SELF'] ) . ((dirname($_SERVER['PHP_SELF']) == '/') ? '' : '/'),
	// 'root' => realpath(dirname(__FILE__)) . '/',
    'root' => DIRECTORY_SEPARATOR . get_absolute_path(dirname(__FILE__)) . DIRECTORY_SEPARATOR,
	// 'content' => realpath(dirname(__FILE__)) . '/' . $_CONFIG['content_folder'],
    'content' => DIRECTORY_SEPARATOR . get_absolute_path(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $_CONFIG['content_folder'],
);

$_DATA = array(
	'site' => $_CONFIG,
	'path' => $_PATH,
	'file' => $_PATH['content'].'index'.$_CONFIG['file_extension'],
	'content' => '',
	'is_404' => false,
	'metadata' => [
		'title' => false,
		'description' => false,
		'image' => false,
        'tags' => false,
		'layout' => 'index',
		'timestamp' => false, 	// auto-generated
		'url' => false,			// auto-generated
	],

	// Inject here below your own data to be available on the template views
	// 'my_var' => 'default my_var value', // accesible on {{ my_var }}
	// 'my_var_02' => 'default my_var_02 value', // accesible on {{ my_var_02 }}
);
$_default_metadata = $_DATA['metadata'];

	// Get the Query URL Parameter.
	$_QUERY = (!empty($_GET['q'])) ? $_GET['q'] : '';
	$_QUERY = trim( $_QUERY, '/' );

	// _file
	// -------------------
	$_DATA['file'] = ( is_dir($_PATH['content'].$_QUERY) )
		? '/' . trim( $_PATH['content'].$_QUERY, '/' ) . '/index' . $_CONFIG['file_extension']
		: '/' . trim( $_PATH['content'], '/') . '/' . $_QUERY . $_CONFIG['file_extension'];

	// _is_404 ? is_homepage?
	// -------------------
	$_DATA['is_404'] = ( file_exists($_DATA['file']) ) ? false : true;
    $_DATA['is_homepage'] = !$_QUERY;

    // _extract_content
	// -------------------
    $file_to_extract = ( file_exists($_DATA['file']) ) ? $_DATA['file'] : $_PATH['content'] .'404'. $_CONFIG['file_extension'];
    $c = extract_content( $file_to_extract );
	$_DATA['metadata'] = array_merge($_default_metadata, $c['metadata']);
    $_DATA['metadata']['url'] = $_PATH['url'] . $_QUERY;
	$_DATA['content'] = $c['content'];

// _render
// -------------------
function render_template($template, $data, $minify = false, $render = true) {

    $output = new div($template, $data);

    if ( $minify ) {

        $output = preg_replace('/(?>[^\S ]\s*|\s{2,})(?=(?:(?:[^<]++|<(?!\/?(?:textarea|pre)\b))*+)
        (?:<(?>textarea|pre)\b|\z))/ix', '', $output );
    }

    if ( !$render ) { return $output; }
    if ( $data['is_404'] ) { header("HTTP/1.0 404 Not Found"); }
    echo $output;
}

// _load extras
// -------------------
require_once 'lib/mosquito-extras.php';


// _OUTPUT
// -------------------
if (PHP_SAPI == "cli") { // php_sapi_name();

    // CLI OUTPUT
    // -------------------
    if ( !empty($argv[1]) && $argv[1] == 'build' ) {

        $_DATA['metadata'] = $_default_metadata;
        build($_DATA);

    } else {

        print "use argument 'build' to generate the static site.\n";
    }

} else {

    // Frontend OUTPUT
    // -------------------
    render_template(
    	'theme/' . $_CONFIG['theme'] . '/' . $_DATA['metadata']['layout'] . '.html',
    	$_DATA,
    	$_CONFIG['minify_output']
    );
}
