<?php
class User
{
  private $ds;
  private $limit;

  private $user_id;
  private $title;
  private $firstname;
  private $lastname;
  private $emailid;
  private $mobilenum;
  private $organization;
  private $city;
  private $password;
  private $curr_room;

  private $table = 'tbl_users';
  private $logintable = 'tbl_user_logins';

  private $user_to;
  private $user_from;
  private $cardtable = 'tbl_cards';


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

  public function getUser()
  {
    $query = 'Select * from ' . $this->table . ' where userid=?';
    $paramType = 's';
    $paramValue = array(
      $this->user_id
    );
    $status = $this->ds->select($query, $paramType, $paramValue);
    return $status;
  }

  private function getUserByEmail()
  {
    $query = 'Select userid, logout_date, hash_pwd from ' . $this->table . ' where emailid=?';
    $paramType = 's';
    $paramValue = array(
      $this->emailid
    );

    $status = $this->ds->select($query, $paramType, $paramValue);
    return $status;
  }
  public function getUserCount()
  {
    $query = 'Select id from ' . $this->table . '';
    $paramType = '';
    $paramValue = array();

    $count = $this->ds->getRecordCount($query, $paramType, $paramValue);
    return $count;
  }
  public function getAllUsers($offset)
  {
    $query = 'Select * from ' . $this->table . ' order by logout_date desc limit ?,?';
    $paramType = 'ss';
    $paramValue = array($offset, $this->limit);

    $admins = $this->ds->select($query, $paramType, $paramValue);

    return $admins;
  }
  public function addUser()
  {
    $status = $this->getUserByEmail();

    if (!empty($status)) {
      $response = setResponse('error', 'You are already registered');
    } else {
      $this->user_id = bin2hex(random_bytes(32));
      $this->password = password_generate(8);
      $hash_pwd = password_hash($this->password, PASSWORD_DEFAULT);
      $reg_date   = date('Y/m/d H:i:s');
      $query = 'INSERT INTO ' . $this->table . '(userid, title, first_name, last_name, emailid, phone_num, organization, city, hash_pwd, reg_date) values(?,?,?,?,?,?,?,?,?,?)';
      $paramType = 'ssssssssss';
      $paramValue = array(
        $this->user_id,
        $this->title,
        $this->firstname,
        $this->lastname,
        $this->emailid,
        $this->mobilenum,
        $this->organization,
        $this->city,
        $hash_pwd,
        $reg_date
      );

      $userId = $this->ds->insert($query, $paramType, $paramValue);

      if (!empty($userId)) {
        $mail = new Mail();
        $mail->__set('emailto', $this->emailid);
        $mail->__set('password', $this->password);
        $mail->__set('name', $this->title . ' ' . $this->firstname . ' ' . $this->lastname);

     $mail->sendEmail();

        $response = setResponse('success', 'You are registered succesfully. Please check your email regarding instructions on how to login.');
      } else {
        $response = setResponse('error', 'You cound not be registered. Please try again.');
      }
    }

    return $response;
  }
  public function userLogin()
  {
    $status = $this->getUserByEmail();
    // 
    if (empty($status)) {
      $response = setResponse('error', 'You are not registered.');
      return $response;
    } else {
      $this->user_id = $status[0]['userid'];
      /* $hash_pwd = $status[0]['hash_pwd'];
      if (!password_verify($this->password, $hash_pwd)) {
        $response = setResponse('error', 'Invalid email address or password');
        return $response;
      } */
      /* $response = setResponse('success', 'Please login on 20th August 2021 at 03.45pm to attend Anubhav 2021.');
      return $response; */

      $login_date     = date('Y/m/d H:i:s');
      $logout_date    = date('Y/m/d H:i:s', time() + 60);

      $dateTimestamp1 = strtotime($status[0]["logoutdate"]);
      $dateTimestamp2 = strtotime($login_date);

      if ($dateTimestamp1 > $dateTimestamp2) {
        $response = setResponse('error', 'You are already logged in from another location. Please logout from other location and try again.');
        return $response;
      }

      $query = 'Update ' . $this->table . ' set login_date=?, logout_date=? where emailid = ?';
      $paramType = 'sss';
      $paramValue = array(
        $login_date,
        $logout_date,
        $this->emailid
      );

      $this->ds->execute($query, $paramType, $paramValue);

      $query = "Insert into " . $this->logintable . "(user_id, join_time, leave_time) values(?, ?, ?)";
      $paramType = 'sss';
      $paramValue = array(
        $this->user_id,
        $login_date,
        $logout_date
      );

      $this->ds->execute($query, $paramType, $paramValue);
      // print_r($this->user_id);
      $_SESSION['userid'] = $this->user_id;
      header('location: lobby.php');
    }
  }
  public function userTestLogin()
  {
    $status = $this->getUserByEmail();
    // 
    if (empty($status)) {
      $response = setResponse('error', 'You are not registered.');
      return $response;
    } else {
      $this->user_id = $status[0]['userid'];
      /* $hash_pwd = $status[0]['hash_pwd'];
      if (!password_verify($this->password, $hash_pwd)) {
        $response = setResponse('error', 'Invalid email address or password');
        return $response;
      } */

      $login_date     = date('Y/m/d H:i:s');
      $logout_date    = date('Y/m/d H:i:s', time() + 60);

      $dateTimestamp1 = strtotime($status[0]["logout_date"]);
      $dateTimestamp2 = strtotime($login_date);

      if ($dateTimestamp1 > $dateTimestamp2) {
        $response = setResponse('error', 'You are already logged in from another location. Please logout from other location and try again.');
        return $response;
      }

      $query = 'Update ' . $this->table . ' set login_date=?, logout_date=? where emailid = ?';
      $paramType = 'sss';
      $paramValue = array(
        $login_date,
        $logout_date,
        $this->emailid
      );

      $this->ds->execute($query, $paramType, $paramValue);

      $query = "Insert into " . $this->logintable . "(user_id, join_time, leave_time) values(?, ?, ?)";
      $paramType = 'sss';
      $paramValue = array(
        $this->user_id,
        $login_date,
        $logout_date
      );

      $this->ds->execute($query, $paramType, $paramValue);
      // print_r($this->user_id);
      $_SESSION['userid'] = $this->user_id;
      header('location: lobby.php');
    }
  }

