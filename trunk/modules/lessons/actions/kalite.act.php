<?php

	require_once($kapenta->installPath . 'modules/lessons/inc/khan.inc.php');

//--------------------------------------------------------------------------------------------------
//*	This action sets up the Initial KA Lite Page
//--------------------------------------------------------------------------------------------------
	global $kapenta;

	if ('admin' !== $kapenta->user->role 
	and 'teacher' !== $kapenta->user->role
	and 'student' !== $kapenta->user->role) { $kapenta->page->do403(); }

	$contents = '<div id="logo">
                 	<a id="logo-image-small"  title="KA Lite Home">
                  		<img src="http://localhost/static/images/horizontal-logo-small.png" alt="KA Lite logo">
                        	</a>
                            	</div>
                            		<div class="clear"></div>
                 </div>';
                 
    $contents = $contents . "<h1><a class='black' href='http://localhost/lessons/homekhan'>Watch Videos and Do Exercises</a></h1>
							Watch Video Lessons and do the Exercises that the teacher has already prepared for you.";
   	$contents = $contents . "<h1><a class='black' href='http://localhost/lessons/coachreportskhan'>Coach Reports</a></h1>
							Teacher, check the progress of your students with Khan Academy (KAlite) ";
   	$contents = $contents . "<h1><a class='black' href='http://localhost/lessons/updatekhan'>Update Videos and Exercises</a></h1>
							Teacher, download new Videos and Exercises from Khan Academy (KAlite) ";


	//----------------------------------------------------------------------------------------------
	//	Render KA Lite main page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/lessons/actions/khan.page.php');
	$kapenta->page->blockArgs['kalisting'] = $contents;
	$kapenta->page->render();	
?>