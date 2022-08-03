<?php

class Question
{

    private $ds;
    private $table = 'tbl_sessionquestions';
    public $limit = 50;

    private $quesid;
    private $sessionid;
    private $userid;
    private $question;

    function __construct()
    {
        $this->ds = new DataSource();
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    public function submitQues()
    {
        $this->quesid = bin2hex(random_bytes(32));
        $today = date('Y/m/d H:i:s');
        $query = "Insert into " . $this->table . "(quesid, sessionid, userid,question, asked_at) values(?,?,?,?,?)";
        $paramType = 'sssss';
        $paramValue = array(
            $this->quesid,
            $this->sessionid,
            $this->userid,
            $this->question,
            $today
        );

        $quesId = $this->ds->insert($query, $paramType, $paramValue);
        if (!empty($quesId)) {
            $response = setResponse('success', 'Question submitted!');
        } else {
            $response = setResponse('error', 'Question could not be submitted!');
        }

        return $response;
    }

    public function getQuesCount()
    {
        $query = "select * from " . $this->table;
        $paramType = '';
        $paramValue = array();

        $cnt = $this->ds->getRecordCount($query, $paramType, $paramValue);
        return $cnt;
    }

    public function getQuestions($start)
    {
        $query = "select tbl_sessionquestions.quesid, tbl_sessionquestions.question, tbl_sessionquestions.asked_at, tbl_users.first_name,tbl_users.last_name, tbl_sessions.session_title from tbl_sessionquestions, tbl_users, tbl_sessions where tbl_sessionquestions.userid=tbl_users.userid and tbl_sessionquestions.sessionid=tbl_sessions.session_id order by  asked_at desc LIMIT $start, $this->limit";
        $paramType = '';
        $paramValue = array();
        $list = $this->ds->select($query, $paramType, $paramValue);

        return $list;
    }

    public function getQues($questionid)
    {
        $query = "select * from " . $this->table . " where quesid = ? limit 1";
        $paramType = 's';
        $paramValue = array(
            $questionid
        );
        $q = $this->ds->select($query, $paramType, $paramValue);

        return $q;
    }

    public function delQues()
    {
        $query = "delete from " . $this->table . " where quesid=?";
        $paramType = 's';
        $paramValue = array(
            $this->quesid
        );
        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Question deleted');

        return $response;
    }

    public function getquesupdate()
    {
        $query = "SELECT * FROM " . $this->table;
        $paramType = '';
        $paramValue = array();

        $status = $this->ds->getRecordCount($query, $paramType, $paramValue);

        return $status;
    }

    public function getSessQuesCount()
    {
        $query = "select * from " . $this->table . " where sessionid=?";
        $paramType = 's';
        $paramValue = array($this->sessionid);

        $cnt = $this->ds->getRecordCount($query, $paramType, $paramValue);
        return $cnt;
    }

    public function getSessQuestions($start)
    {
        $query = "select tbl_sessionquestions.quesid, tbl_sessionquestions.question, tbl_sessionquestions.asked_at, tbl_users.first_name,tbl_users.last_name, tbl_sessions.session_title from tbl_sessionquestions, tbl_users, tbl_sessions where tbl_sessionquestions.userid=tbl_users.userid and tbl_sessionquestions.sessionid=tbl_sessions.session_id and tbl_sessionquestions.sessionid=? order by  asked_at desc LIMIT $start, $this->limit";
        $paramType = 's';
        $paramValue = array($this->sessionid);
        $list = $this->ds->select($query, $paramType, $paramValue);

        return $list;
    }

    public function getsessquesupdate()
    {
        $query = "SELECT * FROM " . $this->table . " where sessionid=?";
        $paramType = 's';
        $paramValue = array(
            $this->sessionid
        );

        $status = $this->ds->getRecordCount($query, $paramType, $paramValue);

        return $status;
    }

    public function getSessAllQuestions()
    {
        $query = "select tbl_sessionquestions.quesid, tbl_sessionquestions.question, tbl_sessionquestions.asked_at, tbl_users.first_name,tbl_users.last_name from tbl_sessionquestions, tbl_users, tbl_sessions where tbl_sessionquestions.userid=tbl_users.userid and tbl_sessionquestions.sessionid=tbl_sessions.session_id and tbl_sessionquestions.sessionid=? order by  asked_at desc";
        $paramType = 's';
        $paramValue = array($this->sessionid);
        $list = $this->ds->select($query, $paramType, $paramValue);

        return $list;
    }
}
