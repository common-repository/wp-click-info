<?php
	global $wpdb;
if (isset($_POST["cls"])){
	$wpdb->query("DELETE FROM ".$wpdb->saqClicks);
?>
<div class="update-nag"><br>
	<strong>Statistics successfully deleted.</strong>
</div>
<?php
}
?>
<style>
#legend{
position:absolute;
top:44px;right:0; background-color:#fff;
bottom:55px;right:55px;
border:2px solid #000;
font-size:0.8em;
padding:11px;
}
</style>
<?php
	include('wp-click-info.thank-you.php');
?>
<div class="wrap">
	<h2>WP Click Info Timeline</h2>
	<i>Click and drag to zoom. Double-click to zoom back out.</i>
	<div id="legend"></div>
	<div id="div_g" style="width:650px; height:400px;  ">

	</div>

    <script type="text/javascript">
//Top Targets

function NoisyData() {
					<?php
						function obj2arr($obj) {
							$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
							foreach ($_arr as $key => $val) {
								$val = (is_array($val) || is_object($val)) ? object2array($val) : $val;
								$arr[$key] = $val;
							}
							return $arr;

						}


						$i=0;
						$head = array();
						$res = $wpdb->get_results("SELECT URL, sum(CLICKS) as CLICKS FROM ".$wpdb->saqClicks." WHERE CLICKS > 0 and DT >= DATE_ADD(NOW(), INTERVAL -30 DAY) GROUP BY URL ORDER BY CLICKS DESC");
						foreach ($res as $row) {
							$url = $row->URL;
							$u=split("/", $url);
							$head[] = $url ;//$u[0]."//".$u[2]."/".$u[3]."...";
							$fields[] = $url;
							$i++;
						}
					?>
return "" +
//"Date,<?php echo implode($head, ','); ?>\n" +
					<?php
						$sql = "SELECT DATE(DT) as DT0, " ;
						$i=0;
						foreach ($fields as $field){
							$sql .= "(select sum(CLICKS) from ".$wpdb->saqClicks." where DATE(DT) = DT0 and URL='".$field."') as a".$i."";
							$i++;
							if ($i < count($fields)) $sql .= ", ";

						}
						$sql .= " from ".$wpdb->saqClicks." GROUP BY DT0 ORDER BY DT ASC";
//			echo $sql;
						$res = $wpdb->get_results($sql);
						$rr=0;
						foreach ($res as $row) {
							$i=0;
							$r = obj2arr($row);
							echo "\"".str_replace("-","",$row->DT0).",";
							foreach ($fields as $field){
								$v = trim($r["a".$i]);
								if ($v=="")$v=0;
								echo $v;
								$i++;
								if ($i < count($fields)) echo ",";
							}
							echo "\\n\"";
							$rr++;
							if ($rr<count($res)) echo "+";
						}
					?>
}



      var lines = [];
      var xline;
      g = new Dygraph(
            document.getElementById("div_g"),
            NoisyData,
			{
				title:'Top Targets',
				ylabel: 'Clicks',
				xlabel: 'Date',
				fillGraph:false,
				labels: [ <?php echo '"'.implode($head, '","').'"'; ?> ],
				showRoller: false,
				errorBars: true,
				labelsDiv: document.getElementById('legend'),
				labelsSeparateLines: true,
				labelsKMB: false,
				legend: 'always',
				wilsonInterval:true,
				sigma:0.25,
				highlightCallback: function(e, x, pts) {
	/*                 for (var i = 0; i < pts.length; i++) {
				  var y = pts[i].canvasy;
				  lines[i].style.display = "";
				  lines[i].style.top = y + "px";
				  if (i == 0) xline.style.left = pts[i].canvasx + "px";
				}
				xline.style.display = "";
	*/          },
				unhighlightCallback: function(e) {
		/*                 for (var i = 0; i < 2; i++) {
					  lines[i].style.display = "none";
					}
					xline.style.display = "none";
*/              }
			}
          );

    </script>


			</div>
</div>