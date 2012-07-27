<?php

/**
 * -----------------------------------------------------------------------------
 *              C O M M O N  L I B R A R I E S  ( P H P  5 . 2 )
 * -----------------------------------------------------------------------------
 * 
 * @package    Common
 * @author     Kijin Sung <kijin@kijinsung.com>
 * @copyright  (c) 2012, Kijin Sung <kijin@kijinsung.com>
 * @license    GPL v3 <http://www.opensource.org/licenses/gpl-3.0.html>
 * @link       http://github.com/kijin/common
 * @version    201207.1
 * 
 * -----------------------------------------------------------------------------
 * 
 * Copyright (c) 2012, Kijin Sung <kijin@kijinsung.com>
 * 
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * -----------------------------------------------------------------------------
 */

class DB
{
    // Some static properties.
    
    protected function __construct() { }
    protected static $_dbh = null;
    protected static $_in_transaction = false;
    protected static $_nested_transaction_sequence = 1;
    protected static $_nested_transaction_memory = array();
    
    // Initialize the database connection.
    
    public static function initialize($filename_or_pdo)
    {
        if ($filename_or_pdo instanceof PDO)  // Used for dependency injection.
        {
            self::$_dbh = $filename_or_pdo;
        }
        else  // Default is to open an SQLite database.
        {
            self::$_dbh = new PDO('sqlite:' . $filename_or_pdo);
        }
        self::$_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    // Prepare.
    
    public static function prepare($querystring)
    {
        return self::$_dbh->prepare($querystring);
    }
    
    // Query.
    
    public static function query($querystring /* optional parameters */ )
    {
        $params = func_get_args(); array_shift($params);
        if (count($params) == 1 && is_array($params[0])) $params = $params[0];
        
        if (count($params))
        {
            $statement = self::$_dbh->prepare($querystring);
            $statement->execute($params);
        }
        else
        {
            $statement = self::$_dbh->query($querystring);
        }
        
        return $statement;
    }
    
    // Get direct access to the PDO object.
    
    public static function get_pdo()
    {
        return self::$_dbh;
    }
    
    // Get the last insert ID.
    
    public static function get_last_insert_id()
    {
        return self::$_dbh->lastInsertId();
    }
    
    // Normal transactions.
    
    public static function begin_transaction()
    {
        $status = self::$_dbh->beginTransaction();
        self::$_in_transaction = true;
        return $status;
    }
    
    public static function commit()
    {
        $status = self::$_dbh->commit();
        self::$_in_transaction = false;
        return $status;
    }
    
    public static function rollback()
    {
        $status = self::$_dbh->rollBack();
        self::$_in_transaction = false;
        return $status;
    }
    
    // Fake nested transactions.
    
    public static function try_begin_transaction()
    {
        $transid = self::$_nested_transaction_sequence++;
        if (self::$_in_transaction)
        {
            self::$_nested_transaction_memory[$transid] = false;
        }
        else
        {
            self::begin_transaction();
            self::$_nested_transaction_memory[$transid] = true;
        }
        return $transid;
    }
    
    public static function try_commit($transid)
    {
        if (self::$_nested_transaction_memory[$transid])
        {
            $status = self::commit();
        }
        else
        {
            $status = false;
        }
        return $status;
    }
}
