<? header('HTTP/1.1 403 Forbidden'); exit('403 - forbidden'); /*
<?xml version="1.0" ?>

<page>
	<template>twocol-rightnav.template.php</template>
	<title>[`|pc][`|pc]websiteName[`|pc][`|pc] - package details - %%name%% (admin)</title>
	<content>

		<div class='block'>
			[[:theme::navtitlebox::label=Package::toggle=divDetails:]]
			<div id='divDetails'>
			[[:packages::showpackage::packageUID=%%packageUID%%:]]
			<hr/>
			</div>
			<div class='foot'></div>
		</div>
		<br/>

		<div class='block'>
			[[:theme::navtitlebox::label=Manifest::toggle=divManifest:]]
			<div id='divManifest'>
			<div class='spacer'></div>
			[[:packages::packagefilestatus::packageUID=%%UID%%:]]
			[[:packages::removepackageform::packageUID=%%UID%%:]]
			[[:packages::checkoutform::packageUID=%%UID%%:]]
			</div>
			<div class='foot'></div>
		</div>
		<br/>

		<div class='block'>
			[[:theme::navtitlebox::label=Developer Options::toggle=divEditPackage::hidden=yes:]]
			<div id='divEditPackage' style='visibility: hidden; display: none;'>
			[[:packages::editpackageform::packageUID=%%packageUID%%:]]
			</div>		
			<div class='foot'></div>
		</div>
		<br/>

</content>
	<nav1>
	[[:packages::commitbutton::packageUID=%%UID%%:]]
	[[:admin::subnav:]]
	</nav1>
	<nav2></nav2>
	<script></script>
	<jsinit></jsinit>
	<banner></banner>
	<head></head>
	<menu1>[[:home::menu:]]</menu1>
	<menu2>[[:admin::menu:]]</menu2>
	<section></section>
	<subsection></subsection>
	<breadcrumb>[[:theme::breadcrumb::label=Administration - ::link=/admin/:]]
[[:theme::breadcrumb::label=Packages::link=/packages/:]]</breadcrumb>
</page>

*/ ?>
