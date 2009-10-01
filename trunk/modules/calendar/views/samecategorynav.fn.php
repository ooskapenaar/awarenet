<?

	require_once($installPath . 'modules/calendar/models/calendar.mod.php');

//--------------------------------------------------------------------------------------------------
//	show list of upcoming events in the same category
//--------------------------------------------------------------------------------------------------
// * $args['raUID'] = recordAlias or UID or calendar entry

function calendar_samecategorynav($args) {
	global $serverPath;
	if (array_key_exists('raUID', $args) == false) { return false; }
	$c = new Calendar($args['raUID']);
	$html = '';
	
	$ev = $c->loadUpcoming($c->data['category'], 20);
	
	if (count($ev) > 0) {
		$html .= "<table noborder>\n";
		$html .= "<tr>\n";
		$html .= "<td class='title' width='90px'>&nbsp;Date&nbsp;</td>\n";
		$html .= "<td class='title'>&nbsp;Event&nbsp;</td>\n";
		$html .= "</tr>\n";
		foreach($ev as $UID => $row) {
			$link = $serverPath . 'calendar/' . $row['recordAlias'];
			$html .= "<tr>\n";			
			$html .= "<td valign='top'>" . $row['year'] . '-' . $row['month'] 
				. '-' . $row['day'] . "</td>\n";
			$html .= "<td><a href='" . $link . "'>" . $row['title'] . "</a></td>\n";
			$html .= "</tr>\n";
		}
		$html .= "</table>\n";

	} else {
		$html = '(no upcoming events)';
	}
	return $html;
}

//--------------------------------------------------------------------------------------------------

?>