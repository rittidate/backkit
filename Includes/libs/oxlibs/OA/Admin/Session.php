<?php
class MAX_Dal_Admin_Session
{
    var $table = 'session';

	/**
     * @param string $session_id
     * @return string A serialized array (probably)
     *
     * @todo Consider raise an error when no session is found.
     */
    function getSerializedSession($session_id)
    {
        $mdb2 = connectDB();
        $sql = "select * from [pf]cms_session where sessionid='$session_id'";
        $res = $mdb2->query($sql);

        if ($res->numRows() > 0) {
            $doSession = $res->fetchRow();
            // Deal with MySQL 4.0 timestamp format (YYYYMMDDHHIISS)
            if (preg_match('/^(\d\d\d\d)(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)$/', $doSession->lastused, $m)) {
                $doSession->lastused = "{$m[1]}-{$m[2]}-{$m[3]} {$m[4]}:{$m[5]}:{$m[6]}";
            }
            // Deal with PgSQL timestamp with timezone
            if (preg_match('/^(\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d)./', $doSession->lastused, $m)) {
                $doSession->lastused = $m[1];
            }
            $timeNow = (int)strtotime(OA::getNow());
            $timeLastUsed = (int)strtotime($doSession->lastused);
//            echo $timeNow. "<br>";
//            echo $timeLastUsed. "<br>";
//            echo OA::getNow();
            if (($timeNow - $timeLastUsed) < 3600) {
                return $doSession->sessiondata;
            }
        }
        return false;
    }

    /**
     * Reset "last used" timestamp on a session to prevent it from timing out.
     *
     * @param string $session_id
     * @return void
     */
    function refreshSession($session_id)
    {
        $mdb2 = connectDB();

        $query = "
                    UPDATE
                        [pf]cms_session
                    SET
                        lastused = '". OA::getNow() ."'
                    WHERE
                        sessionid = '" . $session_id . "'";
        $result = $mdb2->execute($query);
    }

    /**
     * @param string $serialized_session_data
     * @param string $session_id
     *
     * @todo Use ANSI SQL syntax, such as an UPDATE/INSERT cycle.
     * @todo Push down REPLACE INTO into a MySQL-specific DAL.
     */
    function storeSerializedSession($serialized_session_data, $session_id)
    {
        $mdb2 = connectDB();
        $doSession = $mdb2->isHaveRow("select * from [pf]cms_session where sessionid='$session_id'");
        $datatype = array(DBTYPE_TEXT, DBTYPE_TIMESTAMP, DBTYPE_TEXT);
            $data = array($serialized_session_data, OA::getNow(), $session_id);

        if ($doSession) {
            $query = "update [pf]cms_session set sessiondata=?, lastused=? where sessionid=?";
        }
        else {
            $query = "insert into [pf]cms_session(sessiondata, lastused, sessionid) values(?, ?, ?) " ;
        }
        $mdb2->execute($query, $datatype, $data);
    }

    /**
     * Remove many unused sessions from storage.
     *
     * @todo Use ANSI SQL syntax, such as NOW() + INTERVAL '12' HOUR
     */
    function pruneOldSessions()
    {
        $mdb2 = connectDB();
        $query = "
                DELETE FROM [pf]cms_session
                WHERE
                    UNIX_TIMESTAMP('". OA::getNow() ."') - UNIX_TIMESTAMP(lastused) > 43200
                ";
        $mdb2->execute($query);
    }

    /**
     * Remove a specific session from storage.
     */
    function deleteSession($session_id)
    {
        $mdb2 = connectDB();
        $query="
           DELETE FROM [pf]cms_session
           WHERE sessionid='" . $session_id . "'";
        $mdb2->execute($query);
    }

}


?>
