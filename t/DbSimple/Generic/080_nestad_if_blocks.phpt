--TEST--
Generic: nested {|}-blocks usage
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
    @$DB->query("SELECT str FROM test WHERE 0 { OR id = ?d | OR id = ?d }", 1, 3);
    @$DB->query("SELECT str FROM test WHERE 0 { OR id = ?d | OR id = ?d }", DBSIMPLE_SKIP, 3);
    @$DB->query("SELECT str FROM test WHERE 0 { OR id = ?d | OR id = ?d { OR id = ?d | OR id = ?d }}", DBSIMPLE_SKIP, 2, 3, 4);
    @$DB->query("SELECT str FROM test WHERE 0 { OR id = ?d | OR id = ?d { OR id = ?d | OR id = ?d }}", DBSIMPLE_SKIP, 3, DBSIMPLE_SKIP, 4);
    @$DB->query("SELECT str FROM test WHERE 0 { OR id = ?d | OR id = ?d {{ OR id = ?d } | {OR id = ?d }}}", DBSIMPLE_SKIP, 3, DBSIMPLE_SKIP, 4);
    @$DB->query("SELECT str FROM test WHERE 0 { { OR id = ?d | OR id = ?d } OR id = ?d | OR id = ?d }", DBSIMPLE_SKIP, 3, DBSIMPLE_SKIP, 4);
    @$DB->query("SELECT str FROM test WHERE 0 { { OR id = ?d | OR id = ?d } OR id = ?d | OR id = ?d }", DBSIMPLE_SKIP, 3, 2, 4);
}

?>
--EXPECT--
Query: 'SELECT str FROM test WHERE 0   OR id = 1  '
Query: 'SELECT str FROM test WHERE 0   OR id = 3  '
Query: 'SELECT str FROM test WHERE 0   OR id = 2   OR id = 3   '
Query: 'SELECT str FROM test WHERE 0   OR id = 3   OR id = 4   '
Query: 'SELECT str FROM test WHERE 0   OR id = 3     '
Query: 'SELECT str FROM test WHERE 0   OR id = 4  '
Query: 'SELECT str FROM test WHERE 0     OR id = 3   OR id = 2  '
