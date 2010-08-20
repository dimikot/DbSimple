--TEST--
Generic: array placeholder
--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
    @$DB->setIdentPrefix('pre_');

    @$DB->query('SELECT * FROM t1 WHERE 1=1{ AND a IN (?a)}',array());
    @$DB->query('SELECT * FROM ?_t1 WHERE a IN (?a)', array('1','2','3'));
    @$DB->query('UPDATE ?_t1 SET ?a WHERE a IN (?a)', array('a'=>'1'), array('1','2','3'));
    @$DB->query('UPDATE ?_t1 SET ?a WHERE a IN (?a)', array('a'=>'1', 'b'=>2), array('1','2','3'));
    @$DB->query('UPDATE ?_t1 SET ?a WHERE a IN (?a)', array(
        '?_t' => array('a' => 1, 'b' => 2),
        't2' => array('a' => 3)
    ), array('1','2','3'));
    @$DB->query('INSERT INTO t1(a,b,c) VALUES (?a)',array(
        array('a'=>1,2,3),
        array(4,'b'=>5,6),
        array(7,8,'c'=>9),
    ));
    @$DB->query('SELECT * FROM t1 WHERE (?&)',array(
        array('a'=>1, 'b'=>2),
        array('c'=>3, 'd'=>4),
    ));
    @$DB->query('SELECT * FROM t1 WHERE (?|)',array(
        'a'=>1,
        array('a'=>1, 'b'=>2),
        array('c'=>3, 'd'=>4),
    ));
    @$DB->query('SELECT * FROM t1 WHERE (?|)', array('a'=>1, 'b'=>2));
    @$DB->query('SELECT * FROM t1 WHERE (?&)', array('a'=>1, 'b'=>2));
}

?>
--EXPECT--
Query: 'SELECT * FROM t1 WHERE 1=1'
Query: 'SELECT * FROM pre_t1 WHERE a IN (\'1\', \'2\', \'3\')'
Query: 'UPDATE pre_t1 SET `a`=\'1\' WHERE a IN (\'1\', \'2\', \'3\')'
Query: 'UPDATE pre_t1 SET `a`=\'1\', `b`=\'2\' WHERE a IN (\'1\', \'2\', \'3\')'
Query: 'UPDATE pre_t1 SET `pre_t`.`a`=\'1\', `pre_t`.`b`=\'2\', `t2`.`a`=\'3\' WHERE a IN (\'1\', \'2\', \'3\')'
Query: 'INSERT INTO t1(a,b,c) VALUES (\'1\', \'2\', \'3\'), (\'4\', \'5\', \'6\'), (\'7\', \'8\', \'9\')'
Query: 'SELECT * FROM t1 WHERE (`a`=\'1\' OR `b`=\'2\') AND (`c`=\'3\' OR `d`=\'4\')'
Query: 'SELECT * FROM t1 WHERE (`a`=\'1\') OR (`a`=\'1\' AND `b`=\'2\') OR (`c`=\'3\' AND `d`=\'4\')'
Query: 'SELECT * FROM t1 WHERE (`a`=\'1\') OR (`b`=\'2\')'
Query: 'SELECT * FROM t1 WHERE (`a`=\'1\') AND (`b`=\'2\')'
