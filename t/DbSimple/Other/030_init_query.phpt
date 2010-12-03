--TEST--
Init query on connect
--FILE--
<?php
require_once dirname(__FILE__) . '/../init_lasy.php';

function main(&$DB)
{
	$DB->addInit('SET time_zone = ?', '+04:00');
	echo "test\n";
	var_dump($DB->selectRow('SHOW VARIABLES like ?', 'time_zone'));
}

?>
--EXPECT--
test
Query: 'SET time_zone = \'+04:00\''
Query: 'SHOW VARIABLES like \'time_zone\''
array(2) {
  ["Variable_name"]=>
  string(9) "time_zone"
  ["Value"]=>
  string(6) "+04:00"
}
