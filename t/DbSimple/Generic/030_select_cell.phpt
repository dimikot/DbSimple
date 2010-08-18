--TEST--
Generic: selectCell() usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id INTEGER, str VARCHAR(1))");
	$DB->query("INSERT INTO test(id, str) VALUES( 1, 'a')");
	printr($DB->selectCell("SELECT id FROM test"));
	printr($DB->selectCell("SELECT str FROM test"));
	printr($DB->selectCell("SELECT id, str FROM test"));

}

?>
--EXPECT--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id INTEGER, str VARCHAR(1))'
Query: 'INSERT INTO test(id, str) VALUES( 1, \'a\')'
Query: 'SELECT id FROM test'
'1'
Query: 'SELECT str FROM test'
'a'
Query: 'SELECT id, str FROM test'
'1'