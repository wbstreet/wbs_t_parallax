<?php
/*
 * @changed date 14-02-2017
 */

/*

/media/bg/{PAGE_ID}.jpg - фон страницы
/media/bg/p{PAGE_ID}.jpg - фон дочерних страниц
/media/prlx/{PAGE_ID}-{SIDE}.jpg - элемент с параллаксом (SIDE - l или r)
*/

$template_directory				= 'wbs_t_parallax';
$template_name						= 'Parallax';
$template_function			= 'template';
$template_version					= '2.10.0';
$template_platform				= '2.7';
$template_author					= 'Company';
$template_license					= '<a href="http://www.gnu.org/licenses/gpl.html">GNU General Public License</a>';
$template_license_terms		= '-';
$template_description			= 'This template is for use on page where you do not want anything wrapping the content.';

$menu[1] =	'Main';
$menu[2] =	'Left';
$menu[3] =	'Right';

$block[1]='Left';
$block[2]='Center'; 
$block[3]='Right'; 

?>