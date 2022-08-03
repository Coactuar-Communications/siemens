<?php
require_once '../inc/constants.php';

class Admin
{
  private $ds;
  private $id;
  private $username;
  private $password;
  private $role;

  private $table = 'tbl_admins';

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

  private function isAdmin($username, $role)
  {
    $query = 'Select username from ' . $this->table . ' where username=? and role = ?';
    $paramType = 'ss';
    $paramValue = array(
      $username, $role
    );

    $status = $this->ds->getRecordCount($query, $paramType, $paramValue);
    return $status;
  }

  public function getAdmins()
  {
    $query = 'Select * from ' . $this->table . ' order by username';
    $paramType = '';
    $paramValue = array();

    $admins = $this->ds->select($query, $paramType, $paramValue);

    return $admins;
  }

  public function getAdmin($adminid)
  {
    $query = 'Select role, username from ' . $this->table . ' where id=?';
    $paramType = 's';
    $paramValue = array($adminid);

    $admins = $this->ds->select($query, $paramType, $paramValue);

    return $admins;
  }

  public function addAdmin()
  {
    $status = $this->isAdmin($this->username, $this->role);

    if ($status > 0) {
      $response = setResponse('error', 'Admin already exists.');
    } else {
      $hash_pwd = password_hash($this->password . SALT, PASSWORD_DEFAULT);

      $query = 'INSERT INTO ' . $this->table . '(username,password, role) values(?, ?, ?)';
      $paramType = 'sss';
      $paramValue = array(
        $this->username, $hash_pwd, $this->role
      );

      $adminId = $this->ds->insert($query, $paramType, $paramValue);

      if (!empty($adminId)) {
        $response = setResponse('success', 'New admin added');
      } else {
        $response = setResponse('error', 'New admin could not be added');
      }
    }

    return $response;
  }

  public function updAdmin()
  {
    $query = 'Update ' . $this->table . ' set role=? where id = ?';
    $paramType = 'si';
    $paramValue = array(
      $this->role, $this->id
    );

    $this->ds->execute($query, $paramType, $paramValue);
    $response = setResponse('success', 'Admin updated');

    return $response;
  }

  public function delAdmin()
  {
    $query = 'delete from ' . $this->table . ' where id = ?';
    $paramType = 'i';
    $paramValue = array(
      $this->id
    );

    $this->ds->execute($query, $paramType, $paramValue);
    $response = setResponse('success', 'Admin deleted');

    return $response;
  }

  public function adminLogin()
  {

    $query = 'SELECT * FROM ' . $this->table . ' WHERE username = ? and role =?';
    $paramType = 'ss';
    $paramValue = array(
      $this->username, $this->role
    );
    $admin = $this->ds->select($query, $paramType, $paramValue);

    if (!empty($admin)) {
      if (password_verify($this->password . SALT, $admin[0]['password'])) {
        $_SESSION['admin'] = $this->username;
        $responseMsg = '';
        $response = setResponse('success', $responseMsg);
      } else {

        $responseMsg = "Invalid username/password.";
        $response = setResponse('error', $responseMsg);
      }
    } else {
      $responseMsg = "Invalid username/password.";
      $response = setResponse('error', $responseMsg);
    }
    return $response;
  }
}
