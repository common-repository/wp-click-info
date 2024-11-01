<p>
    <form method="POST">
        <div class="rpt-date">
            <strong>Reportfilter</strong>
        </div>
        <div class="rpt-date">
            <div class="rpt-date-header">Startdate: </div>
            <input name="d1" class="d" title="Day" value="<?php echo $_POST["d1"]; ?>" />.
            <input name="m1" class="m" title="Month" value="<?php echo $_POST["m1"]; ?>" />.
            <input name="y1" class="y" title="Year" value="<?php echo $_POST["y1"]; ?>" />
        </div>
        <div class="rpt-date">
            <div class="rpt-date-header">Enddate: </div>
            <input name="d2" class="d" title="Day" value="<?php echo $_POST["d2"]; ?>" />.
            <input name="m2" class="m" title="Month" value="<?php echo $_POST["m2"]; ?>" />.
            <input name="y2" class="y" title="Year" value="<?php echo $_POST["y2"]; ?>" />
        </div>
        <div class="rpt-date">
            <div class="rpt-date-header" style="width:140px;">Textfilter (Include): </div>
            <input name="txt_filter" id="txt_filter" class="" title="Comma-separated Keywords to force item inclusion." value="<?php echo $_POST["txt_filter"]; ?>" />
        </div>
        <div class="rpt-date">
            <div class="rpt-date-header" style="width:140px;">Textfilter (Exclude): </div>
            <input name="txt_filter2" id="txt_filter2" class="" title="Comma-separated Keywords to force item exclusion." value="<?php echo $_POST["txt_filter2"]; ?>" />
        </div>
        <div class="rpt-date">
            <div class="rpt-date-header" style="width:180px;border-right:solid 1px #ccc;" >Rows per report: </div>
            <input name="limit" id="limit" class="" title="" value="<?php  echo $_POST["limit"];   ?>" />
        </div>

        <div class="rpt-date" style="width:100px;">
            <div class="rpt-date-header">Data:</div>
            <input type="submit" name="date-range-filter" value="Refresh"/>
        </div>
    </form>
</p>