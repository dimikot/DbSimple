--TEST--
Generic: identifier placeholderwith tablename
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
    @$DB->query("SELECT ?# FROM t1", array('a', 'b'));
    @$DB->query("SELECT ?# FROM t1", array('t1' => 'a', 'b'));
}

?>
--EXPECT--
Query: 'SELECT `a`, `b` FROM t1'
Query: 'SELECT `t1`.`a`, `b` FROM t1'
