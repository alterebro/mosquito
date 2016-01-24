<?php

function navigation( $path, $max_depth = false ) {
	// http://stackoverflow.com/questions/10779546/recursiveiteratoriterator-and-recursivedirectoryiterator-to-nested-html-lists/10780023#10780023
	global $_PATH;

	$objects = new RecursiveIteratorIterator(
	    new RecursiveDirectoryIterator(
			$path,
			RecursiveDirectoryIterator::SKIP_DOTS
		),
		RecursiveIteratorIterator::SELF_FIRST,
	    RecursiveIteratorIterator::CATCH_GET_CHILD
	);
	$exclude = ['.DS_Store', '404.md', 'index.md'];
	$file_ext = ( PHP_SAPI == "cli" ) ? '.html' : '';

	$dom = new DomDocument("1.0");
	$list = $dom->createElement("ul");
	$dom->appendChild($list);
	$node = $list;

	$depth = 0;
	foreach($objects as $name => $object) {

		$_l = str_replace( $_PATH['content'], '', $object->getPathname() );
		$_l = $_PATH['url'] . str_replace('.md', '', $_l);
		$_l .= ( $object->isDir() ) ? '/' : $file_ext;
		$_n = (substr($object->getFilename(), -3) == '.md') ? str_replace( '.md', '', $object->getFilename() ) : $object->getFilename();
		$_n = ucfirst( str_replace('-', ' ', $_n) );
		$_d = $objects->getDepth();

		if (
			(!$max_depth || ( !empty($max_depth) && $_d <= ($max_depth-1) )) &&
			!in_array($object->getFilename(), $exclude)
		) {

			// Replace title with metadata.title
			$_pn = ( $object->isDir() )
				? $object->getPathname() . '/index.md'
				: $object->getPathname();
			$_c = extract_content($_pn);
			$_n = ( !empty($_c['metadata']['title']) ) ? $_c['metadata']['title'] : $_n;

			// Link item
			$a = $dom->createElement( 'a', $_n );
			$a->setAttribute('href', $_l);

		    if ($objects->getDepth() == $depth){

				// depth hasnt changed : add another li
		        $li = $dom->createElement( 'li' );
				$li->appendChild( $a );
		        $node->appendChild( $li );

			} elseif ($objects->getDepth() > $depth){

				// depth increased : the last li is a non-empty folder
		        $li = $node->lastChild;
		        $ul = $dom->createElement( 'ul' );
		        $li->appendChild( $ul );
				$_li = $dom->createElement( 'li' );
				$_li->appendChild( $a );
		        $ul->appendChild( $_li );
		        $node = $ul;

		    } else {

				// depth decreased : going up $difference directories
		        $difference = $depth - $objects->getDepth();
		        for( $i = 0; $i < $difference; $difference-- ) {
		            $node = $node->parentNode->parentNode;
		        }
		        $li = $dom->createElement( 'li' );
				$li->appendChild( $a );
		        $node->appendChild( $li );
		    }

			$depth = $objects->getDepth();
		}
	}

	// $m_id = str_replace('/', '-', trim( str_replace( $_PATH['root'], '', $path ), '/'));
	return $dom->saveHtml();
}

$_DATA['menu_global'] = navigation($_PATH['content'], false);


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
