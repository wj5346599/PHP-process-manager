<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * CodeIgniter Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class MY_Model extends CI_Model
{

    public function __construct($table_name = '')
    {
        parent::__construct();
        $this->table = $table_name;
    }

    /**
     * 	添加数据
     *  $dataArr:(array)插入的数据
     *  return:当前插入的数据id
     */
    function insert($dataArr)
    {
        $this->db->insert($this->table, $dataArr);
        return $this->db->insert_id();
    }

    /**
     * 	修改数据
     *  $dataArr:(array)更新的数据
     *  $whereArr:(array)更新的条件
     *  return:更新的数据条数
     */
    function update($dataArr, $whereArr)
    {
        $this->db->where($whereArr);
        $this->db->update($this->table, $dataArr);
        return $this->db->affected_rows();
    }

    /**
     * 	删除数据
     *  $whereArr:(array)删除的条件
     *  return:删除的数据条数
     */
    function delete($whereArr)
    {
        $this->db->where($whereArr);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * 	查询并返回一条数据
     *  $whereArr:(array)查询的条件
     *  $type:返回结果类型，默认为obj格式，arr为数组格式
     *  return:查询结果
     */
    function row($whereArr, $type = 'arr', $orderby = "")
    {
        $this->db->where($whereArr);
        if (!empty($orderby)) {
            $orderby = str_replace("@", " ", $orderby);
            $this->db->order_by($orderby);
        }
        $query = $this->db->get($this->table);

        if ($type == 'arr')
            return $query->row_array();
        elseif ($type == 'obj')
            return $query->row();

    }

    /**
     * 	查询并返回多条数据
     *  $whereArr:(array)查询的条件
     *  $type:返回结果类型，默认为obj格式，arr为数组格式
     *  $num:单页显示的条数
     *  $page:当前页数
     *  $orderby:排序条件
     *  return:查询结果
     */
    function result($whereArr, $page = 1, $num = 10, $orderby = "", $type = 'arr')
    {
        if ($page == 0)
            $page = 1;
        $offset = ($page - 1) * $num;
        $this->db->where($whereArr);
        if (!empty($orderby)) {
            $orderby = str_replace("@", " ", $orderby);
            $this->db->order_by($orderby);
        }
        $query = $this->db->get($this->table, $num, $offset);
        if ($type == 'obj')
            return $query->result();
        elseif ($type == 'arr')
            return $query->result_array();
    }

    /**
     * 	查询并返回所有数据
     *  $whereArr:(array)查询的条件
     *  $type:返回结果类型，默认为obj格式，arr为数组格式
     *  $orderby:排序条件
     *  return:查询结果
     */
    function all($whereArr = array(), $orderby = "", $type = 'arr')
    {
        $this->db->where($whereArr);
        if (!empty($orderby)) {
            $orderby = str_replace("@", " ", $orderby);
            $this->db->order_by($orderby);
        }
        $query = $this->db->get($this->table);
        if ($type == 'obj')
            return $query->result();
        elseif ($type == 'arr')
            return $query->result_array();
    }

}

// END Model Class

/* End of file Model.php */
/* Location: ./system/core/Model.php */