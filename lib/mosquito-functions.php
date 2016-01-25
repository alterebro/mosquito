<?php
function extract_content($src) {

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

	// TODO : switch to PHP Markdown Extra? (https://github.com/michelf/php-markdown)
	$Parsedown = new Parsedown();
	$contents = preg_replace('/<!--(.*)-->/Uis', '', $contents);
	$content = $Parsedown->text($contents);

	unset($contents);
	return array(
		'metadata' => $metadata,
		'content' => $content,
	);
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

    $the_md = $data['metadata'];
    // The Loop
    foreach ($_files as $render_file) {

    	$c = extract_content( $render_file['source'] );
        $data['metadata'] = array_merge($the_md, $c['metadata']);
    	$data['content'] = $c['content'];

        $output = render_template(
        	'theme/' . $data['site']['theme'] . '/' . $data['metadata']['layout'] . '.html',
        	$data,
        	$data['site']['minify_output'],
            false
        );

        /*
        ob_start();
            $_DATA = $data; // weird...
            eval("?> $output <?php ");
            $out_put = ob_get_contents();
        ob_end_clean();
        file_put_contents($render_file['target'], $out_put);
        */
        file_put_contents($render_file['target'], $output);

        print ' - File created : ' . $render_file['target'] . "\n";
    }

    print ' - mosquito msg : site built! ' . "\n";
    print " ----------- ";
    print "\n";

}
