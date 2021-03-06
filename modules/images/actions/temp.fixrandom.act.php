<?php

//--------------------------------------------------------------------------------------------------
//*	temprary / development action to fill deleted images with random ones
//--------------------------------------------------------------------------------------------------

	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	make a list of random images
	//----------------------------------------------------------------------------------------------

	$fromDir = "/home/strix/Videos/upload/wallpaper/";
	$result = shell_exec("find $fromDir");
	$lines = explode("\n", $result);
	$imgs = array();
	echo "<h1>Random Images</h1>\n";

	foreach($lines as $line) {
		if (false !== strpos($line, '.jpg')) {
			echo "Img: " . $line . "<br/>\n";
			$imgs[] = trim($line);
		}
	}

	shuffle($imgs);

	//----------------------------------------------------------------------------------------------
	//	find missing images
	//----------------------------------------------------------------------------------------------

	$sql = "select * from images_image";
	$result = $kapenta->db->query($sql);

	while ($row = $kapenta->db->fetchAssoc($result)) {
		$item = $kapenta->db->rmArray($row);
		if (false == $kapenta->fs->exists($item['fileName'])) {
			echo $item['fileName'] . "<br/>\n";

			$imgFile = array_pop($imgs);
			echo "new: $imgFile <br/>\n";

			$kapenta->fileMakeSubdirs($item['fileName']);

			copy($imgFile, $kapenta->installPath . $item['fileName']);

		}
	}
	

?>
