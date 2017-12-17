<?php if(!defined('WB_URL')) { header('Location: ../index.php'); 	exit(0); }	

include("config_template.php");

function custom_menu($menu_num) {
    ob_start(); 
    $open = '<li data-id=[page_id] class="[if(class=menu-current||class=menu-parent){active}] [if(class==menu-expand){dropdown}]">
    	[if(class==menu-expand){<a href="[url]" class="dropdown-toggle" data-toggle="dropdown">[menu_title] <b class="caret"></b></a>}else {<a href="[url]#gotopid[page_id]">[menu_title]</a>}]';
    	show_menu2(
    		$aMenu          = $menu_num,
    		$aStart         = SM2_ROOT,
    		$aMaxLevel      = SM2_CURR+3,
    		$aOptions       = SM2_ALL,
    		$aItemOpen      = $open,
    		$aItemClose     = "</li>" /*[if(level!=0){<br>}]*/,
    		$aMenuOpen      = '<ul class="[if(level==0){nav navbar-nav} else {dropdown-menu}] [if(level==1){teaser-menu}] [if(level==2){teaser-menu2}]">',
    		$aMenuClose     = '</ul>',
    		$aTopItemOpen   = false,
    		$aTopMenuOpen   = false
    );
    $topnav = ob_get_contents();
    //$topnav = str_replace('menu-current','active',$topnav);
    ob_end_clean();
    return $topnav;
}
?><!DOCTYPE html>
<html lang="ru"><head>
    <?php
    if(function_exists('simplepagehead')) {
    	simplepagehead(); 
    } else { ?>
    <title><?php page_title(); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php if(defined('DEFAULT_CHARSET')) { echo DEFAULT_CHARSET; } else { echo 'utf-8'; }?>" />
    <meta name="description" content="<?php page_description(); ?>" />
    <meta name="keywords" content="<?php page_keywords(); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <?php }
    if(function_exists('register_frontend_modfiles')) {
    	register_frontend_modfiles('css');
    	register_frontend_modfiles('jquery');
    	register_frontend_modfiles('js');
    } ?>

	<meta charset="UTF-8" />
	<meta name="robots" content="noindex, nofollow">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<title><?php page_title(); ?></title>
    <link href="<?php echo TEMPLATE_DIR; ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo TEMPLATE_DIR; ?>/editor.css" rel="stylesheet">
    <link href="<?php echo TEMPLATE_DIR; ?>/template.css" rel="stylesheet">
    <link href="<?php echo TEMPLATE_DIR; ?>/mobile.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/css/default.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/css/component.css" />
	<script src="<?php echo TEMPLATE_DIR; ?>/js/modernizr.custom.js"></script>
        <?php if(function_exists('wbs_core_include')) wbs_core_include(['functions.js', 'windows.js', 'windows.css']); ?>
	<?php require_once(WB_PATH.'/include/captcha/captcha.php'); ?>
</head><body>
    <div id='sheet' style='background:#d6b073;width:100%;height:100%;position:absolute;top:0;left:0;z-index:100000;text-align:center;'>
        <span style='display:inline-block;font-size:15pt;margin-top:10%;'>Загрузка сайта...</span>
        <!--<img src="<?=WB_URL.MEDIA_DIRECTORY?>/img/logo.png" style='height: 105px;margin-top: 8px;margin-right:10px;'>-->
    </div>

    <style>
    	/* Стили макета */
        section {
            <?php if (PARENT == 0) echo "overflow: hidden;"; /* для главной страницы - убираем скроллинг */ ?>
        }
        <?php if (PARENT != 0) { ?>
        body {
        background-image: url(<?=WB_URL.MEDIA_DIRECTORY?>/bg/p<?php echo PARENT; ?>.jpg);
        }
        <?php } ?>
    </style>
	
	<div class="container">

        <div class='navigation'>
            <div class='menu1'>
            	<?php echo custom_menu(2); ?>
            </div>
    
            <div class='logo' style='/*background:red;border-radius:20px;height:100px;width:100px;*/'>
                <a href="<?php echo WB_URL; ?>/" style="display: inline-block;"><img src="<?php echo WB_URL; ?>/media/img/logo.png" alt="<?php echo WEBSITE_TITLE; ?>" title="<?php echo WEBSITE_TITLE; ?>" style="width: 100%;"></a>
            </div>

            <div class='button_menu' onclick="$('.menu1, .menu3').animate({width:'toggle'}, 350)">
                <div></div>
                <div></div>
                <div></div>
            </div>
			
            <div class='menu3'>
            	<?php echo custom_menu(3); ?>
            </div>
        </div>

		<div id="cbp-fbscroller" class="cbp-fbscroller">
            <?php
            include(WB_PATH.'/framework/functions.php'); // для доступа к функции root_parent
            $content_from_menu = "1,2,3";
            
            if(PARENT==0) { // на главной странице показываются все страницы 0-го уровня
            	$sql = "SELECT * FROM ".TABLE_PREFIX."pages WHERE `parent` = '0' AND menu IN ($content_from_menu) AND visibility IN ('public', 'hidden') ORDER BY `position` ASC";
            } else { // на страницах 1-го уровня и выше показывается только данная страница
            	$sql = "SELECT * FROM ".TABLE_PREFIX."pages WHERE `page_id` = '".PAGE_ID."' AND visibility IN ('public', 'hidden')";
            }

        	$query_pages = $database->query($sql);            
            $is_error = $database->is_error();
            while(!$is_error && $query_page = $query_pages->fetchRow(MYSQLI_ASSOC)) {

                if (PARENT == 0) $bg_style = "background-image:url(".WB_URL."/".MEDIA_DIRECTORY."/bg/{$query_page['page_id']}.jpg)";
                else $bg_style = '';

                echo "<section id='gotopid{$query_page['page_id']}' style='{$bg_style}'><div>";

                // устанавливаем параметры для получения cодержимого данной страницы
        		$link = $query_page['link'];
        		$menu_title = $query_page['menu_title'];
        		$page_title = $query_page['page_title'];
        		$page_id = $query_page['page_id'];
        		$icon = $query_page['keywords'];
        		$wb->page_id = $page_id;

        		$prlx = MEDIA_DIRECTORY."/prlx/{$query_page['page_id']}-l.png";
                if (file_exists(WB_PATH.$prlx)) {echo "<div class='free_parallax' style='left:10px;'><img class='el-1' src='".WB_URL."$prlx'></div>";}

        		$prlx = MEDIA_DIRECTORY."/prlx/{$query_page['page_id']}-r.png";
                if (file_exists(WB_PATH.$prlx)) {echo "<div class='free_parallax' style='right:10px;'><img class='el-1' src='".WB_URL."$prlx'></div>";}

        		echo "<div id='content{$query_page['page_id']}_1' class='block_1'>";
                echo page_content(1);
        		echo "</div>";

        		echo "<div id='content{$query_page['page_id']}_3' class='block_3'>";
                echo page_content(3);
        		echo "</div>";

        		echo "<div id='content{$query_page['page_id']}_2' class='block_2'>";
                echo page_content(2);
        		echo "</div>";

                echo "</div></section>";
            }
            ?>
		</div>

	</div>

	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
	<!-- параллакс фона -->
	<script src="<?php echo TEMPLATE_DIR; ?>/js/jquery.easing.min.js"></script>
	<script src="<?php echo TEMPLATE_DIR; ?>/js/waypoints.min.js"></script>
	<script src="<?php echo TEMPLATE_DIR; ?>/js/jquery.debouncedresize.js"></script>
	<script src="<?php echo TEMPLATE_DIR; ?>/js/cbpFixedScrollLayout.js"></script>
    <!-- Выплывание содержимого -->
    <link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/css/swimming_animate.css" />
    <script src="<?php echo TEMPLATE_DIR; ?>/js/swimming_viewportchecker.js"></script>

	<script>
        /* параллакс фона */
		$(function() {
			cbpFixedScrollLayout.init({navlinks: $('.navigation a')});
		});

        /* Выплывание содержимого */

        window.addEventListener('load', function() {
        //jQuery(document).ready(function() {
            jQuery('.block_3').addClass("sw_hidden").viewportChecker({
                classToAdd: 'sw_visible animated bounceInRight',
                offset: 100
            });
            jQuery('.block_2').addClass("sw_hidden").viewportChecker({
                classToAdd: 'sw_visible animated fadeIn',
                offset: 100
            });
            jQuery('.block_1').addClass("sw_hidden").viewportChecker({
                classToAdd: 'sw_visible animated bounceInLeft',
                offset: 100
            });
        });

        /* Параллакс отдельных элементов */

        $(window).bind('scroll',function(e){
            parallaxScroll();
        });
         
        function parallaxScroll(){
            var scrolled = $(window).scrollTop();
            //$('.parallax-bg1').css('top',(0-(scrolled*.25))+'px');
            //$('.parallax-bg2').css('top',(0-(scrolled*.5))+'px');
            var els = $('.free_parallax');
            for (var i=0; i < els.length; i++) {
                x = 500 * i;//180 * i;
                $(els[i]).css('top',(x-(scrolled*.75))+'px');
                //$('.parallax-bg3').css('top',(x-(scrolled*.25))+'px');
            }
        }

    	window.addEventListener('load', function() {
           if (document.getElementById('sheet')) document.getElementById('sheet').remove();
       })

	</script>

	<div class='windowBody' id='feedback' >
        <img src="<?=WB_URL.MEDIA_DIRECTORY?>/img/close.png" style='position:absolute;width:32px;cursor:pointer;' onclick="W.close(this)">
        <br><br>
        <h1 style='color:#fb8100;'>ЧЕМ ВАМ ПОМОЧЬ?</h1>
    	<form method="post" action="<?php echo WB_URL; ?>/send.php" enctype="application/x-www-form-urlencoded" accept-charset="UTF-8">
			<div class='table' align="left" style="width:90%;">

				<div class='table_cell left_cell'><h7>Как к Вам обращаться:</h7></div>
				<div class='table_cell right_cell'><input style="color: #000; border: 1px solid #183c32;" type="text" name="fio" required="required" size="35" /></div>
				
				<br>

				<div class='table_cell left_cell'><h7>Как с Вами связаться:</h7></div>
				<div class='table_cell right_cell'><input style="color: #000; border: 1px solid #183c32;" id="phone" type="tel" name="phone" required="required" /></div>
				
				<br>

				<div class='table_row'>
					<div class='table_cell left_cell'  style='vertical-align:middle;'><h7>Расскажите о своей проблеме:</h7></div>
					<div class='table_cell right_cell' style='vertical-align:middle;'><textarea style="color: #000; border: 1px solid #183c32;" rows="5" name="zayavka"></textarea></div>
				</div>

				<br>

				<div class='table_cell left_cell'><h7>Защита от спама:</h7></div>
				<div class='table_cell right_cell'><?php call_captcha('image'); echo ' = '; call_captcha('input'); ?></div>

				<br>
				
				<div class='table_cell left_cell' style="color:#000; width:auto;"><input type='checkbox' name='i_agree' required="required" style='cursor:pointer;'></div>

				<div class='table_cell right_cell' style="width:auto;"><h6>Согласен (на) с условиями <a onclick="W.open_by_api('get_agreement', {add_sheet:true})" style='cursor:pointer;'>Пользовательского соглашения</a></h6></div>

			</div>

			<table align="center" width="90%" border="0" cellpadding="1" cellspacing="1">
				<tbody>
					<tr>
						<td width="100%" style="text-align: center;"><input type="submit" name="ok" class="btn-head" value="Отправить" /></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>

    <img src="<?=WB_URL.MEDIA_DIRECTORY?>/img/mail.gif" alt="" style='height:180px;position:fixed;bottom:10px;right:10px;cursor:pointer;' onclick="W.open('feedback', {add_sheet:true, add_title:false})">

    <!-- Для слайдера -->
	<script src="<?php echo WB_URL; ?>/include/added_js/responsiveslides.min.js"></script>
    
</body></html>