  public function userLogout()
  {
    $logout_date   = date('Y/m/d H:i:s');

    $query = "UPDATE " . $this->table . " set logout_date=? where userid=?";
    $paramType = 'ss';
    $paramValue = array(
      $logout_date,
      $this->user_id
    );

    $this->ds->execute($query, $paramType, $paramValue);

    $query = "UPDATE " . $this->logintable . " set leave_time=? where user_id=? and leave_time>=?";
    $paramType = 'sss';
    $paramValue = array(
      $logout_date,
      $this->user_id,
      $logout_date
    );

    $this->ds->execute($query, $paramType, $paramValue);

    $loginStatus = "logged out.";
    $response = setResponse('success', $loginStatus);
    return $response;
  }

  public function getAllMemberList()
  {
    $query = 'Select * from ' . $this->table . ' order by reg_date desc';
    $paramType = '';
    $paramValue = array();

    $users = $this->ds->select($query, $paramType, $paramValue);

    return $users;
  }
  public function getOnlineMemberCount()
  {
    $today = date('Y/m/d H:i:s');
    $query = "SELECT * FROM " . $this->table . " where logout_date > ?";
    $paramType = 's';
    $paramValue = array(
      $today
    );

    $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

    return $count;
  }

  function getOnlineMembers($keyword)
  {
    $today   = date('Y/m/d H:i:s');
    $query = "select * from " . $this->table . " where logout_date > ? and ((first_name like '%$keyword%') || (last_name like '%$keyword%'))  order by first_name asc";
    $paramType = 's'; //ssss';
    $paramValue = array(
      $today
    );

    $online = $this->ds->select($query, $paramType, $paramValue);

    return $online;
  }

