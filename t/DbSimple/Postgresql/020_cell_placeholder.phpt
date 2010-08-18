--TEST--
PostgreSQL: ?# placeholder usage

--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	$row = array(
		'id'  => 1,
		'str' => 'test'
	);
	
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id INTEGER, str VARCHAR(10))");
	$DB->query("INSERT INTO test(?#) VALUES(?a)", array_keys($row), array_values($row));
	printr($DB->selectCol("SELECT ?# FROM test", 'id'));
}
?>


--SKIPIF--
<?php
if (!is_callable('pg_connect')) print('skip pgsql extension not loaded');
?>


--EXPECT--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id INTEGER, str VARCHAR(10))'
Query: 'INSERT INTO test("id", "str") VALUES(E\'1\', E\'test\')'
Query: 'SELECT "id" FROM test'
array (
  0 => '1',
)

