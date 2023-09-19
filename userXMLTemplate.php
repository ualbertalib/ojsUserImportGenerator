<?php
	
$userXml = <<<END
<?xml version="1.0"?>
     <PKPUsers xmlns="http://pkp.sfu.ca" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pkp.sfu.ca pkp-users.xsd">
    <user_groups xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pkp.sfu.ca pkp-users.xsd">
\n
END;


	$userXml .=	"<user_group>
			<role_id>16</role_id>	
			<context_id>{$journal_id}</context_id>
			<is_default>true</is_default>
			<show_title>false</show_title>
			<permit_self_registration>false</permit_self_registration>
			<permit_metadata_edit>true</permit_metadata_edit>			
			<name locale=\"en_US\">Journal manager</name>			
			<abbrev locale=\"en_US\">JM</abbrev>
			<stage_assignments>1:3:4:5</stage_assignments>
		</user_group>		
		<user_group>
			<role_id>4097</role_id>
			<context_id>{$journal_id}</context_id>
			<is_default>true</is_default>
			<show_title>false</show_title>
			<permit_self_registration>false</permit_self_registration>
			<permit_metadata_edit>false</permit_metadata_edit>
			<name locale=\"en_US\">Copyeditor</name>
			<abbrev locale=\"en_US\">CE</abbrev>
			<stage_assignments>1:3:4:5</stage_assignments>
		</user_group>
	</user_groups>";

$userXml .=	<<<END
     <users>
		<user>
			<givenname locale="en_US">{$givenName}</givenname>
			<familyname locale="en_US">{$familyName}</familyname>
			<affiliation locale="en_US">{$affiliation}</affiliation>
			<country>CA</country>
			<email>{$email}</email>
			
			<username>{$username}</username>
			<password is_disabled="false" must_change="false">
				<value>{$userPassword}</value>
			</password>
			
			<locales>en_US</locales>
			<user_group_ref>Journal manager</user_group_ref>
			<user_group_ref>Journal editor</user_group_ref>
			<user_group_ref>Section editor</user_group_ref>
			<user_group_ref>Author</user_group_ref>
			<user_group_ref>Reviewer</user_group_ref>
			<user_group_ref>Reader</user_group_ref>
			
		</user>
	</users>
</PKPUsers>
\n
END;



