<?
$_file = 'sub1';
	
if( isset($_GET['partner-country']) )
	$_file = 'sub2';

include __DIR__ . '/partners.' . $_file . '.php';