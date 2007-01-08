<?php ## Подключение к БД.
require_once "../../lib/config.php";
require_once "DbSimple/DSN.php";

// Получаем пароль для доступа к БД из внешнего файла, 
// чтобы не подсматривали.
@include "{$_SERVER['DOCUMENT_ROOT']}/../forum/dbconfig.php";
if (!empty($dbpasswd)) {
    // Это параметры подключения для dklab.
    $dsn = "mysql://$dbuser:$dbpasswd@localhost/$dbname";
} else {
    // А это - для вас. Вы же не dklab...
    $dsn = "mysql://Логин:Пароль@Хост/База";
} 

// Подключаемся к БД.
$DATABASE = DbSimple_DSN::connect($dsn);

// Устанавливаем обработчик ошибок.
$DATABASE->set_error_handler('databaseErrorHandler');

// Стартуем новую транзакцию.
$DB = $DATABASE->transaction();

// Код обработчика ошибок SQL.
function databaseErrorHandler($message, $info)
{
	// Если использовалась @, ничего не делать.
	if (!error_reporting()) return;
	// Выводим подробную информацию об ошибке.
	echo "SQL Error: $message<br><pre>"; 
	print_r($info);
	echo "</pre>";
	exit();
}
?>
