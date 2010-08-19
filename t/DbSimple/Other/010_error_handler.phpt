--TEST--
Error handler call_user_func
--FILE--
<?php
require_once dirname(__FILE__) . '/../init_lasy.php';

function main(&$DB)
{
	$DB->select('error');
}

?>
--EXPECT--
Query: 'error'
Error: 'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'error\' at line 1'
Context: 'Other/010_error_handler.php line 6'
