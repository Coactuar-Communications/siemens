<?php

class Exhibitor
{
    private $ds;
    private $exhib_id;
    private $exhib_name;

    private $table = 'tbl_exhibitors';

    private $user_id;
    private $vistable = 'tbl_exhibitorvisitors';
    private $postable = 'tbl_posterviews';
    private $video_id;
    private $poster_id;
    private $video_title;
    private $video_url;

    private $vidtable = 'tbl_exhibitorvideos';
    private $vidviewtable = 'tbl_exhibitorvideosviews';

    private $res_id;
    private $res_title;
    private $res_url;
    private $restable = 'tbl_exhibitorresources';
    private $resdltable = 'tbl_exhibitorresourcedownloads';

    private $usertable = 'tbl_users';

    private $req;
    private $reqtable = 'tbl_exhibitorqueries';

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

    private function isExhib()
    {
        $query = 'Select exhib_name from ' . $this->table . ' where exhib_name=?';
        $paramType = 's';
        $paramValue = array(
            $this->exhib_name
        );

        $status = $this->ds->getRecordCount($query, $paramType, $paramValue);
        return $status;
    }
    public function isValidExhib()
    {
        $query = 'Select * from ' . $this->table . ' where exhib_id=?';
        $paramType = 's';
        $paramValue = array(
            $this->exhib_id
        );

        $exhib = $this->ds->select($query, $paramType, $paramValue);
        return $exhib;
    }

    public function getExhibitors()
    {
        $query = 'Select * from ' . $this->table . ' order by exhib_name';
        $paramType = '';
        $paramValue = array();

        $exhib = $this->ds->select($query, $paramType, $paramValue);

        return $exhib;
    }
    public function getExhibitor()
    {
        $query = 'Select exhib_id, exhib_name, entry, active from ' . $this->table . ' where exhib_id=?';
        $paramType = 's';
        $paramValue = array($this->exhib_id);

        $exhib = $this->ds->select($query, $paramType, $paramValue);

        return $exhib;
    }
    public function addExhib()
    {
        $status = $this->isExhib();

        if ($status > 0) {
            $response = setResponse('error', 'Exhibitor already exists.');
        } else {
            $this->exhib_id = bin2hex(random_bytes(32));
            $query = 'INSERT INTO ' . $this->table . '(exhib_id,exhib_name) values(?, ?)';
            $paramType = 'ss';
            $paramValue = array(
                $this->exhib_id, $this->exhib_name
            );

            $exhibId = $this->ds->insert($query, $paramType, $paramValue);

            if (!empty($exhibId)) {
                $response = setResponse('success', 'New exhibitor added');
            } else {
                $response = setResponse('error', 'New exhibitor could not be added');
            }
        }

        return $response;
    }

