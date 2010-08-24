--TEST--
Generic: subQuery function
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
    $q=$DB->subquery('SELECT * FROM ?_t1 WHERE a=?','1');
    @$DB->query('?s AND b=?',$q,1);
    @$DB->query('SELECT * FROM t1 WHERE a IN (?a)',array($DB->subquery('MD5(?)',1)));
    @$DB->query('SELECT ?# FROM t1',array($DB->subquery('sum(?#)',array('t1'=>'f1')) ) );
}

?>
--EXPECT--
Query: 'SELECT * FROM t1 WHERE a=\'1\' AND b=\'1\''
Query: 'SELECT * FROM t1 WHERE a IN (MD5(\'1\'))'
Query: 'SELECT sum(`t1`.`f1`) FROM t1'
