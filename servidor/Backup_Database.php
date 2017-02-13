<?php
/**
 * This file contains the Backup_Database class wich performs
 * a partial or complete backup of any given MySQL database
 * @author Daniel López Azaña <http://www.azanweb.com-->
 * @version 1.1 updated 27.jan.2016 by Diego Soto twitter: @diesoto (Thanks to Daniel and all)
 *
 */

// Report all errors
error_reporting(E_ALL);

/**
 * Define database parameters here
 */
define("DB_USER", 'root');
define("DB_PASSWORD", '');
define("DB_NAME", 'gestion_procuraduria_2016_08072016');
define("DB_HOST", 'localhost');
define("OUTPUT_DIR", 'c:/backups'); // DO NOT FORGET to set your destination folder
define("TABLES", '*');

/**
 * Default time zone
 *
 * List of Supported Timezones: http://php.net/manual/en/timezones.php
 */
date_default_timezone_set('America/Caracas');

/**
 * Instantiate Backup_Database and perform backup
 */
$backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$status         = $backupDatabase->backupTables(TABLES, OUTPUT_DIR) ? 'OK' : 'KO';
echo "


Backup result: " . $status;

/**
 * The Backup_Database class
 */
class Backup_Database
{
    /**
     * Host where database is located
     */
    public $host = '';

    /**
     * Username used to connect to database
     */
    public $username = '';

    /**
     * Password used to connect to database
     */
    public $passwd = '';

    /**
     * Database to backup
     */
    public $dbName = '';

    /**
     * Database charset
     */
    public $charset = '';

    public $conn = null;

    /**
     * Constructor initializes database
     */
    public function __construct($host, $username, $passwd, $dbName, $charset = 'utf8')
    {
        $this->host     = $host;
        $this->username = $username;
        $this->passwd   = $passwd;
        $this->dbName   = $dbName;
        $this->charset  = $charset;

        $this->initializeDatabase();
    }

    protected function initializeDatabase()
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->passwd);
        mysqli_select_db($this->conn, $this->dbName);
        if (!mysqli_set_charset($this->conn, $this->charset)) {
            mysqli_query($this->conn, 'SET NAMES ' . $this->charset);
        }
    }

    /**
     * Backup the whole database or just some tables
     * Use '*' for whole database or 'table1 table2 table3...'
     * @param string $tables
     */
    public function backupTables($tables = '*', $outputDir = '.')
    {
        try
        {
            /**
             * Tables to export
             */
            if ($tables == '*') {
                $tables = array();
                $result = mysqli_query($this->conn, 'SHOW TABLES');
                while ($row = mysqli_fetch_row($result)) {
                    $tables[] = $row[0];
                }
            } else {
                $tables = is_array($tables) ? $tables : explode(',', $tables);
            }

            $sql = "\n\n";
            $sql .= 'CREATE DATABASE IF NOT EXISTS ' . $this->dbName . ";\n\n";
            $sql .= 'USE ' . $this->dbName . ";\n\n";

            /**
             * Iterate tables
             */
            foreach ($tables as $table) {
                echo "Backing up " . $table . " table...";

                $result     = mysqli_query($this->conn, 'SELECT * FROM ' . $table);
                $numFields  = mysqli_num_fields($result);
                $numRecords = mysqli_num_rows($result);

                //$sql .= 'DROP TABLE IF EXISTS ' . $table . ';';
                $row2 = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE ' . $table));
                $sql .= "\n\n" . $row2[1] . ";\n\n";
                $fila_insert = 501;
                $i           = 1;
                //for ($i = 0; $i < $numFields; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    if ($fila_insert > 500) {
                        $sql .= 'INSERT INTO ' . $table . ' VALUES' . "\n";
                        $fila_insert = 0;
                    }
                    $sql .= "(";
                    for ($j = 0; $j < $numFields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace(array("\r", "\n", "\t"), array('\\r', '\\n', '\\t'), $row[$j]);

                        if (isset($row[$j])) {
                            //if ($row[$j] != null) {
                            $sql .= "'" . $row[$j] . "'";
                            //} else {
                            //    $sql .= "NULL";
                            //}
                        } else {
                            $sql .= "''";
                        }

                        if ($j < ($numFields - 1)) {
                            $sql .= ',';
                        }
                    }
                    $fila_insert++;
                    if ($fila_insert < 501) {
                        if ($i < $numRecords) {
                            $sql .= "),\n";
                            $i++;
                        } else {
                            $sql .= ");\n";
                        }
                    } else {
                        $sql .= ");\n";
                        $i++;
                    }
                }
                //}

                $sql .= "\n\n\n";

                echo " OK " . "
";
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return false;
        }

        return $this->saveFile($sql, $outputDir);
    }

    /**
     * Save SQL to file
     * @param string $sql
     */
    protected function saveFile(&$sql, $outputDir = '.')
    {
        if (!$sql) {
            return false;
        }

        try
        {
            $handle = fopen($outputDir . '/db-backup-' . $this->dbName . '-' . date("Ymd-His", time()) . '.sql', 'w+');
            fwrite($handle, $sql);
            fclose($handle);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return false;
        }

        return true;
    }
}
