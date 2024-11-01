<?php
/*
Plugin Name: SocWidgIt
Plugin URI: http://www.itcreati.com
Description: Social buttons in sidebar.
Author: Artur Kirilyuk
Version: 0.5.1
Author URI: http://www.itcreati.com

My Widget is released under the GNU General Public License (GPL)
http://www.gnu.org/licenses/gpl.txt
This is a WordPress plugin and widget (http://wordpress.org)
*/

function widget_socwidgit_init() {

	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	function widget_socwidgit($args) {

		extract($args);

		// Collect our widget's options, or define their defaults.
		$options = get_option('widget_socwidgit');
		$url 	= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$socTitle = wp_title(' - ',false,'');
		$title 	= htmlspecialchars($options['title'], 	ENT_QUOTES);
		$vkApiId= htmlspecialchars($options['vkApiId'],	ENT_QUOTES);
		$twitVia= htmlspecialchars($options['twitVia'],	ENT_QUOTES);
		$twitRel= htmlspecialchars($options['twitRel'],	ENT_QUOTES);

		echo $before_widget;
		echo $before_title . $title . $after_title;

?><style type="text/css">
.SocWidgIt, .SocWidgIt-FB, .SocWidgIt-VK, .SocWidgIt-Tw, .SocWidgIt-GP, .SocWidgIt-FB iframe {width:180px}
.SocWidgIt-FB iframe {height:20px; border:none; overflow:hidden}
.SocWidgIt-VK {margin-bottom:4px}
</style>
<div class="SocWidgIt">

  <div class="SocWidgIt-FB">
    <iframe src="http://www.facebook.com/plugins/like.php?href=http://<?php echo $url?>&amp;layout=button_count&amp;locale=ru_RU&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;height=20&amp;width=180" 
scrolling="no" frameborder="0" allowTransparency="true"></iframe>
  </div>

<?php if (intval($vkApiId) > 0) { ?>
  <div class="SocWidgIt-VK">
    <script type="text/javascript" src="/wp-content/plugins/socwidgit/openapi.js?123"></script>
    <script type="text/javascript">
      VK.init({apiId: <?php echo $vkApiId?>, onlyWidgets: true});
    </script>

    <!-- Put this div tag to the place, where the Like block will be -->
    <div id="vk_like"></div>
    <script type="text/javascript">
    VK.Widgets.Like("vk_like", {type: "button", height: 20});
    </script>
  </div>
<?php } ?>

<?php if ($twitVia) { ?>
  <div class="SocWidgIt-Tw">
    <a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-counturl="<?php echo $url?>" data-via="<?php echo $twitVia?>" data-related="<?php echo $twitRel?>">Tweet</a>
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
  </div>
<?php } ?>

  <div class="SocWidgIt-GP">
    <g:plusone href="<?php echo $url?>" size="medium"></g:plusone>
    <script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'ru', parsetags: 'explicit'}</script>
    <script type="text/javascript">gapi.plusone.go();</script>
  </div>

</div><?php

		echo $after_widget;
	}


	function widget_socwidgit_control() {

		$options = get_option('widget_socwidgit');

		if ( $_POST['socwidgit-submit'] ) {
			$newoptions['title'] =	strip_tags(stripslashes($_POST['socwidgit-title']	));
			$newoptions['vkApiId'] =strval(intval(		$_POST['socwidgit-vkApiId']	));
			$newoptions['twitVia'] =strip_tags(stripslashes($_POST['socwidgit-twitVia']	));
			$newoptions['twitRel'] =strip_tags(stripslashes($_POST['socwidgit-twitRel']	));
			if ( $options != $newoptions ) {
				$options = $newoptions;
				update_option('widget_socwidgit', $options);
			} 
		}

		$title 	= htmlspecialchars($options['title'], 	ENT_QUOTES);
		$vkApiId= htmlspecialchars($options['vkApiId'],	ENT_QUOTES);
		$twitVia= htmlspecialchars($options['twitVia'],	ENT_QUOTES);
		$twitRel= htmlspecialchars($options['twitRel'],	ENT_QUOTES);

?>
<style>
#socwidgit-config input {width:90px; float:right;}
#socwidgit-config label {line-height:35px;display:block;}
</style>
<div id="socwidgit-config">
	<label for="socwidgit-title">Widget title: 
		<input type="text" id="socwidgit-title" name="socwidgit-title" value="<?php echo $title; ?>" />
	</label>
	<label for="socwidgit-vkApiId">Vkontakte API Id:
		<input type="text" id="socwidgit-vkApiId" name="socwidgit-vkApiId" value="<?php echo $vkApiId; ?>" />
	</label>
	<label for="socwidgit-twitVia">Twitter Author:
		<input type="text" id="socwidgit-twitVia" name="socwidgit-twitVia" value="<?php echo $twitVia; ?>" />
	</label>
	<label for="socwidgit-twitRel">Twitter Recomended:
		<input type="text" id="socwidgit-twitRel" name="socwidgit-twitRel" value="<?php echo $twitRel; ?>" />
	</label>
	<input type="hidden" name="socwidgit-submit" id="socwidgit-submit" value="1" />
</div>
	<?php
	// end of widget_socwidgit_control()
	}

	// This registers the widget. About time.
	register_sidebar_widget('SocWidgIt!', 'widget_socwidgit');

	// This registers the (optional!) widget control form.
	register_widget_control('SocWidgIt!', 'widget_socwidgit_control');
}

// Delays plugin execution until Dynamic Sidebar has loaded first.
add_action('plugins_loaded', 'widget_socwidgit_init');
?>