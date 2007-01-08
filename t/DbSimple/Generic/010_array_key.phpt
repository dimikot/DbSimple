--TEST--
Generic: ARRAY_KEY* usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id INTEGER, pid INTEGER, str VARCHAR(1))");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(100, 10, 'a')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(101, 10, 'b')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(200, 20, 'x')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(201, 20, 'y')");
	printr($DB->select("SELECT id AS ARRAY_KEY, str FROM test ORDER BY id"));
	printr($DB->select("SELECT id AS ARRAY_KEY_2, pid AS ARRAY_KEY_1, str FROM test ORDER BY id"));
	printr($DB->select("SELECT NULL AS ARRAY_KEY_2, pid AS ARRAY_KEY_1, str FROM test ORDER BY id"));
}

?>


--EXPECT--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id INTEGER, pid INTEGER, str VARCHAR(1))'
Query: 'INSERT INTO test(id, pid, str) VALUES(100, 10, \'a\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(101, 10, \'b\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(200, 20, \'x\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(201, 20, \'y\')'
Query: 'SELECT id AS ARRAY_KEY, str FROM test ORDER BY id'
array (
  100 => 
  array (
    'str' => 'a',
  ),
  101 => 
  array (
    'str' => 'b',
  ),
  200 => 
  array (
    'str' => 'x',
  ),
  201 => 
  array (
    'str' => 'y',
  ),
)
Query: 'SELECT id AS ARRAY_KEY_2, pid AS ARRAY_KEY_1, str FROM test ORDER BY id'
array (
  10 => 
  array (
    100 => 
    array (
      'str' => 'a',
    ),
    101 => 
    array (
      'str' => 'b',
    ),
  ),
  20 => 
  array (
    200 => 
    array (
      'str' => 'x',
    ),
    201 => 
    array (
      'str' => 'y',
    ),
  ),
)
Query: 'SELECT NULL AS ARRAY_KEY_2, pid AS ARRAY_KEY_1, str FROM test ORDER BY id'
array (
  10 => 
  array (
    0 => 
    array (
      'str' => 'a',
    ),
    1 => 
    array (
      'str' => 'b',
    ),
  ),
  20 => 
  array (
    0 => 
    array (
      'str' => 'x',
    ),
    1 => 
    array (
      'str' => 'y',
    ),
  ),
)
