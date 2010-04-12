--TEST--
Postgresql: native placeholders support

--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	$query = array("INSERT INTO test(id, pid, str) VALUES(?, ?, ?)", 10, 101, 'a');
	$DB->_expandPlaceholders($query, true);
	printr($query, "With native placeholders");
	
	$query = array("INSERT INTO test(id, pid, str) VALUES(?, ?, ?)", 10, 101, 'a');
	$DB->_expandPlaceholders($query, false);
	printr($query, "Without native placeholders");
}
?>


--SKIPIF--
<?php
if (!is_callable('pg_connect')) print('skip pgsql extension not loaded');
?>


--EXPECT--
With native placeholders: array (
  0 => 'INSERT INTO test(id, pid, str) VALUES($1, $2, $3)',
  1 => 10,
  2 => 101,
  3 => 'a',
)
Without native placeholders: array (
  0 => 'INSERT INTO test(id, pid, str) VALUES(E\'10\', E\'101\', E\'a\')',
)

