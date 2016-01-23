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
		$_d = $objects->getDepth();

		if (
			(!$max_depth || ( !empty($max_depth) && $_d <= ($max_depth-1) )) &&
			!in_array($object->getFilename(), $exclude)
		) {

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
	return $dom->saveHtml();
}
$_DATA['menu_global'] = navigation($_PATH['content'], false);


function nav() {
	global $_PATH;
	$nav_iterator = new RecursiveIteratorIterator(
	    new RecursiveDirectoryIterator($_PATH['content'], RecursiveDirectoryIterator::SKIP_DOTS),
	    RecursiveIteratorIterator::SELF_FIRST,
	    RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
	);

	$exclude = ['.DS_Store', '404.md', 'index.md'];
	$ext = ( PHP_SAPI == "cli" ) ? '.html' : '';
	$output = ['<ul>'];
	$depth = 1;
	foreach ($nav_iterator as $nav_item) {
		$_p = str_replace( $_PATH['content'], '', $nav_item->getPathname() );
		$_l = $_PATH['url'] . str_replace('.md', '', $_p) . $ext;
		$_l = str_replace(array('index.html', 'index'), '', $_l);

//		$_d = explode('/', $_p);
//		$depth = count($_d);

		if ( $nav_item->isFile() && !in_array($_p, $exclude) && (substr($_p, -3) == '.md') ) {
			$o_item = '<li>';
			$o_item .= '<a href="'. $_l .'">';
			$o_item .= $_p . $ext;
			$o_item .= '</a>';
			$o_item .= '</li>';
			$output[] = $o_item;

		}

	}
	$output[] = '</ul>';
	return implode('', $output);
}
$_DATA['menu_local'] = nav();



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
