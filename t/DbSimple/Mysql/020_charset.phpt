--TEST--
Mysql: ?# placeholder usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
	$row = array(
		'id'  => 1,
		'str' => 'test'
	);
	
	printr($DB->select("SHOW VARIABLES LIKE 'character_set_client'"));
}
?>


--EXPECT--
Query: 'SHOW VARIABLES LIKE \'character_set_client\''
array (
  0 => 
  array (
    'Variable_name' => 'character_set_client',
    'Value' => 'utf8',
  ),
)

