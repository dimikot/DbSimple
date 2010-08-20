--TEST--
Generic: identifier placeholderwith tablename
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
    @$DB->setIdentPrefix('pre_');
    @$DB->query("SELECT ?# FROM t1", array('a', 'b'));
    @$DB->query("SELECT ?# FROM t1", array('t1' => 'a', 'b'));

    @$DB->query('SELECT ?# FROM ?_t1', 'a');
    @$DB->query('SELECT ?# FROM ?_t1', array('a'));
    @$DB->query('SELECT ?# FROM ?_t1', array('a','b'));
    @$DB->query('SELECT ?# FROM t1', array('t1'=>'a'));
    @$DB->query('SELECT ?# FROM t1', array('t1'=>array('a','b')));
    @$DB->query('SELECT ?# FROM ?_t1', array('?_t1'=>array('a','b')));
    @$DB->query('SELECT ?# FROM ?_t1', array('?_t1'=>'*','?_t2'=>'c'));
    @$DB->query('SELECT ?# FROM ?#', array('t1'=>'*','t2'=>'c'),array('base'=>'t1'));
    @$DB->query('SELECT ?# FROM ?#', array('?_t1'=>'*','?_t2'=>'c'),array('base'=>'?_t1'));

}

?>
--EXPECT--
Query: 'SELECT `a`, `b` FROM t1'
Query: 'SELECT `t1`.`a`, `b` FROM t1'
Query: 'SELECT `a` FROM pre_t1'
Query: 'SELECT `a` FROM pre_t1'
Query: 'SELECT `a`, `b` FROM pre_t1'
Query: 'SELECT `t1`.`a` FROM t1'
Query: 'SELECT `t1`.`a`, `t1`.`b` FROM t1'
Query: 'SELECT `pre_t1`.`a`, `pre_t1`.`b` FROM pre_t1'
Query: 'SELECT `pre_t1`.*, `pre_t2`.`c` FROM pre_t1'
Query: 'SELECT `t1`.*, `t2`.`c` FROM `base`.`t1`'
Query: 'SELECT `pre_t1`.*, `pre_t2`.`c` FROM `base`.`pre_t1`'
