<? /*
<module>
    <modulename>calendar</modulename>
    <version>1.0</version>
    <revision>0.</revision>
    <description>For creating, editing, displaying and summarising calendar entries.</description>
    <core>yes</core>
    <installed>no</installed>
    <enabled>yes</enabled>
    <dbschema></dbschema>
    <search>no</search>
    <dependencies>
		<module>images</module>
		<module>alias</module>
    </dependencies>
    <models>
        <model>
            <name>Entry</name>
            <description></description>
            <permissions>
                <permission>show</permission>
                <permission>edit</permission>
                <permission>delete</permission>
                <permission>new</permission>
            </permissions>
            <relationships>
                <relationship>creator</relationship>
            </relationships>
        </model>
    </models>
    <defaultpermissions>
		student:p|calendar|Calendar_Entry|new
		student:p|calendar|Calendar_Entry|show
		student:p|calendar|Calendar_Entry|images-show
		student:c|calendar|Calendar_Entry|edit|(if)|creator
		student:c|calendar|Calendar_Entry|delete|(if)|creator
		student:c|calendar|Calendar_Entry|images-add|(if)|creator
		student:c|calendar|Calendar_Entry|images-edit|(if)|creator
		student:c|calendar|Calendar_Entry|images-remove|(if)|creator

		teacher:p|calendar|Calendar_Entry|new
		teacher:p|calendar|Calendar_Entry|show
		teacher:p|calendar|Calendar_Entry|images-show
		teacher:c|calendar|Calendar_Entry|edit|(if)|creator
		teacher:c|calendar|Calendar_Entry|delete|(if)|creator
		teacher:c|calendar|Calendar_Entry|images-add|(if)|creator
		teacher:c|calendar|Calendar_Entry|images-edit|(if)|creator
		teacher:c|calendar|Calendar_Entry|images-remove|(if)|creator
    </defaultpermissions>

</module>
*/ ?>
