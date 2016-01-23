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

	$Parsedown = new Parsedown();
	$contents = preg_replace('/<!--(.*)-->/Uis', '', $contents);
	$content = $Parsedown->text($contents);

	unset($contents);
	return array(
		'metadata' => $metadata,
		'content' => $content,
	);
}