    public function updExhib()
    {
        $query = 'Update ' . $this->table . ' set exhib_name=? where exhib_id = ?';
        $paramType = 'ss';
        $paramValue = array(
            $this->exhib_name, $this->exhib_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Exhibitor updated');

        return $response;
    }

    public function delExhib()
    {
        $query = 'delete from ' . $this->table . ' where exhib_id = ?';
        $paramType = 's';
        $paramValue = array(
            $this->exhib_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Exhibitor deleted');

        return $response;
    }

    public function updExhibEntry()
    {
        $exhib = $this->getExhibitor();
        $entry = $exhib[0]['entry'];

        $updEntry = !$entry;

        $query = 'Update ' . $this->table . ' set entry=? where exhib_id = ?';
        $paramType = 'is';
        $paramValue = array(
            $updEntry, $this->exhib_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Exhibitor Entry updated');

        return $response;
    }
    public function updateMemberSessionStatus()
    {
        $today = date('Y/m/d H:i:s', time());
        $status = 0;

        $query = "select * from " . $this->atttable . " where user_id=? and audi_id=? and leave_time >= ? limit 1";
        $paramType = 'sss';
        $paramValue = array(
            $this->user_id,
            $this->audi_id,
            $today
        );

        $status = $this->ds->getRecordCount($query, $paramType, $paramValue);
        //return $status;

        if ($status > 0) {
            $leave_time  = date('Y/m/d H:i:s', time() + 60);
            $query = "UPDATE " . $this->atttable . " set leave_time = ? where user_id = ? and audi_id=? and leave_time >= ?";
            $paramType = 'ssss';
            $paramValue = array(
                $leave_time,
                $this->user_id,
                $this->audi_id,
                $today
            );
            $this->ds->execute($query, $paramType, $paramValue);
        } else {

            $join_time  = date('Y/m/d H:i:s', time());
            $leave_time  = date('Y/m/d H:i:s', time() + 60);
            $query = "Insert into " . $this->atttable . "(audi_id, user_id, join_time, leave_time) values(?,?,?,?)";
            $paramType = 'ssss';
            $paramValue = array(
                $this->audi_id,
                $this->user_id,
                $join_time,
                $leave_time
            );
            $sesid = $this->ds->insert($query, $paramType, $paramValue);
        }

        return $status;
    }

    public function addExhibVideo()
    {
        $this->video_id = bin2hex(random_bytes(32));
        $query = 'INSERT INTO ' . $this->vidtable . '(exhib_id, video_id, video_title, video_url) values(?, ?,?,?)';
        $paramType = 'ssss';
        $paramValue = array(
            $this->exhib_id,
            $this->video_id,
            $this->video_title,
            $this->video_url
        );

        $videoId = $this->ds->insert($query, $paramType, $paramValue);

        if (!empty($videoId)) {
            $response = setResponse('success', 'New video added');
        } else {
            $response = setResponse('error', 'New video could not be added');
        }

        return $response;
    }

    public function getVideos()
    {
        $query = 'Select video_id, exhib_name, video_title, views from ' . $this->vidtable . ',' . $this->table . ' where ' . $this->vidtable . '.exhib_id= ' . $this->table . '.exhib_id order by video_title';
        $paramType = '';
        $paramValue = array();

        $videos = $this->ds->select($query, $paramType, $paramValue);

        return $videos;
    }

    public function getVideo()
    {
        $query = 'Select exhib_id, video_title, video_url, views from ' . $this->vidtable . ' where video_id=?';
        $paramType = 's';
        $paramValue = array($this->video_id);

        $video = $this->ds->select($query, $paramType, $paramValue);

        return $video;
    }
    public function getPost()
    {
        $query = 'Select exhib_id, video_title, video_url, views from ' . $this->vidtable . ' where video_id=?';
        $paramType = 's';
        $paramValue = array($this->video_id);

        $video = $this->ds->select($query, $paramType, $paramValue);

        return $video;
    }
    public function updVideo()
    {
        $query = 'Update ' . $this->vidtable . ' set exhib_id=?, video_title=?, video_url=? where video_id = ?';
        $paramType = 'ssss';
        $paramValue = array(
            $this->exhib_id,
            $this->video_title,
            $this->video_url,
            $this->video_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Video updated');

        return $response;
    }

    public function delVideo()
    {
        $query = 'delete from ' . $this->vidtable . ' where video_id = ?';
        $paramType = 's';
        $paramValue = array(
            $this->video_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Video deleted');

        return $response;
    }

    public function updateVideoView()
    {
        $video = $this->getVideo();
        $views = $video[0]['views'] + 1;

        $query = 'Update ' . $this->vidtable . ' set views=? where video_id = ?';
        $paramType = 'ss';
        $paramValue = array(
            $views,
            $this->video_id
        );

        $this->ds->execute($query, $paramType, $paramValue);

        $viewtime   = date('Y/m/d H:i:s');
        $query = "Insert into " . $this->vidviewtable . "(video_id, user_id, view_time) values(?, ?, ?)";
        $paramType = 'sss';
        $paramValue = array(
            $this->video_id,
            $this->user_id,
            $viewtime
        );

        $viewid = $this->ds->insert($query, $paramType, $paramValue);
        return $viewid;
    }
    public function updatePostView()
    {
        // $video = $this->getVideo();
        // $views = $video[0]['views'] + 1;

        // $query = 'Update ' . $this->vidtable . ' set views=? where video_id = ?';
        // $paramType = 'ss';
        // $paramValue = array(
        //     $views,
        //     $this->video_id
        // );

        // $this->ds->execute($query, $paramType, $paramValue);

        $viewtime   = date('Y/m/d H:i:s');
        $query = "Insert into " . $this->postable . "(poster_id, user_id, view_time) values(?, ?, ?)";
        $paramType = 'sss';
        $paramValue = array(
            $this->poster_id,
            $this->user_id,
            $viewtime
        );
//  print_r($paramValue); die();
        $viewid = $this->ds->insert($query, $paramType, $paramValue);
        return $viewid;
    }
    public function getVideoViewersCount()
    {
      
        $query = 'SELECT count(DISTINCT user_id) as cnt,tbl_exhibitorvideosviews.video_id,tbl_exibitorvideosdetail.video_title FROM tbl_exhibitorvideosviews,tbl_exibitorvideosdetail, tbl_users where tbl_exhibitorvideosviews.user_id = tbl_users.userid and tbl_exibitorvideosdetail.videoid=tbl_exhibitorvideosviews.video_id GROUP by video_id ORDER by cnt desc ';
        
        $paramType = '';
        $paramValue = array();
      
        $videos = $this->ds->select($query, $paramType, $paramValue);
        
        return $videos;
    }
    public function getPosterViewersCount()
    {
        
        $query = 'SELECT count(DISTINCT user_id) as cnt, tbl_posterviews.poster_id,tbl_exibitorpostdetail.poster_title FROM tbl_posterviews,tbl_exibitorpostdetail, tbl_users where tbl_posterviews.user_id = tbl_users. userid and tbl_exibitorpostdetail.posterid=tbl_posterviews.poster_id GROUP by posterid ORDER by cnt desc';
        $paramType = '';
        $paramValue = array();

        $videos = $this->ds->select($query, $paramType, $paramValue);
// print_r($videos); die();
        return $videos;
    }
    public function getPosterViewers()
    {
        
        $query = 'SELECT tbl_users.first_name,tbl_users.last_name,tbl_users.emailid FROM tbl_posterviews, tbl_users where tbl_posterviews.user_id = tbl_users. userid and poster_id=?';
        $paramType = 's';
        $paramValue = array($this->video_id);

        $viewerList = $this->ds->select($query, $paramType, $paramValue);

        return $viewerList;
    }
    public function getVideoViewers()
    {
        
        $query = 'SELECT tbl_users.first_name,tbl_users.last_name,tbl_users.emailid FROM tbl_exhibitorvideosviews, tbl_users where tbl_exhibitorvideosviews.user_id = tbl_users. userid and video_id=? ';
        $paramType = 's';
        $paramValue = array($this->video_id);
        // print_r($query);
        $viewerList = $this->ds->select($query, $paramType, $paramValue);
        
        return $viewerList;
    }
    public function addExhibRes()
    {
        $this->res_id = bin2hex(random_bytes(32));
        $query = 'INSERT INTO ' . $this->restable . '(exhib_id, resource_id, resource_title, resource_url) values(?,?,?,?)';
        $paramType = 'ssss';
        $paramValue = array(
            $this->exhib_id,
            $this->res_id,
            $this->res_title,
            $this->res_url
        );

        $resId = $this->ds->insert($query, $paramType, $paramValue);

        if (!empty($resId)) {
            $response = setResponse('success', 'New resource added');
        } else {
            $response = setResponse('error', 'New resource could not be added');
        }

        return $response;
    }

    public function getResources()
    {
        $query = 'Select resource_id, exhib_name, resource_title, download_count from ' . $this->restable . ', ' . $this->table . ' where ' . $this->restable . '.exhib_id= ' . $this->table . '.exhib_id order by resource_title';
        $paramType = '';
        $paramValue = array();

        $resources = $this->ds->select($query, $paramType, $paramValue);

        return $resources;
    }
    public function getTitle()
    {
        $query = 'Select  video_title  from tbl_exibitorvideosdetail where videoid=?';
        $paramType = 's';
        $paramValue = array($this->video_id);
        // print_r($query); die();
        $video = $this->ds->select($query, $paramType, $paramValue);
      
        return $video;
    }
    public function getTitlePoster()
    {
        $query = 'Select  poster_title  from tbl_exibitorpostdetail where  posterid=?';
        $paramType = 's';
        $paramValue = array($this->video_id);
        // print_r($query); die();
        $video = $this->ds->select($query, $paramType, $paramValue);
      
        return $video;
    }

    public function getResource()
    {
        $query = 'Select exhib_id, resource_id, resource_title,resource_url, download_count from ' . $this->restable . ' where resource_id= ?';
        $paramType = 's';
        $paramValue = array($this->res_id);

        $resource = $this->ds->select($query, $paramType, $paramValue);

        return $resource;
    }

    public function updResource()
    {
        $query = 'Update ' . $this->restable . ' set exhib_id=?, resource_title=?, resource_url=? where resource_id = ?';
        $paramType = 'ssss';
        $paramValue = array(
            $this->exhib_id,
            $this->res_title,
            $this->res_url,
            $this->res_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Resource updated');

        return $response;
    }

    public function delResource()
    {
        $query = 'delete from ' . $this->restable . ' where resource_id = ?';
        $paramType = 's';
        $paramValue = array(
            $this->res_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Resource deleted');

        return $response;
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
        $query = 'Select distinct(' . $this->resdltable . '.user_id ), first_name, last_name, emailid from ' . $this->resdltable . ', ' . $this->usertable . ' where resource_id=? and ' . $this->resdltable . '.user_id = ' . $this->usertable . '. userid';
        $paramType = 's';
        $paramValue = array($this->res_id);

        $dlList = $this->ds->select($query, $paramType, $paramValue);

        return $dlList;
    }

    public function updateUserVisit()
    {
        $query = 'INSERT INTO ' . $this->vistable . '(exhib_id, user_id) values(?,?)';
        $paramType = 'ss';
        $paramValue = array(
            $this->exhib_id,
            $this->user_id
        );

        $this->ds->insert($query, $paramType, $paramValue);
    }

    public function getExhibitorVisitorCount()
    {
        $query = "SELECT count(DISTINCT user_id) as cnt, " . $this->vistable . ".exhib_id, exhib_name FROM " . $this->vistable . ", " . $this->table . " where " . $this->vistable . ".exhib_id = " . $this->table . ".exhib_id GROUP by exhib_id ORDER by cnt desc";
// print_r($query); die();
        $paramType = '';
        $paramValue = array();

        $viewerList = $this->ds->select($query, $paramType, $paramValue);

        return $viewerList;
    }

    public function getAttendeesList()
    {
        $query = "select DISTINCT(" . $this->vistable . ".user_id), first_name, last_name, emailid from " . $this->vistable . ", " . $this->usertable . " where exhib_id=? and " . $this->vistable . ".user_id=" . $this->usertable . ".userid";
        $paramType = 's';
        $paramValue = array($this->exhib_id);

        $viewerList = $this->ds->select($query, $paramType, $paramValue);

        return $viewerList;
    }

    public function updExhReq()
    {
        $req_time   = date('Y/m/d H:i:s');
        $query = 'INSERT INTO ' . $this->reqtable . '(exhib_id, user_id, request_for,req_time) values(?,?,?,?)';
        $paramType = 'ssss';
        $paramValue = array(
            $this->exhib_id,
            $this->user_id,
            $this->req,
            $req_time
        );

        $this->ds->insert($query, $paramType, $paramValue);
    }

    public function getExhRequests()
    {
        $query = 'Select count(*) as cnt,' . $this->reqtable . '.exhib_id  from ' . $this->reqtable . ' group by exhib_id order by cnt desc';
        $paramType = '';
        $paramValue = array();

        $req = $this->ds->select($query, $paramType, $paramValue);

        return $req;
    }
    public function getRequests()
    {
        $query = 'Select distinct(' . $this->reqtable . '.user_id), request_for,first_name, last_name, emailid  from ' . $this->reqtable . ', ' . $this->usertable . ' where ' . $this->reqtable . '.user_id = ' . $this->usertable . '.userid and exhib_id=?';
        //var_dump($query);
        $paramType = 's';
        $paramValue = array($this->exhib_id);

        $req = $this->ds->select($query, $paramType, $paramValue);

        return $req;
    }

    public function getResCount()
    {
        $query = "SELECT distinct(resource_id) FROM " . $this->restable;
        $paramType = '';
        $paramValue = array();

        $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

        return $count;
    }

    public function getResDLCount()
    {
        $query = "select sum(download_count) as total from " . $this->restable;
        $paramType = '';
        $paramValue = array();

        $count = $this->ds->select($query, $paramType, $paramValue);

        return $count;
    }

    public function getVidCount()
    {
        $query = "SELECT distinct(video_id) FROM " . $this->vidtable;
        $paramType = '';
        $paramValue = array();

        $count = $this->ds->getRecordCount($query, $paramType, $paramValue);

        return $count;
    }

    public function getVideoViewsCount()
    {
        $query = "select sum(views) as total from " . $this->vidtable;
        $paramType = '';
        $paramValue = array();

        $count = $this->ds->select($query, $paramType, $paramValue);

        return $count[0]['total'];
    }

    public function getExhVisitorsCount()
    {
        $query = "select count(distinct user_id) as cnt from " . $this->vistable;
        $paramType = '';
        $paramValue = array();

        $count = $this->ds->select($query, $paramType, $paramValue);

        return $count[0]['cnt'];
    }

    public function getVisitorsExhVisits()
    {
        $query = "SELECT count(DISTINCT exhib_id) as cnt, user_id FROM " . $this->vistable . "  GROUP by user_id ORDER by cnt DESC limit 10";
        $paramType = '';
        $paramValue = array();

        $count = $this->ds->select($query, $paramType, $paramValue);

        return $count;
    }

    public function subRequests()
    {
        $reqtime   = date('Y/m/d H:i:s');
        $query = "Insert into " . $this->reqtable . "(exhib_id, user_id, request_for, req_time) values(?, ?, ?,?)";
        $paramType = 'ssss';
        $paramValue = array(
            $this->exhib_id,
            $this->user_id,
            $this->req,
            $reqtime
        );

        $reqid = $this->ds->insert($query, $paramType, $paramValue);

        if ($reqid > 0) {
            $response = setResponse('success', 'Request submitted sucessfully');
        } else {
            $response = setResponse('error', 'Request could not be submitted');
        }
        return $response;
    }
}
