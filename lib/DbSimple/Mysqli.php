<?php
/**
 * DbSimple_Mysqli: MySQL database.
 * (C) Dk Lab, http://en.dklab.ru
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * See http://www.gnu.org/copyleft/lesser.html
 *
 * Placeholders end blobs are emulated.
 *
 * @author Dmitry Koterov, http://forum.dklab.ru/users/DmitryKoterov/
 * @author Konstantin Zhinko, http://forum.dklab.ru/users/KonstantinGinkoTit/
 * 
 * @version 2.x $Id$
 */
require_once dirname(__FILE__) . '/Generic.php';


/**
 * Database driver for mysqli extension.
 */
class DbSimple_Mysqli extends DbSimple_Generic_Database
{
    var $link;

    /**
     * constructor(string $dsn)
     * Connect to MySQL.
     */
    function DbSimple_Mysqli($dsn)
    {
        $p = DbSimple_Generic::parseDSN($dsn);
        if (!is_callable('mysql_connect')) {
            return $this->_setLastError("-1", "MySQL extension is not loaded", "mysqli_connect");
        }
        $ok = $this->link = @mysqli_connect(
            $p['host'] . (empty($p['port'])? "" : ":".$p['port']),
            $p['user'],
            $p['pass'],
            preg_replace('{^/}s', '', $p['path'])
        );
        $this->_resetLastError();
        if (!$ok) return $this->_setDbError('mysqli_connect()');
        if (isset($p["charset"])) {
            $this->query('SET NAMES ?', $p["charset"]);
            mysqli_set_charset($this->link, $p["charset"]);
        }
    }


    function _performEscape($s, $isIdent=false)
    {
        if (!$isIdent) {
            return "'" . mysqli_real_escape_string( $this->link, $s) . "'";
        } else {
            return "`" . str_replace('`', '``', $s) . "`";
        }
    }


    function _performTransaction($parameters=null)
    {
        return $this->query('BEGIN');
    }


    function& _performNewBlob($blobid=null)
    {
        $obj = new DbSimple_Mysql_Blob($this, $blobid);
        return $obj;
    }


    function _performGetBlobFieldNames($result)
    {
        $blobFields = array();
        $allFields = mysqli_fetch_fields($result);
        
        if (!empty($allFields)) {
            foreach ($allFields as $field)
                if (strpos($field["type"], "BLOB") !== false) $blobFields[] = $field["name"];
        }
        return $blobFields;
    }


    function _performGetPlaceholderIgnoreRe()
    {
        return '
            "   (?> [^"\\\\]+|\\\\"|\\\\)*    "   |
            \'  (?> [^\'\\\\]+|\\\\\'|\\\\)* \'   |
            `   (?> [^`]+ | ``)*              `   |   # backticks
            /\* .*?                          \*/      # comments
        ';
    }


    function _performCommit()
    {
        // return $this->query('COMMIT');
        return mysqli_commit($this->link);
    }


    function _performRollback()
    {
        // return $this->query('ROLLBACK');
        return mysqli_rollback($this->link);
    }


    function _performTransformQuery(&$queryMain, $how)
    {
        // If we also need to calculate total number of found rows...
        switch ($how) {
            // Prepare total calculation (if possible)
            case 'CALC_TOTAL':
                $m = null;
                if (preg_match('/^(\s* SELECT)(.*)/six', $queryMain[0], $m)) {
                    if ($this->_calcFoundRowsAvailable()) {
                        $queryMain[0] = $m[1] . ' SQL_CALC_FOUND_ROWS' . $m[2];
                    }
                }
                return true;
        
            // Perform total calculation.
            case 'GET_TOTAL':
                // Built-in calculation available?
                if ($this->_calcFoundRowsAvailable()) {
                    $queryMain = array('SELECT FOUND_ROWS()');
                }
                // Else use manual calculation.
                // TODO: GROUP BY ... -> COUNT(DISTINCT ...)
                $re = '/^
                    (?> -- [^\r\n]* | \s+)*
                    (\s* SELECT \s+)                                      #1     
                    (.*?)                                                 #2
                    (\s+ FROM \s+ .*?)                                    #3
                        ((?:\s+ ORDER \s+ BY \s+ .*?)?)                   #4
                        ((?:\s+ LIMIT \s+ \S+ \s* (?:, \s* \S+ \s*)? )?)  #5
                $/six';
                $m = null;
                if (preg_match($re, $queryMain[0], $m)) {
                    $query[0] = $m[1] . $this->_fieldList2Count($m[2]) . " AS C" . $m[3];
                    $skipTail = substr_count($m[4] . $m[5], '?'); 
                    if ($skipTail) array_splice($query, -$skipTail);
                }
                return true;
        }
        
        return false;
    }


    function _performQuery($queryMain)
    {
        $this->_lastQuery = $queryMain;
        $this->_expandPlaceholders($queryMain, false);
        $result = @mysqli_query($this->link, $queryMain[0]);
        if ($result === false) return $this->_setDbError($queryMain[0]);
        if (!is_object($result)) {
            if (preg_match('/^\s* INSERT \s+/six', $queryMain[0])) {
                // INSERT queries return generated ID.
                return @mysqli_insert_id($this->link);
            }
            // Non-SELECT queries return number of affected rows, SELECT - mysqli_result object.
            return @mysqli_affected_rows($this->link);
        }
        return $result;
    }

    
    function _performFetch($result)
    {
        $row = @mysqli_fetch_assoc($result);
        if (mysqli_error($this->link)) return $this->_setDbError($this->_lastQuery);
        if ($row === false) return null;
        return $row;
    }
    
    
    function _setDbError($query)
    {
        return $this->_setLastError(mysqli_errno($this->link), mysqli_error($this->link), $query);
    }
    
    
    function _calcFoundRowsAvailable()
    {
        $ok = version_compare(mysqli_get_server_info($this->link), '4.0') >= 0;
        return $ok;
    }
}


class DbSimple_Mysql_Blob extends DbSimple_Generic_Blob
{
    // MySQL does not support separate BLOB fetching. 
    var $blobdata = null;
    var $curSeek = 0;

    function DbSimple_Mysql_Blob(&$database, $blobdata=null)
    {
        $this->blobdata = $blobdata;
        $this->curSeek = 0;
    }

    function read($len)
    {
        $p = $this->curSeek;
        $this->curSeek = min($this->curSeek + $len, strlen($this->blobdata));
        return substr($this->blobdata, $this->curSeek, $len);
    }

    function write($data)
    {
        $this->blobdata .= $data;
    }

    function close()
    {
        return $this->blobdata;
    }

    function length()
    {
        return strlen($this->blobdata);
    }
}
