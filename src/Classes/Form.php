<?php

namespace NedeljkoKuzmanovic\DbForms\Classes;

class Form
{
    public $id, $last_updated;
    /**
     * [__construct description]
     * @param Array $data [description]
     */
    public function __construct(Array $data = null){
        foreach($this as $prop => $value){
            if(isset($data[$prop])){
                $method = 'set' . ucwords($prop);

                $this->$method($data[$prop]);
            }
        }
    }

    /**
    * [setId description]
    * @param [type] $id [description]
    */
    public function setId($id) : void {
        $this->id = (int)$id;
    }

    /**
    * [setUpdated_at description]
    * @param [type] $updated_at [description]
    */
    public function setUpdated_at($updated_at) : void {
        $this->updated_at = $updated_at;
    }
}
