--TEST--
Generic: nested {? }-blocks usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
    @$DB->query('SELECT * FROM t1 {?WHERE a=1}',1);
    @$DB->query('SELECT * FROM t1 {?WHERE a=1}',DBSIMPLE_SKIP);
}

?>
--EXPECT--
Query: 'SELECT * FROM t1   WHERE a=1 '
Query: 'SELECT * FROM t1 '
