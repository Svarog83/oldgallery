<?php

if (!defined('ZENFOLDER')) { define('ZENFOLDER', 'zp-core'); }
require_once(ZENFOLDER . "/template-functions.php");

$albumnr 	 = sanitize_numeric( $_GET['albumnr'] );
$albumname   = Translit ( sanitize( $_GET['albumname'], true ) );
$host 		 = htmlentities( $_SERVER["HTTP_HOST"], ENT_QUOTES, 'UTF-8' );


db_connect();

if (is_numeric($albumnr) && $albumnr != "") { 
	$albumWhere = "images.albumid = $albumnr AND";
} else {
	$albumWhere = "";
}

$query = 
"	
	SELECT 
albums.folder as folder
#images.albumid, 
#images.date AS date, 
#images.filename AS filename, 
#images.title AS title, 
#albums.folder AS folder, 
#albums.title AS albumtitle, 
#images.show, albums.show, 
#albums.password 
	FROM 
" . prefix('images') . " AS images, 
" . prefix('albums') . " AS albums 
	WHERE 
".$albumWhere." 
images.albumid = albums.id && 
images.show=1 && 
albums.show=1 && 
albums.folder != ''
	ORDER BY 
albums.folder
	LIMIT 1;
";

$result = query_full_array( $query );

$folder = $result[0]['folder'];

if ( $folder )
{
	require_once( './zip/pclzip.lib.php' );
	$filename = "./cache/$albumname.zip";
	
	$zip = new PclZip( $filename );
	
	$need_update = false;
	if ( !file_exists( $filename ) )
		$need_update = true;
	else 
	{
		$arr = $zip->listContent();
		if ( count ( $arr ) != count ( $result ) )
			$need_update = true;
	}
	
	if ( $need_update )
	{
		$zip->create( '.' . $_zp_conf_vars['album_folder'] . $folder . '/',
																		 PCLZIP_OPT_REMOVE_PATH, 'albums',
																		 PCLZIP_OPT_NO_COMPRESSION );
	}
	
	SendFile( './cache/', $albumname . '.zip' );
}

