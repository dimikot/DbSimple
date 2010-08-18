--TEST--
PostgreSQL: returning of last inserted ID

--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id SERIAL, str VARCHAR(10)) WITH OIDS");
	printr($DB->query("INSERT INTO test(str) VALUES ('test')"), "ID");
	printr($DB->select("SELECT * FROM test"), "Result");	
	printr($DB->select("SELECT 1 AS a"), "Result");	
}
?>


--SKIPIF--
<?php
if (!is_callable('pg_connect')) print('skip pgsql extension not loaded');
?>


--EXPECTF--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id SERIAL, str VARCHAR(10)) WITH OIDS'
Query: 'INSERT INTO test(str) VALUES (\'test\')'
ID: '%d'
Query: 'SELECT * FROM test'
Result: array (
  0 => 
  array (
    'id' => '1',
    'str' => 'test',
  ),
)
Query: 'SELECT 1 AS a'
Result: array (
  0 => 
  array (
    'a' => '1',
  ),
)
