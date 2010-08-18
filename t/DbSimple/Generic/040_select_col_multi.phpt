--TEST--
Generic: selectCol() usage with multi-dimensional array
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id1 INTEGER, id2 INTEGER, str VARCHAR(1))");
	$DB->query("INSERT INTO test VALUES( 1, 10, 'a')");
	$DB->query("INSERT INTO test VALUES( 2, 20, 'b')");
	$DB->query("INSERT INTO test VALUES( 2, 30, 'c')");
	$DB->query("INSERT INTO test VALUES( 4, 40, 'd')");
	printr($DB->selectCol("SELECT id1 AS ARRAY_KEY_1, str FROM test"));
	printr($DB->selectCol("SELECT id1 AS ARRAY_KEY_1, id2 AS ARRAY_KEY_2, str FROM test"));
}

?>
--EXPECT--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id1 INTEGER, id2 INTEGER, str VARCHAR(1))'
Query: 'INSERT INTO test VALUES( 1, 10, \'a\')'
Query: 'INSERT INTO test VALUES( 2, 20, \'b\')'
Query: 'INSERT INTO test VALUES( 2, 30, \'c\')'
Query: 'INSERT INTO test VALUES( 4, 40, \'d\')'
Query: 'SELECT id1 AS ARRAY_KEY_1, str FROM test'
array (
  1 => 'a',
  2 => 'c',
  4 => 'd',
)
Query: 'SELECT id1 AS ARRAY_KEY_1, id2 AS ARRAY_KEY_2, str FROM test'
array (
  1 => 
  array (
    10 => 'a',
  ),
  2 => 
  array (
    20 => 'b',
    30 => 'c',
  ),
  4 => 
  array (
    40 => 'd',
  ),
)
