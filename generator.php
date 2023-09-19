<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
 
/**
	The user data that is entered into the user import xml files.  See userXMLTemplate.php  
*/	
$userToImport = array(
'username'=>'ojs_username',
'userPassword'=>'secret',
'email'=>'some@email_address.com',
'givenName' => 'FirstName',
'familyName' => 'LastName',
'affiliation' => 'Some Affiliation'
);

/**
	$directory is the directory of where all the OJS instances are installed
	It is assumed that the config.inc.php file is another directory level below $directory.
	example:
	$directory/ojs_instance/config.inc.php
	Make sure there is a backslash at the end of the path.
*/	
$directory = '/path/to/ojs/instances/';
// enter in the full path of the directories that should not be included in the process
$skipDirectories = array('..','.', $directory . 'directory_to_skip_1', $directory . 'directory_to_skip_2');

// directory where the user import xml files are written.
$filesOutputDir = 'xml';
// The file where the import commands are written too.
$outputCommandFileName = 'COMMANDS.txt';




$dirList =  array_filter(glob($directory . '*'), 'is_dir');
$dirList = array_diff($dirList, $skipDirectories);

$commandAppend = '';

foreach($dirList as $dir)
{
	$config = parse_ini_file( $dir . '/config.inc.php'  );
	
	
	$host = $config['host'];
	$dbname = $config['name'];
	$dbusername = $config['username'];
	$dbpassword = $config['password'];
	
	$dbh = new PDO("mysql:host={$host};dbname={$dbname}", $dbusername, $dbpassword, array( PDO::ATTR_PERSISTENT => false));
	$stmt = $dbh->prepare("Select journal_id, path from journals");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);	
	
	$stmt = $dbh->prepare("Select username FROM users WHERE email = :email");
	$stmt->bindValue(":email",$userToImport['email']);
	$stmt->execute();	
	$user =  $stmt->fetch(PDO::FETCH_ASSOC);
	$emailCount = $stmt->rowCount();
	
	// kill the PDO object
	$dbh = null;
	
	foreach($result as $journal)
	{
		
		$path = $journal['path'];
		$journal_id = $journal['journal_id'];
		$command='';
		
		$userXML = getXML($journal_id, $userToImport);
		
		// Add a note in the command file if the user already exists
		if($emailCount > 0){
			$command .= '# NOTE: ' . $userToImport['email'] . ' already exists for ' . $user['username'] . "\n";
		}
		 
		$xmlFileLocation = realpath($filesOutputDir);
		// if there are duplicate paths across the different journals, append a number after the file name so it doesn't get overwritten.
		$append = '';
		$x = 0;
		if(file_exists($xmlFileLocation . "/userImport_{$path}{$append}.xml")){
			do {		
				$x += 1;
				$append = '_' . $x;
			} while (file_exists($xmlFileLocation . "/userImport_{$path}{$append}.xml"));
		}
		
		$xmlfile = fopen("./xml/userImport_{$path}{$append}.xml", "w");
		fwrite($xmlfile, $userXML);
		fclose($xmlfile);	
		
		$command .= 'php ' . $dir . '/tools/importExport.php UserImportExportPlugin import ' . $xmlFileLocation . "/userImport_{$path}{$append}.xml " . $path . "\n";
		//echo $command;
		
		$commandAppend .= $command;
		
	}
	
	$commandsFile = fopen($outputCommandFileName, "w");
	fwrite($commandsFile, $commandAppend);
	fclose($commandsFile);	
	
	
}


function getXML($journal_id ,$userToImport)
{
	
	extract($userToImport);
	
	require('userXMLTemplate.php');

	return $userXml;
}




