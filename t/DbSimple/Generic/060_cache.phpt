--TEST--
Generic: Zend_Cache usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	require_once dirname(__FILE__) . '/../../Cache/TstCacher.php';
	$DB->setCacher($Cacher = new TstCacher());
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
	var_dump($Cacher->getAll());
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
array(1) {
  ["e8c047124bb1326506f7144011c8ebaf278351e552185ed67702f0c1326535c0"]=>
  string(152) "a:3:{s:10:"invalCache";N;s:6:"result";a:1:{i:0;a:2:{s:2:"id";s:1:"1";s:3:"str";s:1:"a";}}s:4:"rows";a:1:{i:0;a:2:{s:2:"id";s:1:"1";s:3:"str";s:1:"a";}}}"
}