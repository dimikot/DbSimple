--TEST--
PostgreSQL: excaping test

--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	$DB->DbSimple_Postgresql_USE_NATIVE_PHOLDERS = false;
	printr($DB->query("select ? as a", "aaa\\"), "Result");
}
?>


--SKIPIF--
<?php
if (!is_callable('pg_connect')) print('skip pgsql extension not loaded');
?>


--EXPECT--
Query: 'select E\'aaa\\\\\' as a'
Result: array (
  0 => 
  array (
    'a' => 'aaa\\',
  ),
)
