<?

	require_once($kapenta->installPath . 'modules/twitter/models/tweet.mod.php');
	require_once($kapenta->installPath . 'modules/twitter/inc/send.inc.php');

//--------------------------------------------------------------------------------------------------
//*	processes run regularly to keep things tidy
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
//|	ten minute cron
//--------------------------------------------------------------------------------------------------
//returns: HTML report of any actions taken [string]

function twitter_cron_tenmins() {
	global $db, $registry;

	if ('yes' !== $registry->get('twitter.enabled')) { return "Twitter not enabled.<br/>\n"; }

	$report = "<h2>twitter_cron_tenmins</h2>\n";	//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	send any unsent tweets if twitter is enabled on this node
	//----------------------------------------------------------------------------------------------

	$conditions = array("status='new'");
	$range = $db->loadRange('twitter_tweet', '*', $conditions, 'createdOn ASC');

	foreach($range as $item) {
		$model = new Twitter_Tweet($item['UID']);
		$model->status = 'sent';
		$model->save();

		$result .= twitter_send($model->content);
		$report .= $result;

		if (false !== strpos($result, '<ok/>')) {
			$model->status = 'fail';
			$model->save();
		}

		sleep(20);
	}

	//$sql = "update twitter_tweet set status='sent' where status='new'";
	//$db->query($sql);

	//----------------------------------------------------------------------------------------------
	//	done
	//----------------------------------------------------------------------------------------------
	return $report;

}

//--------------------------------------------------------------------------------------------------
//|	daily cron
//--------------------------------------------------------------------------------------------------
//returns: HTML report of any actions taken [string]

function twitter_cron_daily() {
	global $kapenta;
	global $registry;
	global $theme;

	if ('yes' != $registry->get('twitter.enabled')) { return "Twitter not enabled.<br/>\n";}

	$report = "<h2>twitter_cron_daily</h2>\n";	//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	poll modules for a dilay report of awareNet activity
	//----------------------------------------------------------------------------------------------
	$date = gmdate("Y-m-d", time() - (60 * 60 * 12));	// twelve hours ago, yesterdays report
	$msg = $theme->expandBlocks('[[:twitter::daily::date=' . $date . ':]]', '');

	if ('' !== $msg) {

		$args = array(
			'refModule' => 'home',
			'refModel' => 'home_static',
			'refUID' => $registry->get('home.frontpage'),
			'message' => $date . ' - ' . $msg
		);

		$kapenta->raiseEvent('twitter', 'microblog_event', $args);
		$report .= "Sending Tweet: $msg<br/>\n";

	} else {

		$report .= "Nothing to report, daily status tweet not sent.<br/>\n";

	}

	return $report;
}

?>
