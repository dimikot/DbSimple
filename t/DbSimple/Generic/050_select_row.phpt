--TEST--
Generic: selectRow(); usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id INTEGER, str VARCHAR(1))");
	$DB->query("INSERT INTO test(id, str) VALUES( 1, 'a')");
	printr($DB->selectRow("SELECT * FROM test"));
	printr($DB->selectRow("SELECT str, id FROM test"));
}

?>
--EXPECT--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id INTEGER, str VARCHAR(1))'
Query: 'INSERT INTO test(id, str) VALUES( 1, \'a\')'
Query: 'SELECT * FROM test'
array (
  'id' => '1',
  'str' => 'a',
)
Query: 'SELECT str, id FROM test'
array (
  'str' => 'a',
  'id' => '1',
)