  public function updateMemberLoginStatus()
  {
    $loginUserResult = $this->getUser();
    //var_dump($loginUserResult);
    $loggedin = 0;
    if (!empty($loginUserResult)) {

      $today = date("Y/m/d H:i:s");
      $logout_date  = date('Y/m/d H:i:s', time() + 60);

      $query = "UPDATE " . $this->table . " set logout_date=?, current_room=? where userid=?";
      $paramType = 'sss';
      $paramValue = array(
        $logout_date,
        $this->curr_room,
        $this->user_id
      );

      $this->ds->execute($query, $paramType, $paramValue);

      $query = "SELECT * from " . $this->logintable . " where user_id=? and leave_time >= ? limit 1";
      $paramType = 'ss';
      $paramValue = array(
        $this->user_id,
        $today
      );

      $count = $this->ds->getRecordCount($query, $paramType, $paramValue);
      if ($count > 0) {
        $leave_time  = date('Y/m/d H:i:s', time() + 60);
        $query = "UPDATE " . $this->logintable . " set leave_time=? where user_id=? and leave_time >= ?";
        $paramType = 'sss';
        $paramValue = array(
          $leave_time,
          $this->user_id,
          $today
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $loggedin = 1;
      } else {
        $loggedin = 0;
      }

      return $loggedin;
    }
  }

  public function getVisitorsCount()
  {
    $today = date('Y/m/d H:i:s');
    $query = "SELECT distinct(user_id) FROM " . $this->logintable;
    $paramType = '';
    $paramValue = array();

    $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

    return $count;
  }
  public function getOnlineCount()
  {
    $today = date('Y/m/d H:i:s');
    $query = "SELECT distinct(user_id) FROM " . $this->logintable . " where leave_time > ?";
    $paramType = 's';
    $paramValue = array($today);

    $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

    return $count;
  }
  public function getOnpageCount($page)
  {
    $today = date('Y/m/d H:i:s');
    $query = "SELECT userid FROM " . $this->table . " where logout_date > ?  and 	current_room=?";
    $paramType = 'ss';
    $paramValue = array($today, $page);

    $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

    return $count;
  }

  public function getUserName()
  {
    $query = 'Select first_name, last_name from ' . $this->table . ' where userid=?';
    $paramType = 's';
    $paramValue = array(
      $this->user_id
    );

    $status = $this->ds->select($query, $paramType, $paramValue);
    return $status[0]['first_name'] . ' ' . $status[0]['last_name'];
  }

  public function shareCard()
  {
    $query = "select * from " . $this->cardtable . " where card_from=? and card_to=?";
    $paramType = 'ss';
    $paramValue = array(
      $this->user_from,
      $this->user_to
    );
    $card = $this->ds->select($query, $paramType, $paramValue);

    if (empty($card)) {
      $query = "insert into " . $this->cardtable . "(card_from, card_to) values(?,?)";
      $paramType = 'ss';
      $paramValue = array(
        $this->user_from,
        $this->user_to
      );
      $card_id = $this->ds->insert($query, $paramType, $paramValue);
      return $card_id;
    } else {
      return '-1';
    }
  }
  public function getCards()
  {
    $query = "select first_name, last_name, emailid, phone_num from " . $this->cardtable . ", " . $this->table . " where card_to=? and " . $this->cardtable . ".card_from = " . $this->table . ".userid order by shared_at desc";
    $paramType = 's';
    $paramValue = array(
      $this->user_to
    );
    $cards = $this->ds->select($query, $paramType, $paramValue);
    return $cards;
  }

  public function getTotalTimeSpent()
  {
    $query = "SELECT SUM(TIMESTAMPDIFF(SECOND, join_time, leave_time)) as total FROM " . $this->logintable;
    $paramType = '';
    $paramValue = array();

    $count = $this->ds->select($query, $paramType, $paramValue);

    return $count[0]['total'];
  }

  public function getUserLogins()
  {
    $query = "select title, first_name, last_name, emailid, city, organization, phone_num, join_time, leave_time from " . $this->logintable . ", " . $this->table . " where " . $this->logintable . ".user_id = " . $this->table . ".userid order by join_time asc";
    $paramType = 's';
    $paramValue = array();
    $logins = $this->ds->select($query, $paramType, $paramValue);
    return $logins;
  }
  public function delUser()
  {
    $query = "delete from " . $this->logintable . " where user_id = ?";
    $paramType = 's';
    $paramValue = array(
      $this->user_id
    );
    $this->ds->execute($query, $paramType, $paramValue);
    $query = "delete from " . $this->table . " where userid = ?";
    $paramType = 's';
    $paramValue = array(
      $this->user_id
    );
    $this->ds->execute($query, $paramType, $paramValue);
    return 'done';
  }
}
function password_generate($chars)
{
  $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
  return substr(str_shuffle($data), 0, $chars);
}
