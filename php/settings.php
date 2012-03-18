<SCRIPT LANGUAGE="JavaScript"> 

$(document).ready(function() {


});


</SCRIPT>
<?php 

if (!isset($initok)) {echo "do not run this script directly";exit;}
if(!isset($userdata) || $userdata[0]['usertype'] == 1) { echo "You must have Admin (Full Access) to access this page";exit;}

/* Spiros Ioannou 2009-2010 , sivann _at_ gmail.com */

if (isset($_POST['dateformat']) && isset($_POST['timeformat'])) { //if we came from a post (save), update the rack 
  $sql="UPDATE settings set companytitle='".$_POST['companytitle']."', dateformat='".$_POST['dateformat']."', currency='".$_POST['currency']."',".
       " lang='".$_POST['lang']."', ".
       //" switchmapenable='".$_POST['switchmapenable']."', switchmapdir='".$_POST['switchmapdir']."',".
       " timezone='".$_POST['timezone']."', timeformat='".$_POST['timeformat']."', vendorlookupurl='".$_POST['vendorlookupurl']."',".
       " listshowentries='".$_POST['listshowentries']."', listheight='".$_POST['listheight']."'";
  db_exec($dbh,$sql);

}//save pressed

/////////////////////////////
//// display data 

$sql="SELECT * FROM settings";
$sth=$dbh->query($sql);
$settings=$sth->fetchAll(PDO::FETCH_ASSOC);
$settings=$settings[0];

echo "\n<form id='mainform' method=post  action='$scriptname?action=$action' enctype='multipart/form-data'  name='settingsfrm'>\n";

echo "\n<h1>".t("Settings")."</h1>\n";
?>

    <table class="tbl2" >
    <tr><td colspan=2><h3><?php te("Settings"); ?></h3></td></tr>
    <tr><td class="tdt"><?php te("Company Title");?>:</td> 
        <td><input  class='input2 ' size=20 type=text name='companytitle' value="<?php echo $settings['companytitle']?>"></td></tr>
    <tr><td class="tdt"><?php te("Date Format")?></td><td>
    <select  name='dateformat'>
      <? if ($settings['dateformat']=="dmy") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> value='dmy'>Day/Month/Year</option>
      <? if ($settings['dateformat']=="mdy") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> value='mdy'>Month/Day/Year</option>
    </select>
    </td>
    </tr>
    <tr><td class="tdt"><?php te("Time Format")?></td><td>
    <select  name='timeformat'>
      <? if ($settings['timeformat']=="hh:mm:ss") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> value='hh:mm:ss'>hh:mm:ss</option>
    </select>
    </td>
    </tr>

    <tr><td class="tdt"><?php te("Currency")?></td><td>

    <select  name='currency'>
      <? if ($settings['currency']=="&amp;euro;") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> title='Euro' value='<?php echo htmlentities("&euro;");?>'>&euro;</option>

      <? if ($settings['currency']=="$") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> title='Dollar' value='<?php echo htmlentities("$");?>'>$</option>

      <? if ($settings['currency']=="&amp;pound;") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> title='Pound' value='<?php echo htmlentities("&pound;");?>'>&pound;</option>

      <? if ($settings['currency']=="&amp;yen;") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> title='Yen' value='<?php echo htmlentities("&yen;");?>'>&yen;</option>

      <? if ($settings['currency']=="&amp;#8361;") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> title='Won' value='<?php echo htmlentities("&#8361;");?>'>&#8361;</option>

      <? if ($settings['currency']=="&amp;#8360;") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> title='Rupee' value='<?php echo htmlentities("&#8360;");?>'>&#8360;</option>

      <? if ($settings['currency']=="&amp;#8377;") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> title='Indian Rupee' value='<?php echo htmlentities("&#8377;");?>'>&#8377;</option>

      <? if ($settings['currency']=="&amp;#20803;") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> title='Yuan' value='<?php echo htmlentities("&#20803;");?>'>&#20803;</option>

      <? if ($settings['currency']=="&amp;#65020;") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> title='Rial' value='<?php echo htmlentities("&#65020;");?>'>&#65020;</option>
    </select></td></tr>
    <tr><td class="tdt"><?php te("Interface Language")?></td><td>
    <select  name='lang'>
      <? if ($settings['lang']=="en") $s="SELECTED"; else $s="" ?>
      <option <?php echo $s?> value='en'>en</option>
      <?
      $tfiles=scandir("translations/");
      foreach ($tfiles as $f) {
        if (strstr($f,"txt") && (!strstr($f,"new")) && (!strstr($f,"missing"))) {
	  $bf=basename($f,".txt");
	  if ($settings['lang']=="$bf") $s="SELECTED"; else $s="" ;
	  echo "<option $s value='$bf'>$bf</option>\n";
	}
      }
      ?>
    </select>
    </td>

    </tr>
    <tr><td class="tdt" title='Timezone based on 3 alpha abbreviation. (e.g. MST, EST, UTC, etc)'><?php te("Timezone (Abbreviation)");?>:</td><td>
    <select name='timezone'>
