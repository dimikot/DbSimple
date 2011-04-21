--TEST--
Set prefixes
--FILE--
<?php
require_once dirname(__FILE__) . '/../init_lasy.php';

function main(&$DB)
{
	$DB->setIdentPrefix('as_');
	$DB->setCachePrefix('db_');
	require_once dirname(__FILE__) . '/../../Cache/TstCacher.php';
	$DB->setCacher($Cacher = new TstCacher());
	$query = "
        -- CACHE: 10
        SELECT * FROM ?_test
        ";
	@$DB->query("DROP TABLE ?_test");
	$DB->query("CREATE TABLE ?_test(id INTEGER, str VARCHAR(10))");
	$DB->query("INSERT INTO ?_test(id, str) VALUES( 1, 'a')");
	printr($DB->selectRow($query));
	$DB->query("UPDATE ?_test SET str='b' WHERE id=1");
	printr($DB->selectRow($query));
	var_dump($Cacher->getAll());
}

?>
--EXPECT--
Query: 'DROP TABLE as_test'
Query: 'CREATE TABLE as_test(id INTEGER, str VARCHAR(10))'
Query: 'INSERT INTO as_test(id, str) VALUES( 1, \'a\')'
Query: '
        -- CACHE: 10
        SELECT * FROM as_test
        '
array (
  'id' => '1',
  'str' => 'a',
)
Query: 'UPDATE as_test SET str=\'b\' WHERE id=1'
Query: '
        -- CACHE: 10
        SELECT * FROM as_test
        '
array (
  'id' => '1',
  'str' => 'a',
)
array(1) {
  ["db_71ccc35426176e29ea1ce9ffac97041b"]=>
  string(152) "a:3:{s:10:"invalCache";N;s:6:"result";a:1:{i:0;a:2:{s:2:"id";s:1:"1";s:3:"str";s:1:"a";}}s:4:"rows";a:1:{i:0;a:2:{s:2:"id";s:1:"1";s:3:"str";s:1:"a";}}}"
}
