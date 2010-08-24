--TEST--
Generic: modify table test
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	require_once dirname(__FILE__) . '/../../Cache/TstCacher.php';
	$DB->setCacher(new TstCacher());
	$query = "-- CACHE: test.m
        SELECT * FROM test";
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id INTEGER, str VARCHAR(1), m VARCHAR(1))");
	$DB->query("INSERT INTO test(id, str, m) VALUES( 1, 'a', '0')");
	printr($DB->selectRow($query));
	$DB->query("UPDATE test SET str='b' WHERE id=1");
	printr($DB->selectRow($query));
	$DB->query("UPDATE test SET m='1' WHERE id=1");
	printr($DB->selectRow($query));
}

?>
--EXPECT--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id INTEGER, str VARCHAR(1), m VARCHAR(1))'
Query: 'INSERT INTO test(id, str, m) VALUES( 1, \'a\', \'0\')'
Query: '  -- UNIQ_KEY
SELECT MAX(test.m) AS M, COUNT(*) AS C FROM test'
Query: '-- CACHE: test.m
        SELECT * FROM test'
array (
  'id' => '1',
  'str' => 'a',
  'm' => '0',
)
Query: 'UPDATE test SET str=\'b\' WHERE id=1'
Query: '  -- UNIQ_KEY
SELECT MAX(test.m) AS M, COUNT(*) AS C FROM test'
Query: '-- CACHE: test.m
        SELECT * FROM test'
array (
  'id' => '1',
  'str' => 'a',
  'm' => '0',
)
Query: 'UPDATE test SET m=\'1\' WHERE id=1'
Query: '  -- UNIQ_KEY
SELECT MAX(test.m) AS M, COUNT(*) AS C FROM test'
Query: '-- CACHE: test.m
        SELECT * FROM test'
array (
  'id' => '1',
  'str' => 'b',
  'm' => '1',
)
