--TEST--
Generic: ARRAY_KEY/PARENT_KEY case insensitivity
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	@$DB->query("DROP TABLE test");
	$DB->query("CREATE TABLE test(id INTEGER, pid INTEGER, str VARCHAR(1))");
	$DB->query("INSERT INTO test(id, pid, str) VALUES( 1, NULL, 'a')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES( 2, 1,    'b')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES( 3, 1,    'c')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES( 4, 1,    'd')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES( 5, 2,    'e')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES( 6, 2,    'f')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES( 7, 2,    'g')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES( 8, 3,    'h')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES( 9, 3,    'i')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(10, 3,    'j')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(11, 4,    'k')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(12, 4,    'l')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(13, 4,    'm')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(14, 5,    'n')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(15, 5,    'o')");
	$DB->query("INSERT INTO test(id, pid, str) VALUES(16, 5,    'p')");
	printr($DB->select("SELECT id AS array_key, pid AS Parent_Key, str FROM test"));
}

?>

--EXPECT--
Query: 'DROP TABLE test'
Query: 'CREATE TABLE test(id INTEGER, pid INTEGER, str VARCHAR(1))'
Query: 'INSERT INTO test(id, pid, str) VALUES( 1, NULL, \'a\')'
Query: 'INSERT INTO test(id, pid, str) VALUES( 2, 1,    \'b\')'
Query: 'INSERT INTO test(id, pid, str) VALUES( 3, 1,    \'c\')'
Query: 'INSERT INTO test(id, pid, str) VALUES( 4, 1,    \'d\')'
Query: 'INSERT INTO test(id, pid, str) VALUES( 5, 2,    \'e\')'
Query: 'INSERT INTO test(id, pid, str) VALUES( 6, 2,    \'f\')'
Query: 'INSERT INTO test(id, pid, str) VALUES( 7, 2,    \'g\')'
Query: 'INSERT INTO test(id, pid, str) VALUES( 8, 3,    \'h\')'
Query: 'INSERT INTO test(id, pid, str) VALUES( 9, 3,    \'i\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(10, 3,    \'j\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(11, 4,    \'k\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(12, 4,    \'l\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(13, 4,    \'m\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(14, 5,    \'n\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(15, 5,    \'o\')'
Query: 'INSERT INTO test(id, pid, str) VALUES(16, 5,    \'p\')'
Query: 'SELECT id AS array_key, pid AS Parent_Key, str FROM test'
array (
  1 => 
  array (
    'str' => 'a',
    'childNodes' => 
    array (
      2 => 
      array (
        'str' => 'b',
        'childNodes' => 
        array (
          5 => 
          array (
            'str' => 'e',
            'childNodes' => 
            array (
              14 => 
              array (
                'str' => 'n',
                'childNodes' => 
                array (
                ),
              ),
              15 => 
              array (
                'str' => 'o',
                'childNodes' => 
                array (
                ),
              ),
              16 => 
              array (
                'str' => 'p',
                'childNodes' => 
                array (
                ),
              ),
            ),
          ),
          6 => 
          array (
            'str' => 'f',
            'childNodes' => 
            array (
            ),
          ),
          7 => 
          array (
            'str' => 'g',
            'childNodes' => 
            array (
            ),
          ),
        ),
      ),
      3 => 
      array (
        'str' => 'c',
        'childNodes' => 
        array (
          8 => 
          array (
            'str' => 'h',
            'childNodes' => 
            array (
            ),
          ),
          9 => 
          array (
            'str' => 'i',
            'childNodes' => 
            array (
            ),
          ),
          10 => 
          array (
            'str' => 'j',
            'childNodes' => 
            array (
            ),
          ),
        ),
      ),
      4 => 
      array (
        'str' => 'd',
        'childNodes' => 
        array (
          11 => 
          array (
            'str' => 'k',
            'childNodes' => 
            array (
            ),
          ),
          12 => 
          array (
            'str' => 'l',
            'childNodes' => 
            array (
            ),
          ),
          13 => 
          array (
            'str' => 'm',
            'childNodes' => 
            array (
            ),
          ),
        ),
      ),
    ),
  ),
)

