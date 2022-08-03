<?php
class Auditorium
{
    private $ds;
    private $audi_id;
    private $audi_name;

    private $table = 'tbl_auditoriums';

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

    private function isAudi($audi)
    {
        $query = 'Select audi_name from ' . $this->table . ' where audi_name=?';
        $paramType = 's';
        $paramValue = array(
            $audi
        );

        $status = $this->ds->getRecordCount($query, $paramType, $paramValue);
        return $status;
    }
    public function getAudis()
    {
        $query = 'Select * from ' . $this->table . ' order by audi_name';
        $paramType = '';
        $paramValue = array();

        $audis = $this->ds->select($query, $paramType, $paramValue);

        return $audis;
    }
    public function getAudi()
    {
        $query = 'Select audi_id, audi_name, entry from ' . $this->table . ' where audi_id=?';
        $paramType = 's';
        $paramValue = array($this->audiid);

        $audi = $this->ds->select($query, $paramType, $paramValue);

        return $audi;
    }
    public function addAudi()
    {
        $status = $this->isAudi($this->audi_name);

        if ($status > 0) {
            $response = setResponse('error', 'Auditorium already exists.');
        } else {
            $audiid = bin2hex(random_bytes(32));
            $query = 'INSERT INTO ' . $this->table . '(audi_id,audi_name) values(?, ?)';
            $paramType = 'ss';
            $paramValue = array(
                $audiid, $this->audi_name
            );

            $audiId = $this->ds->insert($query, $paramType, $paramValue);

            if (!empty($audiId)) {
                $response = setResponse('success', 'New auditorium added');
            } else {
                $response = setResponse('error', 'New auditorium could not be added');
            }
        }

        return $response;
    }

    public function updAudi()
    {
        $query = 'Update ' . $this->table . ' set audi_name=? where audi_id = ?';
        $paramType = 'si';
        $paramValue = array(
            $this->audi_name, $this->audi_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Auditorium updated');

        return $response;
    }

    public function delAudi()
    {
        $query = 'delete from ' . $this->table . ' where audi_id = ?';
        $paramType = 'i';
        $paramValue = array(
            $this->audi_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Auditorium deleted');

        return $response;
    }

    public function updAudiEntry()
    {
        $audi = $this->getAudi($this->audi_id);
        $entry = $audi[0]['entry'];

        $updEntry = !$entry;

        $query = 'Update ' . $this->table . ' set entry=? where audi_id = ?';
        $paramType = 'is';
        $paramValue = array(
            $updEntry, $this->audi_id
        );

        $this->ds->execute($query, $paramType, $paramValue);
        $response = setResponse('success', 'Auditorium Entry updated');

        return $response;
    }


    public function getEntryStatus()
    {
        $query = 'Select entry, audi_name from ' . $this->table . ' where audi_id = ?';
        $paramType = 's';
        $paramValue = array(
            $this->audi_id
        );

        $audi = $this->ds->select($query, $paramType, $paramValue);

        return $audi;
    }
}
