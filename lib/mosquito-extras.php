<?php
// --------------------------------------
// Menu Global : {= menu_global|raw}
// --
// Create new menus :
// $_DATA['your_menu'] = navigation(index_name:string, folder_path:string, maximum_depth:int);
// Will be available as : {= your_menu|raw}
// --------------------------------------
function navigation($path, $root_item = false, $max_depth = false, $level = 1) {

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
			// TODO : Check for collisions
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
	// Add first 'home' item
	$output_html .= ( $level == 1 && $root_item) ? '<li><a href="'.$_PATH['url'].'">'.$root_item.'</a></li>' : '';
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

$_DATA['menu_global'] = ( $_DATA['site']['use_menu_global'] )
	? navigation($_PATH['content'], $_CONFIG['title'])
	: ' {{Error : set config variable "use_menu_global" to true}} ';


// --------------------------------------
// breadcrumbs : {= breadcrumbs|raw}
// --------------------------------------
function breadcrumbs($query) {
	global $_PATH;
	global $_CONFIG;

	$_q = explode('/', $query);
	$_c = []; 		// Helper array
	$_crumbs = [ 	// Where the items will be stored. started w/home link
		'<a href="'.$_PATH['url'].'">'. $_CONFIG['title'] .'</a>'
	];
	foreach ($_q as $_i) {

		if ( !empty($_i) ) {

			$_c[] = $_i;

			// Get the file where the title is
			$_ci = $_PATH['content'] . implode('/', $_c);
			$_ci .= ( is_dir($_ci) ) ? '/index.md'  : '.md';

			// Get the title
			$_ci_contents = extract_content($_ci);
			$_ci_name = ucfirst( str_replace('-', ' ', $_i) ); // Default based on filename
			$_ci_name = ( !empty($_ci_contents['metadata']['title']) ) ? $_ci_contents['metadata']['title'] : $_ci_name;
			unset ($_ci_contents);

			// Create the link
			$_ci_link = str_replace( $_PATH['content'], '', $_ci );
			$_ci_link = $_PATH['url'] . str_replace('.md', '', $_ci_link);
			if ( substr($_ci_link, -5) == 'index' ) { $_ci_link = substr($_ci_link, 0, -5); }
			else { $_ci_link .= ( PHP_SAPI == "cli" ) ? '.html' : ''; }

			// Add it
			$_crumbs[] = '<a href="'.$_ci_link.'">'. $_ci_name .'</a>';
		}
	}
	return $_crumbs;
}

$_DATA['breadcrumbs'] = ( $_DATA['site']['use_breadcrumbs'] )
	? implode(' / ', breadcrumbs($_QUERY))
	: ' {{Error : set config variable "use_breadcrumbs" to true}} ';
