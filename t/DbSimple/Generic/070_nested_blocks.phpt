--TEST--
Generic: nested {}-blocks usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
    @$DB->query("SELECT * FROM t1 WHERE 1 { AND a = ?d } AND c = ?d", 1, 3);
    @$DB->query("SELECT * FROM t1 WHERE 1 { AND a = ?d } AND c = ?d", DBSIMPLE_SKIP, 3);
    @$DB->query("SELECT * FROM t1 WHERE 1 { AND a = ?d { AND b=?d } } AND c = ?d", 1, 2, 3);
    @$DB->query("SELECT * FROM t1 WHERE 1 { AND a = ?d { AND b=?d } } AND c = ?d", 1, DBSIMPLE_SKIP, 3);
    @$DB->query("SELECT * FROM t1 WHERE 1 { AND a = ?d { AND b=?d } } AND c = ?d", DBSIMPLE_SKIP, 2, 3);
}

?>
--EXPECT--
Query: 'SELECT * FROM t1 WHERE 1   AND a = 1   AND c = 3'
Query: 'SELECT * FROM t1 WHERE 1  AND c = 3'
Query: 'SELECT * FROM t1 WHERE 1   AND a = 1   AND b=2     AND c = 3'
Query: 'SELECT * FROM t1 WHERE 1   AND a = 1    AND c = 3'
Query: 'SELECT * FROM t1 WHERE 1  AND c = 3'
