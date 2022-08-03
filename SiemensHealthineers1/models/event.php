<?php
class Event
{
  private $ds;

  private $user_id;

  private $res_id;
  private $res_title;
  private $res_url;
  private $restable = 'tbl_resources';
  private $resdltable = 'tbl_resourcedownloads';

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


  public function getCountries()
  {
    $query = 'Select * from countries order by name';
    $paramType = '';
    $paramValue = array();

    $list = $this->ds->select($query, $paramType, $paramValue);
    return $list;
  }

  public function getStates($cntry)
  {
    $query = 'Select * from states where country_id=? order by name';
    $paramType = 's';
    $paramValue = array($cntry);

    $list = $this->ds->select($query, $paramType, $paramValue);
    return $list;
  }

  public function getCities($state)
  {
    $query = 'Select * from cities where state_id=? order by name';
    $paramType = 's';
    $paramValue = array($state);

    $list = $this->ds->select($query, $paramType, $paramValue);
    return $list;
  }

  public function getCountry($country)
  {
    $query = 'Select name from countries where id=?';
    $paramType = 's';
    $paramValue = array($country);

    $country = $this->ds->select($query, $paramType, $paramValue);
    return $country[0]['name'];
  }

  public function getState($state)
  {
    $query = 'Select name from states where id=?';
    $paramType = 's';
    $paramValue = array($state);

    $state = $this->ds->select($query, $paramType, $paramValue);
    return $state[0]['name'];
  }

  public function getCity($city)
  {
    $query = 'Select name from cities where id=?';
    $paramType = 's';
    $paramValue = array($city);

    $city = $this->ds->select($query, $paramType, $paramValue);
    return $city[0]['name'];
  }

  public function getResources()
  {
    $query = 'Select resource_id, resource_title, download_count from ' . $this->restable . ' order by resource_title';
    $paramType = '';
    $paramValue = array();

    $resources = $this->ds->select($query, $paramType, $paramValue);

    return $resources;
  }

  public function getResource()
  {
    $query = 'Select resource_id, resource_title,resource_url, download_count from ' . $this->restable . ' where resource_id= ?';
    $paramType = 's';
    $paramValue = array($this->res_id);

    $resource = $this->ds->select($query, $paramType, $paramValue);

    return $resource;
  }

  public function updateFileDLCount()
  {
    $res = $this->getResource();
    $dl = $res[0]['download_count'] + 1;

    $query = 'Update ' . $this->restable . ' set download_count=? where resource_id = ?';
    $paramType = 'ss';
    $paramValue = array(
      $dl,
      $this->res_id
    );

    $this->ds->execute($query, $paramType, $paramValue);

    $dltime   = date('Y/m/d H:i:s');
    $query = "Insert into " . $this->resdltable . "(resource_id, user_id, dl_time) values(?, ?, ?)";
    $paramType = 'sss';
    $paramValue = array(
      $this->res_id,
      $this->user_id,
      $dltime
    );

    $dlid = $this->ds->insert($query, $paramType, $paramValue);
    return $dlid;
  }

  public function getResDownloads()
  {
    $query = 'Select distinct(' . $this->resdltable . '.user_id ), first_name, last_name, emailid,city, organization, phone_num from ' . $this->resdltable . ', ' . $this->usertable . ' where resource_id=? and ' . $this->resdltable . '.user_id = ' . $this->usertable . '. userid';
    $paramType = 's';
    $paramValue = array($this->res_id);

    $dlList = $this->ds->select($query, $paramType, $paramValue);

    return $dlList;
  }
}
