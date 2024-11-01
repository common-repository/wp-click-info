<?php
/*
Plugin Name: WP Click Info
Plugin URI: http://saquery.com/wordpress/wp-click-info/
Description: Your external <a href="admin.php?page=wp-click-info">Blog Link Reports</a> | <a href="http://wordpress.org/extend/plugins/wp-click-info/changelog/" target="_blank">Changelog</a> | <a href="http://saquery.com/wordpress/wp-click-info/">WP Click Info Support</a>
Version: 2.7.4
Author: Stephan Ahlf
Author URI: http://saquery.com
*/


	$wpdb->saqClicks = "SAQ_CLICK_STATISTICS";
	$saqClickCheck_db_version = "0.1";
	class wp_click_info{


            function admin_init() {
			register_setting( 'wpclickinfoOptions', 'hideExternalLinkIco' );
			register_setting( 'wpclickinfoOptions', 'doNotOpenNewWindow' );
			register_setting( 'wpclickinfoOptions', 'hideStats' );
			register_setting( 'wpclickinfoOptions', 'recordAdminUser' );
			register_setting( 'wpclickinfoOptions', 'recordRegisteredUser' );
            }

            function init() {
                    global $wpdb;
                    $sql="CREATE TABLE IF NOT EXISTS ".$wpdb->saqClicks."(
                    REFERER varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
                    URL varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
                    CLICKS INT(11) NOT NULL,
                    DT DATETIME NOT NULL,
                    UNIQUE (REFERER,URL)
                    ) ENGINE = MYISAM CHARACTER SET ascii COLLATE ascii_general_ci ";
                            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                            dbDelta($sql);
            }

            function plugin_options(){
                    require_once dirname(__FILE__).'/wp-click-info.options.php';
            }
            function plugin_timeline(){
			require_once dirname(__FILE__).'/wp-click-info.timeline.php';
		}


		function options() {

			global $wpdb;

			?>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css" type="text/css" media="all" />
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>
<style>
.wp-click-info-table
{

	margin-top:11px;
	width: 100%;
	border-collapse: collapse;
	text-align: left;
}
.wp-click-info-table th
{
	font-size: 14px;
	font-weight: bold;
	padding: 10px 8px;
	border-bottom: 2px solid #6678b1;
}
.wp-click-info-table td
{
	font-size: 10px;
	border-bottom: 1px solid #ccc;
	padding: 6px 8px;
	vertical-align:top;
	background-color:#fff;
}
.wp-click-info-table tbody tr:hover td
{
	color: #009;
}

.rpt-date{
    margin:11px;
    float:left;
}
.d,.m,.y {width:15px;}
.y {width:30px;
}
.rpt-date-header{
	width:30px;
}

</style>
<script>
(function($){
<?php


	$res = $wpdb->get_results("(SELECT  SUBSTRING_INDEX(REPLACE(REPLACE(REFERER,'http://',''),'https://',''),'/',1) AS DOMAIN FROM SAQ_CLICK_STATISTICS)
								UNION DISTINCT
								(SELECT  SUBSTRING_INDEX(REPLACE(REPLACE(URL,'http://',''),'https://',''),'/',1) AS DOMAIN FROM SAQ_CLICK_STATISTICS  order by DOMAIN)
								order by DOMAIN ASC");
	$domains=array();
	foreach ($res as $row) $domains[]=$row->DOMAIN;

?>
	$(function() {
		var
			limitTags=["5","10","20","40","80","160"],
			availableTags = <?php echo json_encode($domains); ?>;
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}
		$('#limit').autocomplete({
			source: limitTags
		});
		$( "#txt_filter,#txt_filter2" )
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).data( "autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				minLength: 0,
				source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						availableTags, extractLast( request.term ) ) );
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( ", " );
					return false;
				}
			});
	});
})(jQuery);
</script>
<?php include('wp-click-info.thank-you.php'); ?>
			<div class='wrap'>
                            <?php include_once('wp-click-info-filter.php'); ?>
				<table class="wp-click-info-table">
					<tr>
						<th>#</th>
						<th>Top Targets</th>
						<th>Clicks</th>
						<th>Last Click</th>
					</tr>
					<?php

						$where = array();
						$limit = 10;

						if (isset($_POST["date-range-filter"])){
							if (isset($_POST["d1"]) || isset($_POST["m1"]) || isset($_POST["y1"])){
								if ($_POST["y1"]!=="") $where []= $wpdb->saqClicks.".DT >= '".$_POST["y1"]."-".$_POST["m1"]."-".$_POST["d1"]."'";
							}

							if (isset($_POST["d2"]) || isset($_POST["m2"]) || isset($_POST["y2"])){
								if ($_POST["y2"]!=="") $where []= $wpdb->saqClicks.".DT <= '".$_POST["y2"]."-".$_POST["m2"]."-".$_POST["d2"]."'";
							}
						}

						if (count($where)==0) {
							$where =  "";
						} else {
							$where =  "where ".implode(" and ", ($where));
						}


						if (isset($_POST["txt_filter"])){
							$filter1 =  explode(",", trim($_POST["txt_filter"]));
							while (!empty($filter1) and strlen(reset($filter1)) === 0) array_shift($filter1);
							while (!empty($filter1) and strlen(end($filter1)) === 0) array_pop($filter1);
							foreach ($filter1 as $key => $value) $filter1[$key] = "(".$wpdb->saqClicks.".URL LIKE '%" . trim($value) . "%' or " . $wpdb->saqClicks.".REFERER LIKE '%" . trim($value) . "%')";
							$txtFilterInclude =  implode(" and ",$filter1);

							if (count($filter1)!=0){
								if ($where == ""){
									$where = " where ".$txtFilterInclude;
								} else {
									$where .= " and " . $txtFilterInclude;
								}
							}
						}
						if (isset($_POST["txt_filter2"])){
							$filter2 =  explode(",", trim($_POST["txt_filter2"]));
							while (!empty($filter2) and strlen(reset($filter2)) === 0) array_shift($filter2);
							while (!empty($filter2) and strlen(end($filter2)) === 0) array_pop($filter2);
							foreach ($filter2 as $key => $value) $filter2[$key] = "(".$wpdb->saqClicks.".URL NOT LIKE '%" . trim($value) . "%' and " . $wpdb->saqClicks.".REFERER NOT LIKE '%" . trim($value) . "%')";
							$txtFilterInclude =  implode(" and ",$filter2);

							if (count($filter2)!=0){
								if ($where == ""){
									$where = " where ".$txtFilterInclude;
								} else {
									$where .= " and " . $txtFilterInclude;
								}
							}
						}

						if (isset($_POST["limit"])){
							if ($_POST["limit"]!="") $limit = $_POST["limit"];
						}






						$res = $wpdb->get_results("SELECT URL, sum(CLICKS) as CLICKS, max(DT) as DT FROM ".$wpdb->saqClicks." $where GROUP BY URL ORDER BY CLICKS DESC, DT DESC LIMIT 0 , $limit");
                                                                                                            //echo "current Filter: ".$where;

						$i=1;
						foreach ($res as $row) {
							print "<tr>";
							print '<td style="width:10px;">'.$i++."</td>";
							print "<td>".urldecode($row->URL)."</td>";
							print "<td>$row->CLICKS</td>";
							print "<td class=\"saq-time\">".$row->DT.""."</td>";
							print "</tr>";
						}
					?>
				</table>

				<table class="wp-click-info-table">
					<tr>
						<th>#</th>
						<th>Top Referrer</th>
						<th>Clicks</th>
						<th>Last Click</th>
					</tr>
					<?php
						$res = $wpdb->get_results("SELECT REFERER, sum(CLICKS) as CLICKS, max(DT) as DT FROM ".$wpdb->saqClicks."  $where GROUP BY REFERER ORDER BY CLICKS DESC, DT DESC LIMIT 0 , $limit");
						$i=1;
						foreach ($res as $row) {
							print "<tr>";
							print '<td style="width:10px;">'.$i++."</td>";
							print "<td>".urldecode($row->REFERER)."</td>";
							print "<td>$row->CLICKS</td>";
							print "<td class=\"saq-time\">".$row->DT.""."</td>";
							print "</tr>";
						}
					?>
				</table>




				<table class="wp-click-info-table">
					<tr>
						<th>#</th>
						<th>Top Target by Referrer</th>
						<th>Referrer</th>
						<th>Clicks</th>
						<th>Last Click</th>
					</tr>
					<?php
						$res = $wpdb->get_results("SELECT URL, REFERER, sum(CLICKS) as CLICKS, max(DT) as DT FROM ".$wpdb->saqClicks."  $where GROUP BY URL, REFERER ORDER BY CLICKS DESC, DT DESC LIMIT 0 , $limit");
						$i=1;
						foreach ($res as $row) {

							print "<tr>";
							print '<td style="width:10px;">'.$i++."</td>";
							print "<td>".urldecode($row->URL)."</td>";
							print "<td>$row->REFERER</td>";
							print "<td>$row->CLICKS</td>";
							print "<td class=\"saq-time\">".$row->DT.""."</td>";
							print "</tr>";
						}
					?>
				</table>

				<table class="wp-click-info-table">
					<tr>
						<th>#</th>
						<th>Top Referrer by Target</th>
						<th>Target</th>
						<th>Clicks</th>
						<th>Last Click</th>
					</tr>
					<?php
						$i=1;
						$res = $wpdb->get_results("SELECT REFERER, URL, sum(CLICKS) as CLICKS, max(DT) as DT FROM ".$wpdb->saqClicks."  $where GROUP BY REFERER, URL ORDER BY CLICKS DESC, DT DESC LIMIT 0 , $limit");
						foreach ($res as $row) {
							print "<tr>";
							print '<td style="width:10px;">'.$i++."</td>";
							print "<td>$row->REFERER</td>";
							print "<td>".urldecode($row->URL)."</td>";
							print "<td>$row->CLICKS</td>";
							print "<td class=\"saq-time\">".$row->DT.""."</td>";
							print "</tr>";
						}
					?>
				</table>
			</div>
			<?php

		}

		function wp_print_scripts(){
			wp_enqueue_script('jquery');

			$u = plugins_url( '/', __FILE__ )."wp-click-info.js.php";
			$v = '1.0';
			wp_deregister_script( 'wp-click-info' );
			wp_register_script( 'wp-click-info', $u, array(), $v, false);
			wp_enqueue_script('wp-click-info');
		}
		function wp_print_admin_scripts(){
		}

		function wp_print_styles() {
			$u = plugins_url( '/', __FILE__ )."wp-click-info.css";
			wp_register_style('wp-click-info-style', $u);
			wp_enqueue_style( 'wp-click-info-style');

		}

		function admin_menu(){
			$u = plugins_url( '/', __FILE__ )."dygraph-combined.js";
			$v = '1.0';
			wp_deregister_script( 'dygraph' );
			wp_register_script( 'dygraph', $u, array(), $v, false);
			wp_enqueue_script('dygraph');

			add_menu_page('WP Click Info', 'WP Click Info', 'administrator', 'wp-click-info', array('wp_click_info','options'));
			add_submenu_page( 'wp-click-info', 'Timeline', 'Timeline', 'manage_options', 'wp-click-info-timeline', array('wp_click_info','plugin_timeline'));
			add_submenu_page( 'wp-click-info', 'Settings', 'Options', 'manage_options', 'wp-click-info-options', array('wp_click_info','plugin_options'));
			add_action( 'admin_init', array('wp_click_info','admin_init') );
		}


		function saqDashboardClickCheck() {
				global $wpdb;
		?>
<style>
.wp-click-info-table
{
	width: 100%;
	border-collapse: collapse;
	text-align: left;
}
.wp-click-info-table th
{
	font-size: 14px;
	font-weight: bold;
	padding: 10px 8px;
	border-bottom: 2px solid #6678b1;
	background-color:#fff;
}
.wp-click-info-table td
{
	font-size: 8px;
	border-bottom: 1px solid #ccc;
	padding: 6px 8px;
	vertical-align:top;
	background-color:#fff;
}
.wp-click-info-table tbody tr:hover td
{
	color: #009;
}
</style>
		<div class='wrap'>
			<table class="wp-click-info-table">
				<tr>
					<th>Top 10 Targets</th>
					<th>Clicks</th>
					<th>Last Click</th>
				</tr>
				<?php
					$res = $wpdb->get_results("SELECT URL, sum(CLICKS) as CLICKS, max(DT) as DT FROM ".$wpdb->saqClicks."  $where GROUP BY URL ORDER BY CLICKS DESC, DT DESC LIMIT 0 , 10");
					foreach ($res as $row) {
						print "<tr>";
						print "<td>".urldecode($row->URL)."</td>";
						print "<td>$row->CLICKS</td>";
						print "<td class=\"saq-time\">".$row->DT.""."</td>";
						print "</tr>";
					}
				?>
			</table>
			<div>
				<div style="margin-top:11px;text-align:right;"><a onclick="jQuery(this).parent().next().toggle();" href="javascript:void(0);">about</a></div>
				<div style="display:none;">
					<p>
						Contact for support or feature requests at <a href="http://saquery.com/wordpress/wp-click-info/" >saquery.com/wordpress/wp-click-info/</a> or <a href="mailto:webmaster@saquery.com"> via EMail</a>
					</p>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">

					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="SQRUU7JKE7KFS">
					<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/de_DE/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
			</div>
		</div>
		<?php
		}


		function wp_dashboard_setup() {
			wp_add_dashboard_widget( 'saqDashboardClickCheck', __( 'WP Click Info' ), array('wp_click_info', 'saqDashboardClickCheck') );
		}

        function tag( $atts ){
            global $wpdb;

            if ($atts["title"]==""){$atts["title"]="Top 10 Target URLs";}
            if ($atts["limit"]==""){$atts["limit"]="10";}
            $res = $wpdb->get_results("SELECT URL, sum(CLICKS) as CLICKS, max(DT) as DT FROM ".$wpdb->saqClicks." GROUP BY URL ORDER BY CLICKS DESC, DT DESC LIMIT 0 , ".$atts["limit"]);
            $result="<table><tr><th colspan=\"4\">".$atts["title"]."</th></tr>";
            $i=1;

            foreach ($res as $row) {
                $review = get_post_meta($post->ID, 'id-review', true);
                $thispost = get_post( $review );
                $content = $thispost->post_content;


                $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
                if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
                  foreach($matches as $match) {
                    if ($match[2]==$row->URL){
                        $result .= "<tr>";
                        $result .= '<td style="width:10px;">#'.$i++."</td>";
                        $result .= '<td><a href="'.$match[2].'">'.$match[3]."</a></td>";
                        $result .= "<td>$row->CLICKS clicks</td>";

                        $result .= "<td class=\"saq-time\">".$row->DT.""."</td>";
                        $result .= "</tr>";
                    }
                  }
                }
            }
         return $result.'<tr><td colspan="4" style="font-size:xx-small;text-align:right;"><i><a href="http://saquery.com/wp-click-info/">Proudly powered by WP Click Info</a></i></td></tr></table>';
        }

	}

	add_action('admin_menu', array('wp_click_info','admin_menu'));
	add_action('wp_print_scripts', array('wp_click_info', 'wp_print_scripts'), 1);
	add_action('wp_print_styles', array('wp_click_info', 'wp_print_styles'));
	add_action('wp_dashboard_setup', array('wp_click_info', 'wp_dashboard_setup'));

	if(isset($_GET['activate']) && $_GET['activate'] == 'true')
	add_action('init', array('wp_click_info', 'init'));

    /*
    [wp-click-info type="top-targets" limit="10" title="Top 10 Target URLs"]
    */

    add_shortcode( 'wp-click-info', array('wp_click_info', 'tag') );

?>