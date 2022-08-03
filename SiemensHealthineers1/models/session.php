<?php
class Session
{
  private $limit;
  private $ds;
  private $session_id;
  private $audi_id;
  private $session_title;
  private $session_desc;
  private $start_time;
  private $session_url;

  private $live_status;
  private $launch_status;
  private $session_status;
  private $show_session;


  private $table = 'tbl_sessions';
  private $auditable = 'tbl_auditoriums';

  private $user_id;
  private $atttable = 'tbl_sessionattendees';

  private $usertable = 'tbl_users';

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

  public function addSession()
  {
    $this->session_id = bin2hex(random_bytes(32));
    $query = 'INSERT INTO ' . $this->table . '(session_id, audi_id, session_title, session_desc, start_time, session_url) values(?,?,?,?,?,?)';
    $paramType = 'ssssss';
    $paramValue = array(
      $this->session_id,
      $this->audi_id,
      $this->session_title,
      $this->session_desc,
      $this->start_time,
      $this->session_url
    );

    $sessId = $this->ds->insert($query, $paramType, $paramValue);
    if (!empty($sessId)) {
      $status = "Session added!";
      $response = setResponse('success', $status);
    } else {
      $status = "Session could not be added!";
      $response = setResponse('success', $status);
    }

    return $response;
  }

  public function updateSession()
  {
    $query = 'Update ' . $this->table . ' set audi_id=?, session_title=?, session_desc=?, start_time=?, session_url=? where session_id=?';
    $paramType = 'ssssss';
    $paramValue = array(
      $this->audi_id,
      $this->session_title,
      $this->session_desc,
      $this->start_time,
      $this->session_url,
      $this->session_id
    );

    $this->ds->execute($query, $paramType, $paramValue);

    $status = "Session updated!";
    $response = setResponse('success', $status);
    return $response;
  }
  public function getCurrLiveSession()
  {
    $query = 'SELECT session_id FROM ' . $this->table . ' where ' . $this->table . '.audi_id = ? and live_status=1 LIMIT 1';
    $paramType = 's';
    $paramValue = array(
      $this->audi_id
    );

    $session = $this->ds->select($query, $paramType, $paramValue);

    return $session;
  }
  public function getAllSessions()
  {
    $query = 'SELECT session_id, audi_name, session_title, launch_status, session_status, session_url, start_time FROM ' . $this->table . ', ' . $this->auditable . ' where ' . $this->table . '.audi_id = ' . $this->auditable . '.audi_id order by start_time asc';
    $paramType = '';
    $paramValue = array();

    $sessions = $this->ds->select($query, $paramType, $paramValue);
    return $sessions;
  }

  public function getSession()
  {
    $query = 'SELECT session_id, ' . $this->table . '.audi_id, audi_name, session_title, session_desc, launch_status, session_status, session_url, start_time FROM ' . $this->table . ', ' . $this->auditable . ' where ' . $this->table . '.audi_id = ' . $this->auditable . '.audi_id and ' . $this->table . '.session_id=? LIMIT 1';
    $paramType = 's';
    $paramValue = array(
      $this->session_id
    );

    $session = $this->ds->select($query, $paramType, $paramValue);
    return $session;
  }

  public function updSessionLiveStatus()
  {
    $session = $this->getSession();
    //var_dump($session);
    $audi_id = $session[0]['audi_id'];

    $setvalue = 0;

    $query = 'Update ' . $this->table . ' set live_status=? where audi_id=?';
    $paramType = 'is';
    $paramValue = array(
      $setvalue,
      $audi_id
    );

    $this->ds->execute($query, $paramType, $paramValue);

    $query = 'Update ' . $this->table . ' set live_status=? where session_id=?';
    $paramType = 'ss';
    $paramValue = array(
      $this->live_status,
      $this->session_id
    );

    $this->ds->execute($query, $paramType, $paramValue);

    $response = setResponse('success', 'Status updated');
    return $response;
  }

  public function updSessionEntry()
  {
    $query = 'Update ' . $this->table . ' set launch_status=? where session_id=?';
    $paramType = 'ss';
    $paramValue = array(
      $this->launch_status,
      $this->session_id
    );

    $this->ds->execute($query, $paramType, $paramValue);
    $status = 'Session entry status updated';

    $response = setResponse('success', $status);
    return $response;
  }

  public function updSessionShow()
  {
    $query = 'Update ' . $this->table . ' set show_session=? where session_id=?';
    $paramType = 'ss';
    $paramValue = array(
      $this->show_session,
      $this->session_id
    );

    $this->ds->execute($query, $paramType, $paramValue);
    $status = 'Session show status updated';

    $response = setResponse('success', $status);
    return $response;
  }

  public function updSessionStatus()
  {
    $query = 'Update ' . $this->table . ' set session_status=? where session_id=?';
    $paramType = 'ss';
    $paramValue = array(
      $this->session_status,
      $this->session_id
    );

    $this->ds->execute($query, $paramType, $paramValue);

    $response = setResponse('success', 'Status updated');
    return $response;
  }
  public function delSess()
  {
    $query = 'delete from ' . $this->table . ' where session_id = ?';
    $paramType = 's';
    $paramValue = array(
      $this->session_id
    );

    $this->ds->execute($query, $paramType, $paramValue);
    $response = setResponse('success', 'Session deleted');

    return $response;
  }

  public function getSessionCount()
  {
    $query = "SELECT id FROM " . $this->table;
    $paramType = '';
    $paramValue = array();

    $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

    return $count;
  }

