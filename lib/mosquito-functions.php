<?php
use \Michelf\MarkdownExtra;

function extract_content($src) {

	// Empty by default
	$_output = array(
		'metadata' => '',
		'content' => '',
	);

	// Exit if no file
	if ( !file_exists($src) ) { return $_output; }

	// ---
	// Get data if file exists
	$contents = file_get_contents( $src );
	$metadata = [
        'timestamp' => filemtime($src),
    ];
	if ( preg_match("/<!--(.*)-->/s", $contents, $meta) ) {
		$meta = trim($meta[1]);
		$meta = explode(PHP_EOL, $meta);
		foreach ($meta as $item) {
			$_md = explode(':', $item);
			$metadata[trim($_md[0])] = trim(implode(':', array_slice($_md, 1)));
		}
	}

	$contents = preg_replace('/<!--(.*)-->/Uis', '', $contents);
	$MarkdownParser = new MarkdownExtra;
	$content = $MarkdownParser->transform($contents);

	unset($contents);

	// Set and return data
	$_output['metadata'] = $metadata;
	$_output['content'] = $content;
	return $_output;
}

// http://www.php.net/manual/en/function.realpath.php#84012
function get_absolute_path($path) {
	$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
	$parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
	$absolutes = array();
	foreach ($parts as $part) {
		if ('.' == $part) continue;
		if ('..' == $part) {
			array_pop($absolutes);
		} else {
			$absolutes[] = $part;
		}
	}
	return implode(DIRECTORY_SEPARATOR, $absolutes);
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

function recursive_remove_dir($directory) {
    foreach( glob("{$directory}/*") as $file) {
        if(is_dir($file)) {
            recursive_remove_dir($file);
        } else {
            unlink($file);
        }
    }
	@rmdir($directory);
}

function build($data) {

	print "\n";
    print " ----------- ";
    print "\n";
    print ' - mosquito msg : init building ' . "\n";

	$target_folder = $data['path']['root'] . $data['site']['dist_folder'];

	// If already exists, remove all to create a clean generated site.
	if ( is_dir($target_folder) ) {
		print ' - Removing existing content on distributable folder' . "\n";
		recursive_remove_dir($target_folder);
		print ' - Existing content on distributable folder erased from existence' . "\n";
	}

	// Create the distributable folder
	if ( !is_dir($target_folder) ) {
		print ' - Creating distributable folder content...' . "\n";
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

    $the_md = $data['metadata'];
    // The Loop
    foreach ($_files as $render_file) {

    	$c = extract_content( $render_file['source'] );
        $data['metadata'] = array_merge($the_md, $c['metadata']);
    	$data['content'] = $c['content'];

		// Construct Query if needed for breadcrumbs
		if ($data['site']['use_breadcrumbs']) {
			$_q = str_replace($data['path']['content'], '', $render_file['source']);
			$_q = str_replace('.md', '', $_q);
			$_q = str_replace('/index', '', $_q);
			$_q = ( $_q == 'index' ) ? '' : $_q;
			$data['breadcrumbs'] = implode(' / ', breadcrumbs($_q));
		}

        $output = render_template(
        	'theme/' . $data['site']['theme'] . '/' . $data['metadata']['layout'] . '.html',
        	$data,
        	$data['site']['minify_output'],
            false
        );
        file_put_contents($render_file['target'], $output);

        print ' - File created : ' . $render_file['target'] . "\n";
    }

    print ' - mosquito msg : site built! ' . "\n";
    print " ----------- " . "\n";
	print ' - Your site can now be reached at : ' . $data['site']['dist_url']  . "\n";
	print " ----------- " . "\n";
    print "\n";
}