<?php
      $tz_array=file("php/timezones.txt");
      foreach ($tz_array as $tz) {
	$tz=trim($tz);
	if ($tz==$settings['timezone']) $s="SELECTED"; else $s="";
	echo "<option $s>$tz</option>\n";
      }
?>
</select>

</td></tr>

    <tr><td class="tdt" title='Should be the URL to lookup products on the vendors webpage using the service tag value. The Service tag must be appended to the end of the URL for it to work. This URL will only be applied for service tags.'><?php te("Vendor Lookup URL");?>:</td><td><input  class='input2 ' size=20 type=text name='vendorlookupurl' value="<?php echo $settings['vendorlookupurl']?>"></td></tr>

    <tr><td class="tdt" title='The default number of entries shown.'><?php te("Show Entries");?>:</td>
      <td>
        <select name='listshowentries'>
          <?php
            if ($settings['listshowentries']=="10") $s="SELECTED"; else $s="";
	      echo "<option title='10' $s value='10'>10</option>\n";
            if ($settings['listshowentries']=="18") $s="SELECTED"; else $s="";
	      echo "<option title='18' $s value='18'>18</option>\n";
            if ($settings['listshowentries']=="25") $s="SELECTED"; else $s="";
	      echo "<option title='25' $s value='25'>25</option>\n";
            if ($settings['listshowentries']=="50") $s="SELECTED"; else $s="";
	      echo "<option title='50' $s value='50'>50</option>\n";
            if ($settings['listshowentries']=="100") $s="SELECTED"; else $s="";
	      echo "<option title='100' $s value='100'>100</option>\n";
            if ($settings['listshowentries']=="All") $s="SELECTED"; else $s="";
	      echo "<option title='All' $s value='All'>All</option>\n";
          ?>
      </select>
      </td>
    </tr>
    <tr><td class="tdt" title='What height in pixels do you want the list view?'><?php te("List Height");?>:</td><td><input  class='input2 ' size=20 type=text name='listheight' value="<?php echo $settings['listheight']?>"></td></tr>

<!--
    <tr><td colspan=2><h3><?php te("Integration"); ?></h3></td></tr>

    <tr>
    <?php
      //SwitchMap Enabled (switchmapenable)
      $y="";$n="";
      if ($settings['switchmapenable']=="1") {$y="checked";$n="";}
      if ($settings['switchmapenable']=="0") {$n="checked";$y="";}
    ?>
      <td class='tdt' title='Select yes if switchmap is installed on this server.'><?php te("SwitchMap Integration");?>:</td>
      <td>
        <div >
          <input  validate='required:true' <?php echo $y?> class='radio' type=radio name='switchmapenable' value='1'><?php te("Yes");?>
          <input  class='radio' type=radio <?php echo $n?> name='switchmapenable' value='0'><?php te("No");?>
        </div>
      </td>
    </tr>
    <tr><td class="tdt" title='Provide the full path to the switches directory within the SwitchMap directory.'><?php te("Path To Switchmap");?>:</td><td><input  class='input2 ' size=20 type=text name='switchmapdir' value="<?php echo $settings['switchmapdir']?>"></td></tr>

-->

<tr>
<td colspan=2>
<br>
<button type="submit"><img src="images/save.png" alt="Save"> <?php te("Save");?></button>
</td>
</tr>
</table>
<input type=hidden name='action' value='<?php echo $action ?>'>
</form>

</body>
</html>
