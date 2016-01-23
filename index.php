<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('UTC');
require_once 'lib/parsedown.php';
require_once 'lib/mosquito-functions.php';

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

    'dist_url' => 'http://localhost:8000/',
    'dist_folder' => 'dist/',
    'dist_extension' => '.html',
);

$_PATH = array(
	'path' => (PHP_SAPI == "cli")
        ? $_CONFIG['dist_url']
        : dirname($_SERVER['PHP_SELF']) . ((dirname($_SERVER['PHP_SELF']) == '/') ? '' : '/'),
	'url' => (PHP_SAPI == "cli")
        ? $_CONFIG['dist_url']
        : 'http://' . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['PHP_SELF'] ) . ((dirname($_SERVER['PHP_SELF']) == '/') ? '' : '/'),
	'root' => realpath(dirname(__FILE__)) . '/',
	'content' => realpath(dirname(__FILE__)) . '/' . $_CONFIG['content_folder'],
);


$_DATA = array(
	'site' => $_CONFIG,
	'path' => $_PATH,
	'file' => $_PATH['content'].'index'.$_CONFIG['file_extension'],
	'content' => '',
	'is_404' => false,
	'metadata' => [
		'title' => $_CONFIG['title'],
		'description' => $_CONFIG['description'],
		'image' => false,
		'layout' => 'index',
		'timestamp' => false, 	// auto-generated
		'url' => false,			// auto-generated
	],

	// Inject here below your own data to be available on the template views
	// 'my_var' => 'default my_var value', // accesible on {{ my_var }}
	// 'my_var_02' => 'default my_var_02 value', // accesible on {{ my_var_02 }}
);

	// Get the Query URL Parameter.
	$_QUERY = (!empty($_GET['q'])) ? $_GET['q'] : '';
	$_QUERY = trim( $_QUERY, '/' );

	// _file
	// -------------------
	$_DATA['file'] = ( is_dir($_PATH['content'].$_QUERY) )
		? '/' . trim( $_PATH['content'].$_QUERY, '/' ) . '/index' . $_CONFIG['file_extension']
		: '/' . trim( $_PATH['content'], '/') . '/' . $_QUERY . $_CONFIG['file_extension'];

	// _is_404
	// -------------------
	$_DATA['is_404'] = ( file_exists($_DATA['file']) ) ? false : true;

    // _extract_content
	// -------------------
    $file_to_extract = ( file_exists($_DATA['file']) ) ? $_DATA['file'] : $_PATH['content'] .'404'. $_CONFIG['file_extension'];
    $c = extract_content( $file_to_extract );
	$_DATA['metadata'] = array_merge($_DATA['metadata'], $c['metadata']);
    $_DATA['metadata']['url'] = $_PATH['url'] . $_QUERY;
	$_DATA['content'] = $c['content'];


// _render
// -------------------
function clean_str($str) {
    return iconv("UTF-8", "UTF-8//IGNORE", $str );
    // return $str;
}
function render_template($template, $data, $minify = false, $eval = true) {
	$_DATA = $data;
	$template = file_get_contents($template);
	$template = preg_replace_callback('/{{ (.*?) }}/',
		function($m) {
			$_m = explode('.', $m[1]);
			return ( count($_m) >1 )
				? '<?php echo (!empty($_DATA[\''.$_m[0].'\'][\''.$_m[1].'\'])) ? $_DATA[\''.$_m[0].'\'][\''.$_m[1].'\'] : ""; ?>'
				: '<?php echo (!empty($_DATA[\''.$_m[0].'\'])) ? $_DATA[\''.$_m[0].'\'] : ""; ?>';
		}, $template);

	if ( $minify ) {

		$template = preg_replace(
			array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'),
			array('>', '<', '\\1', ' ', ''),
			$template
		);
	}

    if (!$eval) { return $template; }

	if ( $_DATA['is_404'] ) { header("HTTP/1.0 404 Not Found"); }
	eval("?> $template <?php ");
}


// _BUILD
// -------------------
function recurse_copy($src,$dst, $exclude = array()) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )  && ( $file != '.DS_Store' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file, $exclude);
            }
            else {
                if ( !in_array(pathinfo($src . '/' . $file, PATHINFO_EXTENSION), $exclude) ) {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
    }
    closedir($dir);
}

function build($data) {

    print " ----------- ";
    print "\n";
    print ' - mosquito msg : init building ' . "\n";

    // TODO : remove first everything on/and the destination folder
	$target_folder = $data['path']['root'] . $data['site']['dist_folder'];
	if (!is_dir($target_folder)) {
		mkdir($target_folder, 0755, true);
	}

    // Copy theme and media folders
    recurse_copy( $data['path']['root'] . 'theme', $target_folder . 'theme', ['DS_store', 'html', 'less'] );
    recurse_copy( $data['path']['root'] . 'media', $target_folder . 'media' );

	$content_iterator = new RecursiveIteratorIterator(
	    new RecursiveDirectoryIterator($data['path']['content'], RecursiveDirectoryIterator::SKIP_DOTS),
	    RecursiveIteratorIterator::SELF_FIRST,
	    RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
	);
	$_files = [];
	foreach ($content_iterator as $content_item) {
		// Files
		if (
			$content_item->isFile() &&
			($content_item->getBasename('.md') != $content_item->getFilename())
		) {
			$_files[] = [
				'source' => $content_item->getPathname(),
				'target' =>
					dirname(str_replace($data['site']['content_folder'], $data['site']['dist_folder'], $content_item->getPathname()))
					. DIRECTORY_SEPARATOR
					. $content_item->getBasename('.md')
					. $data['site']['dist_extension']
			];
		}

		// Folders
		if ( $content_item->isDir() ) {
			$dest_dir = str_replace($data['site']['content_folder'], $data['site']['dist_folder'], $content_item) . DIRECTORY_SEPARATOR;
			if ( !is_dir( $dest_dir ) ) {
				mkdir($dest_dir, 0755, true);
			}
		}
	}

    // The Loop
    foreach ($_files as $render_file) {

    	$c = extract_content( $render_file['source'] );
    	$data['metadata'] = array_merge($data['metadata'], $c['metadata']);
    	$data['content'] = $c['content'];

        $output = render_template(
        	'theme/' . $data['site']['theme'] . '/' . $data['metadata']['layout'] . '.html',
        	$data,
        	$data['site']['minify_output'],
            false
        );

        ob_start();
            $_DATA = $data; // weird...
            eval("?> $output <?php ");
            $out_put = ob_get_contents();

            // todo : improve this
            /*
            $out_put = preg_replace(
                "/<a href=\"(\.\/.*)\">(.*)<\/a>/iU",
                "<a href=\"$1.html\">$2</a>",
                $out_put
            );
            */

        ob_end_clean();
        file_put_contents($render_file['target'], $out_put);

        print ' - File created : ' . $render_file['target'] . "\n";
    }

    print ' - mosquito msg : site built! ' . "\n";
    print " ----------- ";
    print "\n";

}


require_once 'lib/mosquito-extras.php';


// _OUTPUT
// -------------------

    if (PHP_SAPI == "cli") { // php_sapi_name();

        // CLI OUTPUT
        // -------------------
        build($_DATA);


    } else {

        // Frontend OUTPUT
        // -------------------
        render_template(
        	'theme/' . $_CONFIG['theme'] . '/' . $_DATA['metadata']['layout'] . '.html',
        	$_DATA,
        	$_CONFIG['minify_output']
        );
    }
