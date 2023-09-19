# ojsUserImportGenerator
Generates user files for import across many ojs databases.  It's usefull if your run many ojs installations and need a quick way to create user across all of them.
The script has been tested on OJS version 3.3

## Summary
The script is meant to loop through all the ojs installations and get the database credentials from the ojs config.inc.php file.  
It will then query the ojs databases and pull the required data to create a user import xml file  and generate the command line to run that xml file.
By default the xml file that is generated will give journal manager and copy editor permissions (roles) to the user entered into the generator.php file.  Edit the userXMLTemplate.php file to change those permissions.

The user import xml files are stored in the xml directory and the ojs commands to run the xml files are written to a COMMANDS.txt file by default.

## Usage
Enter in your desired ojs username/password, email, given name, family name and affiliation in the `$userToImport` array at the top of generator.php
Enter in the parent directory of all the ojs installations in the `$directory` variable in generator.php
Enter in the child directories that you would want to skip or directories that do not have a config.inc.php file in them into the `$skipDirectories` array.

Run
`php generator.php`

You can then take the commands that it wrote in the COMMANDS.txt file and run them as you see fit. It will add a note above each command if the email address choosen already exists in the database.
