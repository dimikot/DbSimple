--TEST--
PostgreSQL: connect twice even with same DSNs

--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	global $DSN;
	$db2 = DbSimple_Generic::connect($DSN[0]);
	if ($DB->link != $db2->link) echo "OK\n"; 
	else echo "Same link is established for same DSNs: {$DB->link}\n";
}
?>


--SKIPIF--
<?php
if (!is_callable('pg_connect')) print('skip pgsql extension not loaded');
?>


--EXPECT--
OK
