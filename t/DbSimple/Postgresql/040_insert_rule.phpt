--TEST--
PostgreSQL: returning result of RULE after INSERT

--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	@$DB->query("DROP RULE test_r ON test");
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id SERIAL, str VARCHAR(10))");
	$DB->query("CREATE RULE test_r AS ON INSERT TO test DO (SELECT 111 AS id)");
	printr($DB->query("INSERT INTO test(str) VALUES ('test')"), "Rule generated");
	printr($DB->query("SELECT * FROM test"), "Table content");
}
?>


--SKIPIF--
<?php
if (!is_callable('pg_connect')) print('skip pgsql extension not loaded');
?>


--EXPECT--
Query: 'DROP RULE test_r ON test'
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id SERIAL, str VARCHAR(10))'
Query: 'CREATE RULE test_r AS ON INSERT TO test DO (SELECT 111 AS id)'
Query: 'INSERT INTO test(str) VALUES (\'test\')'
Rule generated: array (
  0 => 
  array (
    'id' => '111',
  ),
)
Query: 'SELECT * FROM test'
Table content: array (
  0 => 
  array (
    'id' => '1',
    'str' => 'test',
  ),
)

