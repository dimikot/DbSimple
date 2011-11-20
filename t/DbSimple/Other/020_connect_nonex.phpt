--TEST--
Connect to non existed DB
--FILE--
<?php ## Подключение к БД.
require_once dirname(__FILE__)."/../../../lib/config.php";
require_once "DbSimple/Generic.php";

// Подключаемся к БД.
$DATABASE = DbSimple_Generic::connect('mysql://test:test@localhost/non-existed-db');

// Устанавливаем обработчик ошибок.
$DATABASE->setErrorHandler('databaseErrorHandler');

// Код обработчика ошибок SQL.
function databaseErrorHandler($message, $info)
{
	// Если использовалась @, ничего не делать.
	if (!error_reporting()) return;
	$dir = dirname(__FILE__). '/';
	$rpath = str_replace($dir, '', $info['context']);
	echo "Error: ".$info['message']."\n";
	echo "Context: ".$rpath."\n";
	exit();
}
?>
--EXPECT--
Error: Access denied for user 'test'@'%' to database 'non-existed-db'
Context: 020_connect_nonex.php line 6
