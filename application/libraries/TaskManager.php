<?php

/**
 * Process operation class
 * Have the following functions：
 * 1、Timed execution method
 * 2、execute Process
 * 3、checked Process status
 * 4、kill the process by pid
 */
class TaskManager
{

    /**
     * First, execute the process, get the process ID 
     * Second, loop for $timeout seconds checking if process is running 
     * If process is still running after timeout, kill the process and return false
     * @param string $command 
     * @param int $timeout
     * @param int $sleep
     * @return boolean 
     */
    function PsExecute($command, $timeout = 60, $sleep = 2)
    {
        $pid = $this->PsExec($command);
        if ($pid === false)
            return false;
        $cur = 0;
        while ($cur < $timeout) {
            sleep($sleep);
            $cur += $sleep;
            if (!$this->PsExists($pid))
                return true;
        }
        $this->PsKill($pid);
        return false;
    }

    /**
     * execute the process, get the process ID
     * @return bool or int
     */
    function PsExec($commandJob, $logs = '/dev/null', $isBefore = false)
    {
        if ($isBefore) {
            $this->_before();
        }
        $command = trim($commandJob) . ' > ' . $logs . ' 2>&1 & echo $!';
        exec($command, $op);
        $pid = (int) $op[0];
        if ($pid != "")
            return $pid;
        return false;
    }

    /**
     * before the execute,you can do somethings
     */
    function _before()
    {
        //before TODO
    }

    /**
     * check process status
     * @param int $pid
     * @return bool
     */
    function PsExists($pid)
    {
        exec("ps ax | grep $pid 2>&1", $output);
        while (list(, $row) = each($output)) {
            $rowString = trim($row);
            $row_array = explode(" ", $rowString);
            $check_pid = $row_array[0];
            if ($pid == $check_pid) {
                return true;
            }
        }
        return false;
    }

    /**
     * kill the process by pid
     * @param int $pid
     * @return bool
     */
    function PsKill($pid)
    {
        exec("kill -9 $pid", $output);
        return true;
    }

}
