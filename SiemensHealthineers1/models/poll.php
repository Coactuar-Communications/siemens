<?php
require_once '../inc/constants.php';

class Poll
{
    private $limit;
    private $ds;
    private $session_id;
    private $poll_id;
    private $poll_ques;
    private $poll_opt1;
    private $poll_opt2;
    private $poll_opt3;
    private $poll_opt4;
    private $poll_opt5;
    private $corr_ans;
    private $active;


    private $table = 'tbl_polls';

    private $user_id;
    private $answer;
    private $anstable = 'tbl_pollanswers';

    private $sesstable = 'tbl_sessions';

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

    public function addPoll()
    {
        $this->poll_id = bin2hex(random_bytes(32));
        $query = 'INSERT INTO ' . $this->table . '(session_id, poll_id, poll_question, poll_opt1,  poll_opt2, poll_opt3, poll_opt4, poll_opt5, correct_ans) values(?,?,?,?,?,?,?,?,?)';
        $paramType = 'sssssssss';
        $paramValue = array(
            $this->session_id,
            $this->poll_id,
            $this->poll_ques,
            $this->poll_opt1,
            $this->poll_opt2,
            $this->poll_opt3,
            $this->poll_opt4,
            $this->poll_opt5,
            $this->corr_ans,
        );

        $pollId = $this->ds->insert($query, $paramType, $paramValue);
        if (!empty($pollId)) {
            $status = "Poll added!";
            $response = setResponse('success', $status);
        } else {
            $status = "Poll could not be added!";
            $response = setResponse('error', $status);
        }

        return $response;
    }

    public function updatePoll()
    {
        $query = 'Update ' . $this->table . ' set session_id=?, poll_question=?, poll_opt1=?,  poll_opt2=?, poll_opt3=?, poll_opt4=?, poll_opt5=?, correct_ans=? where poll_id=?';
        $paramType = 'sssssssss';
        $paramValue = array(
            $this->session_id,
            $this->poll_ques,
            $this->poll_opt1,
            $this->poll_opt2,
            $this->poll_opt3,
            $this->poll_opt4,
            $this->poll_opt5,
            $this->corr_ans,
            $this->poll_id,

        );

        $this->ds->execute($query, $paramType, $paramValue);
        $status = "Poll updated!";
        $response = setResponse('success', $status);

        return $response;
    }

    public function getPoll()
    {
        $query = "select * from " . $this->table . " where poll_id=?";
        $paramType = 's';
        $paramValue = array(
            $this->poll_id
        );
        $poll = $this->ds->select($query, $paramType, $paramValue);

        return $poll;
    }

    public function submitResponse()
    {
        $poll_time   = date('Y/m/d H:i:s');
        $query = "insert into " . $this->anstable . "(poll_id, user_id,poll_answer, poll_at) values(?,?,?,?)";
        $paramType = 'ssss';
        $paramValue = array(
            $this->poll_id,
            $this->user_id,
            $this->answer,
            $poll_time
        );

        $pollOpt = $this->ds->insert($query, $paramType, $paramValue);
        return $pollOpt;
    }

    public function getPollCount()
    {
        $query = "SELECT id FROM " . $this->table;
        $paramType = '';
        $paramValue = array();

        $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

        return $count;
    }

    public function getPollsList($start)
    {
        $query = "select * from " . $this->table . ", " . $this->sesstable . " where " . $this->table . ".session_id = " . $this->sesstable . ".session_id order by start_time asc LIMIT $start, $this->limit";
        $paramType = '';
        $paramValue = array();
        $list = $this->ds->select($query, $paramType, $paramValue);

        return $list;
    }

    public function updateActivePoll()
    {
        $setvalue = 0;
        $query = 'Update ' . $this->table . ' set active=? where session_id=?';
        $paramType = 'ss';
        $paramValue = array(
            $setvalue,
            $this->session_id,
        );

        $this->ds->execute($query, $paramType, $paramValue);

        $query = 'Update ' . $this->table . ' set active=? where poll_id=?';
        $paramType = 'ss';
        $paramValue = array(
            $this->active,
            $this->poll_id,
        );

        $this->ds->execute($query, $paramType, $paramValue);

        $status = "Poll status updated!";
        $response = setResponse('success', $status);

        return $response;
    }

    public function delPoll()
    {
        $query = 'delete from ' . $this->table . ' where poll_id=?';
        $paramType = 's';
        $paramValue = array(
            $this->poll_id,
        );

        $this->ds->execute($query, $paramType, $paramValue);

        $status = "Poll deleted!";
        $response = setResponse('success', $status);

        return $response;
    }
    public function getAnsCount()
    {
        $query = "select * from " . $this->anstable . " where poll_id=?";
        $paramType = 's';
        $paramValue = array(
            $this->poll_id
        );
        $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

        return $count;
    }

    public function getOptAnsCount($option)
    {
        $query = "select * from " . $this->anstable . " where poll_id=? and poll_answer=?";
        $paramType = 'ss';
        $paramValue = array(
            $this->poll_id,
            $option
        );
        $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

        return $count;
    }

    public function getCurrSessionPoll()
    {
        $query = "select * from " . $this->table . " where session_id=? and active=1";
        $paramType = 's';
        $paramValue = array(
            $this->session_id
        );
        $poll = $this->ds->select($query, $paramType, $paramValue);

        return $poll;
    }

    public function isAnswered()
    {
        $query = 'select * from ' . $this->anstable . ' where poll_id = ? and user_id =?';
        $paramType = 'ss';
        $paramValue = array(
            $this->poll_id,
            $this->user_id
        );

        $status = $this->ds->getRecordCount($query, $paramType, $paramValue);
        return $status;
    }

    public function getSessPollCount()
    {
        $query = "SELECT id FROM " . $this->table . " where session_id=?";
        $paramType = 's';
        $paramValue = array($this->session_id);

        $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

        return $count;
    }

    public function getSessPollsList($start)
    {
        $query = "select * from " . $this->table . ", " . $this->sesstable . " where " . $this->table . ".session_id = " . $this->sesstable . ".session_id and " . $this->table . ".session_id=? order by start_time asc LIMIT $start, $this->limit";
        $paramType = 's';
        $paramValue = array($this->session_id);
        $list = $this->ds->select($query, $paramType, $paramValue);

        return $list;
    }
}
