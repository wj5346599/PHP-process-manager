<!doctype html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8" />
        <title>daemon list</title>
        <meta name="description" content="text/html" />
        <meta name="author" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet'  href='../assets/css/bootstrap.css' type='text/css' media='all' />
        <link rel='stylesheet'  href='../assets/css/bootstrap-theme.css' type='text/css' media='all' />
        <link rel='stylesheet'  href='../assets/css/navigation.css' type='text/css' media='all' />
        <link rel='stylesheet'  href='../assets/js/jquery.js' type='text/css' media='all' />
    <body>
        <div style="padding:10px">
            <section class="content">
                <div class="alert alert-info">
                    编辑 [<?php echo $taskinfo['task_name']; ?>] 任务
                </div>
                <form class="form-horizontal" action="edit_task" method="post">
                    <div class="form-group">
                        <label for="name" class="col-sm-1 control-label">进程ID</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="id" value="<?php echo $taskinfo['id']; ?>"/>
                            <input  class="form-control" type="text" name="pid" readonly="true" value="<?php echo $taskinfo['pid']; ?>"/>
                        </div>
                        <div class="col-sm-3">
                            * 系统生成,无法修改
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-1 control-label">任务名称(*必填)</label>
                        <div class="col-sm-3">
                            <input  class="form-control" type="text" name="task_name" value="<?php echo $taskinfo['task_name']; ?>" required="true" />
                        </div>
                        <div class="col-sm-3 FontSmall">
                            * 任务的名称描述
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-1 control-label">任务脚本(*必填)</label>
                        <div class="col-sm-3">
                            <textarea class="form-control" rows="5" name="command"   required="true"><?php echo $taskinfo['command']; ?></textarea>
                        </div>
                        <div class="col-sm-3 warning">
                            * php 以及脚本路径建议写绝对路径 <br/>For example：/usr/sbin/php /obj_url/index.php param1 param2
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-1 control-label">日志路径</label>
                        <div class="col-sm-3">
                            <input class="form-control" type="text" name="logs" value="<?php echo $taskinfo['logs']; ?>"/>
                        </div>
                        <div class="col-sm-3">
                            * 格式为：./cronlogs/*.txt(cronlogs文件夹下), 默认为/dev/null表示重定向为空
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-1 control-label">状态</label>
                        <div class="col-sm-1 control-label" style="text-align: left">
                            <?php echo $taskinfo['status'] == 1 ? '正常' : '<span style="color:red">不正常</span>'; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label"></label>
                        <div class="col-sm-3">
                            <input type="submit" class="btn btn-primary" value="提交"/>
                            <input type="reset" class="btn btn-default" value="重置"/>
                        </div>
                    </div>
                </form>
        </div>
    </body>
</html>