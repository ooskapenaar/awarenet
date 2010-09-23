<? /*
<module>
    <modulename>announcements</modulename>
    <version>1.0</version>
    <revision>0</revision>
    <description>Announcements module, dependant on other modules (schools, groups, etc make announcements).</description>
    <core>yes</core>
    <installed>no</installed>
    <enabled>no</enabled>
    <dbschema></dbschema>
    <search>no</search>
    <dependancy>
    </dependancy>
	<models>
		<model>
			<name>Announcements_Announcement</name>
			<description></description>
			<permissions>
				<permission>new</permission>
				<permission>show</permission>
				<permission>list</permission>
				<permission>delete</permission>
				<export>announcement-add</export>
				<export>announcement-edit</export>
				<export>announcement-show</export>
				<export>announcement-delete</export>
			</permissions>
			<relationships>
				<relationship>creator</relationship>
			</relationships>
		</model>
	</models>
    <defaultpermissions>
		student:p|announcements|Announcements_Announcement|show
		student:p|announcements|Announcements_Announcement|list
		student:c|announcements|Announcements_Announcement|edit|(if)|creator

		teacher:p|announcements|Announcements_Announcement|announcement-add
		teacher:c|announcements|Announcements_Announcement|announcement-edit|(if)|creator
		teacher:c|announcements|Announcements_Announcement|announcement-delete|(if)|creator
    </defaultpermissions>
    <blocks>
    </blocks>
</module>
*/ ?>
