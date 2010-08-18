--TEST--
Generic: selectCol() usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id INTEGER, str VARCHAR(1))");
	$DB->query("INSERT INTO test(id, str) VALUES( 1, 'a')");
	$DB->query("INSERT INTO test(id, str) VALUES( 2, 'b')");
	$DB->query("INSERT INTO test(id, str) VALUES( 3, 'c')");
	$DB->query("INSERT INTO test(id, str) VALUES( 4, 'd')");
	printr($DB->selectCol("SELECT str FROM test"));
	printr($DB->selectCol("SELECT str, id FROM test"));
	printr($DB->selectCol("SELECT str, id FROM test WHERE 1=0"));
}

?>
--EXPECT--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id INTEGER, str VARCHAR(1))'
Query: 'INSERT INTO test(id, str) VALUES( 1, \'a\')'
Query: 'INSERT INTO test(id, str) VALUES( 2, \'b\')'
Query: 'INSERT INTO test(id, str) VALUES( 3, \'c\')'
Query: 'INSERT INTO test(id, str) VALUES( 4, \'d\')'
Query: 'SELECT str FROM test'
array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
  3 => 'd',
)
Query: 'SELECT str, id FROM test'
array (
  0 => 'a',
  1 => 'b',
  2 => 'c',
  3 => 'd',
)
Query: 'SELECT str, id FROM test WHERE 1=0'
array (
)
