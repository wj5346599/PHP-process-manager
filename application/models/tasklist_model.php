<?php

class Tasklist_model extends MY_Model
{

    public static $table = 'taskList';

    public function __construct()
    {
        parent::__construct(self::$table);
    }

}
