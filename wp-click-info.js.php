<?php
	error_reporting(0);
	include_once ('../../../wp-config.php');
	function saqClickCount() {            
                global $wp,$wpdb,$current_user;
            $result = false;
            $doRecord=true;
            if ($doRecord){
                $source = $_SERVER["HTTP_REFERER"];
                $target = $_POST["target"];
                $source = $wpdb->escape($source);
                $target = $wpdb->escape($target);
                $dt = gmdate('Y-m-d H:i:s', (time() + (get_option('gmt_offset') * 3600)));
                $sql="INSERT INTO ".$wpdb->saqClicks." (REFERER,URL,CLICKS,DT) VALUES ('$source', '$target',1,'$dt')  ON duplicate KEY UPDATE CLICKS=CLICKS+1, DT='$dt'";


                $u=get_bloginfo("url");
                $pos = strpos(strtolower($source), strtolower($u));
                if ($pos === false) {
                        wp_redirect(get_bloginfo('wpurl'), 404);
                        exit;
                } else {
                        $result = $wpdb->query(str_replace("\'", "\'\'", $sql));
                        $result = true;
                }
            }
            return $result;
	}
	if(isset($_POST['saqtrc']) && $_POST['saqtrc'] == '1'){
	saqClickCount();
		header('Content-type: text');
?>
done.
<?php
	} else {
?>(function($){
	function prettyDate(time){
		var date = new Date((time || "").replace(/-/g,"/").replace(/[TZ]/g," ")),
			diff = (((new Date()).getTime() - date.getTime()) / 1000),
			day_diff = Math.floor(diff / 86400);
				
		if ( isNaN(day_diff) || day_diff < 0 || day_diff >= 31 )
			return;
				
		return day_diff == 0 && (
				diff < 60 && "just now" ||
				diff < 120 && "1 minute ago" ||
				diff < 3600 && Math.floor( diff / 60 ) + " minutes ago" ||
				diff < 7200 && "1 hour ago" ||
				diff < 86400 && Math.floor( diff / 3600 ) + " hours ago") ||
			day_diff == 1 && "Yesterday" ||
			day_diff < 7 && day_diff + " days ago" ||
			day_diff < 31 && Math.ceil( day_diff / 7 ) + " weeks ago";
	}

	if ( typeof jQuery != "undefined" )
	jQuery.fn.prettyDate = function(){
		return this.each(function(){
			var date = prettyDate(this.title);
			if ( date )
				jQuery(this).text( date );
		});
	};
	$.expr[':'].ext = function(obj){
		var left = function(s,n) {
			return s.substring(0, n);
		}
		return obj.href != undefined && obj.href != "" && !obj.href.match(/^mailto\:/) && (obj.hostname != location.hostname) && (left(obj.href,10)!="javascript") && (left(obj.href,1)!="#");
	};
	$(function() {
		var _links = [], _parms = [],_click=null;
		$('.saq-time').each(function(){
			$(this).html(prettyDate($(this).html()));
		});

		<?php 
			$doRecord = true; 
			$current_user = wp_get_current_user();
			$is_user_logged_in = ( 0 != $current_user->ID );
			$is_admin = ($current_user->user_level == 10);
			 //print_r($current_user);
				
			 
			if ($is_admin) {
				$doRecord = (get_option('recordAdminUser',true));
			} else {
				if ($is_user_logged_in) $doRecord = get_option('recordRegisteredUser',true);   
			}   
			if ( $doRecord ) {
				?>
					_click = function(){
						<?php if (get_option('doNotOpenNewWindow',false)){echo "$(this).attr('href','javascript:void(0);');";} ?>;
						var _href=
						$.ajax({<?php if (get_option('doNotOpenNewWindow',false)){echo "async:false,";} ?>
							type: 'POST',
							url: '<?php echo plugins_url( '/', __FILE__ )."wp-click-info.js.php?ver=1.26_".get_option('hideExternalLinkIco',false);?>',
							data: { 'saqtrc': "1", 'target':$(this).data('href') },
							error:function(d,a,f){
								alert('WP Click Info: Error while tracking URL.\n'+a+'\n'+d.responseText);
							},
							dataType:'text'
						});
						$(this).attr('href',$(this).data('href'));
						return true;
					} ;
				<?php
			}
		?> 
		$('a:ext')<?php 
				if (!get_option('hideExternalLinkIco',false)){echo ".addClass('saq-external-link-ico')";} 
				if (!get_option('doNotOpenNewWindow',false)){echo ".attr('target','_blank')";} 
		?>.each(function(i,n){
			_links[$(this).attr('href')] = $(this); 
			_parms.push($(this).attr('href'));
			if ($(this).children('img:first').length!=0) $(this).removeClass('saq-external-link-ico');
			$(this).data('href',$(this).attr('href'));
		}); //.click(_click)
		//$("a.offsite").live("click", function(){ alert("Goodbye!"); });                // jQuery 1.3+
		if ($.fn.on) {
			$(document).on("click", "a:ext:not('.igno')", _click);        // jQuery 1.7+
		} else {
			if ($.fn.delegate) $(document).delegate("a:ext:not('.igno')", "click", _click);  // jQuery 1.4.3+
		} 
		
		<?php 
			if (!get_option('hideStats',false)){ 
		?>
		$.ajax({
			async:true, 
			type: 'POST',
			url: '<?php echo plugins_url( '/', __FILE__ ).'nfo.js.php?ver=1.26'; ?>',
			data: { 'urls':_parms.join('^') }, 
			success:function(d){
				$(d).each(function(i,n){  
					var _item=$('a:ext[href="'+n.URL+'"]'),_title=_item.attr('title');  
					_title=(_title ? _title + ' - ' : '');
					
					_item.attr('title', _title + ' ('+n.CLICKS+' clicks. Last was ' + prettyDate(n.DT) + ')'); 
				});  
			}
		}); 
		<?php 
			}
		?>
	});
})(jQuery);
<?php	
	}
?>