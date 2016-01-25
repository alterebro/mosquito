<?php

function navigation($path, $max_depth = false, $level = 1) {

	global $_PATH;

	$dir_items = new DirectoryIterator($path);
	$dir_exclude = ['.', '..', '.DS_Store', '404.md', 'index.md'];
	$dir_item_ext = ( PHP_SAPI == "cli" ) ? '.html' : '';

	$dir_files = [];

	$i = iterator_count($dir_items);
	foreach ($dir_items as $dir_item) {
		if ( !in_array($dir_item, $dir_exclude) ) {

			// iterator grows...
			$i++;

			// Create a link
			$_link = str_replace( $_PATH['content'], '', $dir_item->getPathname() );
			$_link = $_PATH['url'] . str_replace('.md', '', $_link);
			$_link .= ( $dir_item->isDir() ) ? '/' : $dir_item_ext;

			// Create a default title based on the filename
			$_name = (substr($dir_item->getFilename(), -3) == '.md') ? str_replace( '.md', '', $dir_item->getFilename() ) : $dir_item->getFilename();
			$_name = ucfirst( str_replace('-', ' ', $_name) );

			// Get the path
			$_path = $dir_item->getPathname();

			// get the file to extract the content
			$_file = ( $dir_item->isDir() )
				? $dir_item->getPathname() . '/index.md'
				: $dir_item->getPathname();

			// replace Title with metadata.title
			$_file_contents = extract_content($_file);
			$_name = ( !empty($_file_contents['metadata']['title']) ) ? $_file_contents['metadata']['title'] : $_name;

			// Get metadata order
			$_order = ( isset($_file_contents['metadata']['order']) && is_numeric($_file_contents['metadata']['order']) ) ? $_file_contents['metadata']['order'] : $i;


			// Populate the array
			$dir_files[$_order] = [
				'name' => $_name,
				'path' => $_path,
				'file' => $_file,
				'link' => $_link,
			];
		}
	}

	// Array sort by key
	ksort($dir_files);

	// Create the HTML output
	$output_html = '<ul>';
	foreach ($dir_files as $dir_file) {
		$output_html .= '<li>';
		$output_html .= '<a href="'.$dir_file['link'].'">' . $dir_file['name'] . '</a>';
		// Recursive Looping...
		if ( is_dir($dir_file['path'])  ) {
			$level++;
			if ( empty($max_depth) || ($level <= $max_depth) ) {
				$output_html .= navigation( $dir_file['path'], $max_depth, $level);
			}
			$level--;
		}
		$output_html .= '</li>';
	}
	$output_html .= '</ul>';

	// Return the created HTML
	return $output_html;
}

$_DATA['menu_global'] = navigation($_PATH['content']);


/*
// TODO : _breadcrumbs
// -------------------
// {{ breadcrumbs }}
// -------------------
$breadcrumbs = array(
    '<a href="'.$_PATH['path'].'" title="Home">Home</a>',
);
$breadcrumb_path = [];
$bread_crumbs = explode('/', $_QUERY);
foreach ($bread_crumbs as $crumb) {
    $breadcrumb_path[] = $crumb;

    $bc_name = format_filename($crumb);
    $bc_link = $_PATH['path'] . implode('/', $breadcrumb_path);
    $breadcrumbs[] = '<a href="'.$bc_link.'" title="'.$bc_name.'">'.$bc_name.'</a>';

}
$_DATA['breadcrumbs'] = implode(' / ', $breadcrumbs);
*/
