<?php
include_once 'includes/init.php';

if (($user != $login) && $is_nonuser_admin)
  load_user_layers ($user);
else
  load_user_layers ();

load_user_categories ();

$wday = strftime ( "%w", mktime ( 3, 0, 0, $thismonth, $thisday, $thisyear ) );

$now = mktime ( 3, 0, 0, $thismonth, $thisday, $thisyear );
$nowYmd = date ( "Ymd", $now );

$next = mktime ( 3, 0, 0, $thismonth, $thisday + 1, $thisyear );
$nextYmd = date ( "Ymd", $next );
$nextyear = date ( "Y", $next );
$nextmonth = date ( "m", $next );
$nextday = date ( "d", $next );
$month_ago = date ( "Ymd", mktime ( 3, 0, 0, $thismonth - 1, $thisday, $thisyear ) );

$prev = mktime ( 3, 0, 0, $thismonth, $thisday - 1, $thisyear );
$prevYmd = date ( "Ymd", $prev );
$prevyear = date ( "Y", $prev );
$prevmonth = date ( "m", $prev );
$prevday = date ( "d", $prev );
$month_ahead = date ( "Ymd", mktime ( 3, 0, 0, $thismonth + 1, $thisday, $thisyear ) );

$HeadX = '';
if ( $auto_refresh == "Y" && ! empty ( $auto_refresh_time ) ) {
  $refresh = $auto_refresh_time * 60; // convert to seconds
  $HeadX = "<meta http-equiv=\"refresh\" content=\"$refresh; url=day.php?$u_url" .
    "date=$nowYmd$caturl\" target=\"_self\" />\n";
}
$INC = array('js/popups.php');
print_header($INC,$HeadX);
?>

<?php

/* Pre-Load the repeated events for quckier access */
$repeated_events = read_repeated_events ( empty ( $user ) ? $login : $user,
  $cat_id  );

/* Pre-load the non-repeating events for quicker access */
$events = read_events ( empty ( $user ) ? $login : $user, $nowYmd, $nowYmd,
  $cat_id  );

?>

<table style="border-width:0px; width:100%;">
<tr><td style="vertical-align:top; width:70%;"><tr><td>
<table style="border-width:0px; width:100%;">
<tr>
<?php if ( empty ( $friendly ) ) { ?>
<td style="text-align:left;"><a href="day.php?<?php echo $u_url;?>date=<?php echo $prevYmd . $caturl;?>"><img src="leftarrow.gif" class="prevnext" alt="<?php etranslate("Previous"); ?>" /></a></td>
<?php } ?>
<td style="text-align:center; color:<?php echo $H2COLOR;?>; font-weight:bold;"><span style="font-size:24px;">
<?php
  echo date_to_str ( $nowYmd );
?>
</span>
<span style="font-size:18px;">
<?php
  // display current calendar's user (if not in single user)
  if ( $single_user == "N" ) {
    echo "<br />";
    echo $user_fullname;
  }
  if ( $is_nonuser_admin )
    echo "<br />-- " . translate("Admin mode") . " --";
  if ( $is_assistant )
    echo "<br />-- " . translate("Assistant mode") . " --";
  if ( $categories_enabled == "Y" ) {
    echo "<br />\n<br />\n";
    print_category_menu('day', sprintf ( "%04d%02d%02d",$thisyear, $thismonth, $thisday ), $cat_id, $friendly);
  }
?>
</span>
</td>
<?php if ( empty ( $friendly ) ) { ?>
<td style="text-align:right;"><a href="day.php?<?php echo $u_url;?>date=<?php echo $nextYmd . $caturl;?>"><img class="prevnext" src="rightarrow.gif" alt="<?php etranslate("Next"); ?>" /></a></td>
<?php } ?>
</tr>
</table>

<?php if ( empty ( $friendly ) || ! $friendly ) { ?>
<table style="border-width:0px; width:100%;" cellspacing="0" cellpadding="0">
<tr><td style="background-color:<?php echo $TABLEBG?>;">
<table style="border-width:0px; width:100%;" cellspacing="1" cellpadding="2">
<?php } else { ?>
<table style="border-width:1px; width:100%;" cellspacing="0" cellpadding="0">
<?php } ?>


<?php
if ( empty ( $TIME_SLOTS ) )
  $TIME_SLOTS = 24;

print_day_at_a_glance ( date ( "Ymd", $now ),
  empty ( $user ) ? $login : $user, ! empty ( $friendly ), $can_add );
?>

<?php if ( empty ( $friendly ) || ! $friendly ) { ?>
</table>
</td></tr></table>
<?php } else { ?>
</table>
<?php } ?>

