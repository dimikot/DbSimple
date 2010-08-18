--TEST--
Generic: Cache_Lite usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	$query = "
        -- CACHE: 10
        SELECT * FROM test
        ";
        @$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id INTEGER, str VARCHAR(1))");
	$DB->query("INSERT INTO test(id, str) VALUES( 1, 'a')");
        printr($DB->selectRow($query));
        $DB->query("UPDATE test SET str='b' WHERE id=1");
	printr($DB->selectRow($query));
}

?>
--EXPECT--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id INTEGER, str VARCHAR(1))'
Query: 'INSERT INTO test(id, str) VALUES( 1, \'a\')'
Query: '
        -- CACHE: 10
        SELECT * FROM test
        '
array (
  'id' => '1',
  'str' => 'a',
)
Query: 'UPDATE test SET str=\'b\' WHERE id=1'
Query: '
        -- CACHE: 10
        SELECT * FROM test
        '
array (
  'id' => '1',
  'str' => 'a',
)