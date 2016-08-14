<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class taskManger extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //before checked
//        $this->_before_check();
        $this->load->library(array('TaskManager'));
        $this->load->model('Tasklist_model');
        $this->task = new TaskManager();
    }

    /**
     * sign before or before to do something or debug and you..
     */
    protected function _before_check()
    {
        if (isset($_GET['debug'])) {
            error_reporting(E_ALL);
        }
        session_start();
        $uri = explode('/', uri_string());
        $sign = 'vmlxjxcia';
        //need by check routers
        $checkArr = array('get_lists', 'start_task', 'edit_task', 'delete_task');
        if (in_array($uri[1], $checkArr)) {
            $current_uri = $this->input->get('v');
            if (isset($_SESSION['v']) && $_SESSION['v'] == $sign) {
                return true;
            }
            if ($current_uri != $sign) {
                echo '404 Not Found';
                exit;
            } else {
                $_SESSION['v'] = $current_uri;
            }
        }
    }

    /**
     * killed the process 
     */
    public function killTask()
    {
        $pid = $this->input->get('pid');
        $this->task->PsKill($pid);
    }

    /**
     * deleted the process
     */
    public function delete_task()
    {
        $id = $this->input->get('id');
        if ($id) {
            if ($this->Tasklist_model->delete(array('id' => $id))) {
                $this->backjs("删除成功!", 'get_lists');
            }
        }
        $this->backjs("删除失败!");
    }

    protected function backjs($msg, $local = 'get_lists')
    {
        echo "<script type=text/javascript>";
        echo "alert('" . $msg . "');window.location.href='{$local}'";
        echo "</script>";
        exit;
    }

    /**
     * stop the process
     */
    public function stop_task()
    {
        $pid = $this->input->get('pid');
        if ($pid) {
            $this->task->PsKill($pid);
            if ($this->Tasklist_model->update(array('status' => 2), array('pid' => $pid))) {
                $this->backjs("操作成功!", 'get_lists');
            }
        }
        $this->backjs("操作失败!");
    }

    /**
     * start the process
     */
    public function start_task()
    {
        $id = $this->input->get('id');
        if ($id) {
            if ($daemon = $this->Tasklist_model->row(array('id' => $id))) {
                $isBefore = false;
                if (isset($_GET['before'])) {
                    $isBefore = true;
                }
                $new_pid = $this->task->PsExec(htmlspecialchars_decode($daemon['command']), htmlspecialchars_decode($daemon['logs']), $isBefore);
                if ($new_pid > 0) {
                    $this->Tasklist_model->update(array('status' => 1, 'pid' => $new_pid), array('id' => $id));
                    $this->backjs("操作成功!", 'get_lists');
                }
            }
        }
        $this->backjs("操作失败!");
    }

    /**
     * editor the process
     */
    public function edit_task()
    {
        $post = $this->input->post();
        if ($post) {

            //step1 kill 进程
            $info_rel = $this->Tasklist_model->row(array('id' => $post['id']));
            if ($info_rel['pid'] > 0 && $info_rel['status'] == 1) {
                $this->task->PsKill($info_rel['pid']);
            }
            //step2 insert table
            $upd = array(
                'task_name' => htmlspecialchars($post['task_name']),
                'command' => htmlspecialchars($post['command']),
                'logs' => htmlspecialchars($post['logs']),
                'status' => 2, //重置为停止状态
                'pid' => 0 //重置0
            );
            $this->_createLogs($post['logs']);
            if (false !== $this->Tasklist_model->update($upd, array('id' => $post['id']))) {
                $this->backjs("编辑成功!");
            } else {
                $this->backjs("编辑失败!");
            }
        }
        $id = $this->input->get('id');
        if ($id) {
            $info = $this->Tasklist_model->row(array('id' => $id));
            $this->load->view('task/edit', array('taskinfo' => $info));
        } else {
            $this->backjs("非法!");
        }
    }

    /**
     * Create log path
     * @param type $urls url path
     */
    protected function _createLogs($urls = '/dev/null')
    {
        if (!empty($urls)) {
            $logs = explode('/', $urls);
            //if the first
            if ($logs[1] != 'dev' && !is_file($urls)) {
                array_pop($logs);
                $newDir = implode('/', $logs);
                mkdir($newDir, 0777, true) or $this->backjs('无法创建' . $logs[1] . '文件夹');
                fopen($urls, 'w+') or $this->backjs('无法创建' . end($logs) . '文件');
                exit;
            }
        }
    }

    /**
     * add proecss
     */
    public function add_task()
    {
        $post = $this->input->post();
        if ($post) {
            $insert = array(
                'pid' => 0,
                'task_name' => htmlspecialchars($post['task_name']),
                'command' => htmlspecialchars($post['command']),
                'logs' => htmlspecialchars($post['logs']),
                'created_time' => date('Y-m-d H:i:s', time()),
                'status' => 2//默认停止
            );
            $this->_createLogs($post['logs']);
            if (false !== $this->Tasklist_model->insert($insert)) {
                $this->backjs("新增成功,但任务需要手动启动!");
            } else {
                $this->backjs("编辑失败!");
            }
        }
        $this->load->view('task/add');
    }

    /**
     * check daemon run status
     */
    public function check_status()
    {
        $this->psexc();
    }

    /**
     * check daemon run status function
     */
    public function psexc()
    {
        $pid = $this->input->get('pid');
        if ($this->task->PsExists($pid)) {
            $this->backjs("该任务正常运行!");
        }
        $this->backjs("该任务运行不正常!");
    }

    /**
     * show the exec error logs
     */
    function showlogs()
    {
        $id = $this->input->get('id');
        if ($id) {
            if ($rel = $this->Tasklist_model->row(array('id' => $id))) {
                if (!file_exists($rel['logs'])) {
                    exit("日志文件不存在!");
                }
                $logs = file_get_contents($rel['logs']);
                if (empty($logs)) {
                    exit("日志内容为空!");
                }
                $order = array("\r\n", "\n", "\r");
                $replace = '<br/>';
                $newlogs = str_replace($order, $replace, $logs);
                echo $newlogs ? : "暂无日志!";
                exit;
            }
        }
        exit("无效访问!");
    }

    /**
     * the daemon list
     */
    public function get_lists()
    {
        if (!function_exists('exec')) {
            exit('exec函数不可使用!');
        }
        $query_data = $this->Tasklist_model->all(array(), 'id desc');
        $this->load->view('task/list', array('tasklist' => $query_data));
    }

    /**
     * 测试daemon
     */
    function test()
    {
        while (true) {
            sleep(110);
        }
    }

}