  public function getSessionsList($start)
  {
    $query = "select * from " . $this->table . ", " . $this->auditable . " where " . $this->table . ".audi_id = " . $this->auditable . ".audi_id order by live_status desc, start_time asc LIMIT $start, $this->limit";
    $paramType = '';
    $paramValue = array();
    $list = $this->ds->select($query, $paramType, $paramValue);

    return $list;
  }

  public function getWebcastSessions($audi, $day)
  {
    if ($day == 'day1') {
      $date = '2021-05-07';
    }
    if ($day == 'day2') {
      $date = '2021-05-08';
    }
    if ($day == 'day3') {
      $date = '2021-05-09';
    }
    $query = "select * from " . $this->table . "," . $this->auditable . " where " . $this->table . ".audi_id=" . $this->auditable . ".audi_id and show_session = '1' and " . $this->table . ".audi_id=?";
    $query .= " and DATE(start_time) = ?";
    $query .= "  order by start_time asc";
    $paramType = 'ss';
    $paramValue = array($audi, $date);
    $list = $this->ds->select($query, $paramType, $paramValue);

    return $list;
  }

  public function getWebcastSessionURL()
  {
    $query = "select session_url from " . $this->table . " where session_id=? limit 1";
    $paramType = 's';
    $paramValue = array(
      $this->session_id
    );

    $wcsessions = $this->ds->select($query, $paramType, $paramValue);

    return $wcsessions[0]['session_url'];
  }

  public function updateMemberSessionStatus()
  {
    $today = date('Y/m/d H:i:s', time());
    $status = 0;

    $query = "select * from " . $this->atttable . " where userid=? and sessionid=? and leave_time >= ? limit 1";
    $paramType = 'sss';
    $paramValue = array(
      $this->user_id,
      $this->session_id,
      $today
    );

    $status = $this->ds->getRecordCount($query, $paramType, $paramValue);
    //return $status;

    if ($status > 0) {
      $leave_time  = date('Y/m/d H:i:s', time() + 60);
      $query = "UPDATE " . $this->atttable . " set leave_time = ? where userid = ? and sessionid=? and leave_time >= ?";
      $paramType = 'ssss';
      $paramValue = array(
        $leave_time,
        $this->user_id,
        $this->session_id,
        $today
      );
      $this->ds->execute($query, $paramType, $paramValue);
    } else {

      $join_time  = date('Y/m/d H:i:s', time());
      $leave_time  = date('Y/m/d H:i:s', time() + 60);
      $query = "Insert into " . $this->atttable . "(sessionid, userid, join_time, leave_time) values(?,?,?,?)";
      $paramType = 'ssss';
      $paramValue = array(
        $this->session_id,
        $this->user_id,
        $join_time,
        $leave_time
      );
      $sesid = $this->ds->insert($query, $paramType, $paramValue);
    }

    return $status;
  }

  public function getLiveSessionViewerCount()
  {
    $today = date("Y/m/d H:i:s");

    $query = "select * from " . $this->atttable . " where sessionid=? and leave_time > ?";

    $paramType = 'ss';
    $paramValue = array(
      $this->session_id,
      $today
    );

    $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

    return $count;
  }

  public function getLiveSessionViewers()
  {
    $today = date("Y/m/d H:i:s");

    $query = "select first_name, last_name from " . $this->atttable . ", " . $this->usertable . " where " . $this->atttable . ".userid=" . $this->usertable . ".userid and sessionid=? and leave_time > ?";

    $paramType = 'ss';
    $paramValue = array(
      $this->session_id,
      $today
    );

    $viewerList = $this->ds->select($query, $paramType, $paramValue);

    return $viewerList;
  }

  public function getSessionAttendees()
  {
    $query = "SELECT count(DISTINCT userid) as cnt, " . $this->atttable . ".sessionid, session_title FROM " . $this->atttable . ", " . $this->table . " where " . $this->atttable . ".sessionid=" . $this->table . ".session_id group by " . $this->atttable . ".sessionid order by cnt desc ";

    $paramType = '';
    $paramValue = array();

    $viewerList = $this->ds->select($query, $paramType, $paramValue);

    return $viewerList;
  }

  public function getAttendeesList()
  {
    //$query = "select DISTINCT(" . $this->atttable . ".userid), first_name, last_name, emailid from " . $this->atttable . ", " . $this->usertable . " where sessionid=? and " . $this->atttable . ".userid=" . $this->usertable . ".userid";
    $query = "select title,first_name, last_name, emailid, city, organization, phone_num, join_time, leave_time from " . $this->atttable . ", " . $this->usertable . " where sessionid=? and " . $this->atttable . ".userid=" . $this->usertable . ".userid";
    $paramType = 's';
    $paramValue = array($this->session_id);

    $viewerList = $this->ds->select($query, $paramType, $paramValue);

    return $viewerList;
  }

  public function getSessionAttended()
  {
    $query = "select count(distinct userid) as cnt from " . $this->atttable;
    $paramType = '';
    $paramValue = array();

    $count = $this->ds->select($query, $paramType, $paramValue);

    return $count[0]['cnt'];
  }

  public function getAttendeeSessions()
  {
    $query = "SELECT count(distinct sessionid) as cnt, " . $this->atttable . ".userid FROM " . $this->atttable . ", " . $this->usertable . " where " . $this->atttable . ".userid=" . $this->usertable . ".userid GROUP by userid order by cnt desc limit 10";
    $paramType = '';
    $paramValue = array();

    $count = $this->ds->select($query, $paramType, $paramValue);

    return $count;
  }
}
