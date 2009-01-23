#!/usr/bin/env php
<?php
/*
 * The simpliest way to run this file is by executing it with the 'php' command
 * from your command-line interface. If you wish to use this on a regular basis
 * you may consider `chmod +x ruby.php` and linking the file elsewhere such as
 * /usr/local/bin (applicable to *nix variants only).
 */

require_once dirname(dirname(__FILE__)).'/lib/TryRuby.php';

$ruby = new TryRuby();
echo "Interactive Ruby ready.\n";

while (true) {
	echo '>> ';
	echo $ruby->send(fgets(STDIN)),"\n";
}
?>