</td>
<td style="vertical-align:top;">
<?php if ( empty ( $friendly ) ) { ?>
<div style="text-align:right;">
<table style="border-width:0px;" cellspacing="0" cellpadding="0">
<tr><td style="background-color:<?php echo $TABLEBG?>;">
<table style="border-width:0px; width:100%;" cellspacing="1" cellpadding="2">
<tr><th colspan="7" style="background-color:<?php echo $THBG?>; color:<?php echo $THFG?>; font-size:47px;"><?php echo $thisday?></th></tr>
<tr>
<td style="text-align:left; background-color:<?php echo $THBG?>;"><a href="day.php?<?php echo $u_url; ?>date=<?php echo $month_ago . $caturl?>" class="monthlink"><img src="leftarrowsmall.gif" class="prevnextsmall" alt="<?php etranslate("Previous")?>" /></a></td>
<th colspan="5" style="background-color:<?php echo $THBG?>; color:<?php echo $THFG?>;"><?php echo date_to_str ( sprintf ( "%04d%02d01", $thisyear, $thismonth ), $DATE_FORMAT_MY, false ) ?></th>
<td style="text-align:right; background-color:<?php echo $THBG?>;"><a href="day.php?<?php echo $u_url; ?>date=<?php echo $month_ahead . $caturl?>" class="monthlink"><img src="rightarrowsmall.gif" class="prevnextsmall" alt="<?php etranslate("Next") ?>" /></a></td>
</tr>
<?php
echo "<tr>";
if ( $WEEK_START == 0 ) echo "<td style=\"background-color:$CELLBG;\"><font size=\"-3\">" .
  weekday_short_name ( 0 ) . "</td>";
for ( $i = 1; $i < 7; $i++ ) {
  echo "<td style=\"background-color:$CELLBG;\"><font size=\"-3\">" .
    weekday_short_name ( $i ) . "</td>";
}
if ( $WEEK_START == 1 ) echo "<td style=\"background-color:$CELLBG;\"><font size=\"-3\">" .
  weekday_short_name ( 0 ) . "</td>";
echo "</tr>\n";
// generate values for first day and last day of month
$monthstart = mktime ( 3, 0, 0, $thismonth, 1, $thisyear );
$monthend = mktime ( 3, 0, 0, $thismonth + 1, 0, $thisyear );
if ( $WEEK_START == "1" )
  $wkstart = get_monday_before ( $thisyear, $thismonth, 1 );
else
  $wkstart = get_sunday_before ( $thisyear, $thismonth, 1 );
$wkend = $wkstart + ( 3600 * 24 * 7 );

for ( $i = $wkstart; date ( "Ymd", $i ) <= date ( "Ymd", $monthend );
  $i += ( 24 * 3600 * 7 ) ) {
  for ( $i = $wkstart; date ( "Ymd", $i ) <= date ( "Ymd", $monthend );
    $i += ( 24 * 3600 * 7 ) ) {
    echo "<tr style=\"text-align:center;\">\n";
    for ( $j = 0; $j < 7; $j++ ) {
      $date = $i + ( $j * 24 * 3600 );
      if ( date ( "Ymd", $date ) >= date ( "Ymd", $monthstart ) &&
        date ( "Ymd", $date ) <= date ( "Ymd", $monthend ) ) {
        if ( date ( "Ymd", $date ) == date ( "Ymd", $now ) )
          echo "<td style=\"background-color:$TODAYCELLBG;\">";
        else
          echo "<td style=\"background-color:$CELLBG; font-size:10px;\">";
        echo "<a href=\"day.php?";
        echo $u_url;
        echo "date=" . date ( "Ymd", $date ) . "$caturl\" class=\"monthlink\">" .
         date ( "d", $date ) .
         "</a></td>\n";
      } else {
        print "<td style=\"background-color:$CELLBG;\">&nbsp;</td>\n";
      }
    }
    echo "</tr>\n";
  }
}
?>
</table>
</td></tr></table>
</div>
<?php } ?>
</td></tr></table>

<br /><br />

<?php if ( ! empty ( $eventinfo ) && empty ( $friendly ) ) echo $eventinfo; ?>

<?php if ( empty ( $friendly ) ) {

  display_unapproved_events ( ( $is_assistant || $is_nonuser_admin ? $user : $login ) );

?>

<br /><br />
<a href="day.php?<?php
  echo $u_url;
  if ( $thisyear ) {
    echo "year=$thisyear&month=$thismonth&day=$thisday&";
  }
  if ( ! empty ( $cat_id ) ) echo "cat_id=$cat_id&";
?>friendly=1" target="cal_printer_friendly" onmouseover="window.status = '<?php etranslate("Generate printer-friendly version")?>'">[<?php etranslate("Printer Friendly")?>]</a>

<?php print_trailer (); ?>

<?php } else {
	print_trailer ( false );
      }
?>

</body>
</html>
