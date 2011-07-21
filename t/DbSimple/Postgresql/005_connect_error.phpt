--TEST--
Postgresql: connection error catching

--FILE--
<?php
$DSN[] = "postgresql://user:pass@127.0.0.1:55555/test";
require_once dirname(__FILE__) . '/../init.php';

function main(&$DB)
{
}
?>


--SKIPIF--
<?php
if (!is_callable('pg_connect')) print('skip pgsql extension not loaded');
?>


--EXPECT--
Error: 'pg_connect("host=127.0.0.1 port=55555 dbname=test user=user") error'
