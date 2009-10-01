<? /*
<module>
    <modulename>forums</modulename>
    <version>1.0</version>
    <revision>0</revision>
    <description>School and general forums.</description>
    <core>no</core>
    <installed>no</installed>
    <enabled>yes</enabled>
    <dbschema></dbschema>
    <search>no</search>
    <dependancy>
    </dependancy>
    <permissions>
        <perm>new|%%user.ofGroup%%=admin</perm>
        <perm>new|%%user.ofGroup%%=teacher</perm>
        <perm>edit|%%user.ofGroup%%=student</perm>
        <perm>edit|%%user.ofGroup%%=teacher</perm>
        <perm>show|%%user.ofGroup%%=student</perm>
        <perm>show|%%user.ofGroup%%=teacher</perm>
        <perm>post|%%user.ofGroup%%=student</perm>
        <perm>post|%%user.ofGroup%%=teacher</perm>
        <perm>imageupload|%%user.ofGroup%%=student</perm>
        <perm>imageupload|%%user.ofGroup%%=teacher</perm>
        <perm>images|%%user.ofGroup%%=student</perm>
        <perm>images|%%user.ofGroup%%=teacher</perm>
    </permissions>
    <blocks>
    </blocks>
</module>
*/ ?>
