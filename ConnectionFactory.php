<?php
class ConnectionFactory {
    public static $db;

    public static function makeConnection(array $conf, $params = array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT=>true, PDO::ATTR_EMULATE_PREPARES=>false, PDO::ATTR_STRINGIFY_FETCHES=>false)) {
        $dsn = "mysql:host=".$conf['host'].";dbname=".$conf['database'];
        self::$db = new \PDO($dsn, $conf['username'], $conf['password'], $params);
        return self::$db;
    }

    public static function getConnection() {
        if (isset(self::$db)) return self::$db;
    }
}