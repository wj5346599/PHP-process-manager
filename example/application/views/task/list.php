<!doctype html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8" />
        <title>Daemon process manger center</title>
        <meta name="description" content="text/html" />
        <meta name="author" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet'  href='../assets/css/bootstrap.css' type='text/css' media='all' />
        <link rel='stylesheet'  href='../assets/css/bootstrap-theme.css' type='text/css' media='all' />
        <link rel='stylesheet'  href='../assets/css/navigation.css' type='text/css' media='all' />
    <body>
        <div style="padding:10px">
            <section class="content">
                <div class="alert alert-info">
                    守护进程列表
                </div>
                <div style="float: left; margin-bottom:10px; margin-top: -5px">
                    <a class="btn btn-primary form-control" href="add_task">
                        添加任务
                    </a>
                </div>
                <table class="table table-hover tableWithBorder">
                    <thead>
                        <tr>
                            <th width="100">ID</th>
                            <th width="100">进程号</th>
                            <th width="100">任务名称</th>
                            <th width="200">执行脚本</th>
                            <th width="100">状态</th>
                            <th width="100">创建时间</th>
                            <th width="200">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($tasklist)) {
                            foreach ($tasklist as $item) {
                                ?>
                                <tr>
                                    <td><?php echo $item['id']; ?></td>
                                    <td><?php echo $item['pid']; ?></td>
                                    <td><?php echo $item['task_name']; ?></td>
                                    <td><?php echo $item['command']; ?></td>
                                    <td><?php echo $item['status'] == 1 ? "正常" : "停止"; ?></td>
                                    <td><?php echo $item['created_time']; ?></td>
                                    <td><a class="btn btn-info btn-sm" href="edit_task?id=<?php echo $item['id']; ?>">编辑<a/>  <a class="btn btn-primary btn-sm" href="delete_task?id=<?php echo $item['id']; ?>" onclick="if (confirm('你确定要删除该进程吗？')) {
                                                        return true;
                                                    } else {
                                                        return false;
                                                    }">删除<a/>  <?php if ($item['status'] == 1) { ?><a class="btn btn-warning btn-sm" href="stop_task?pid=<?php echo $item['pid']; ?>" onclick="if (confirm('你确定要停止该进程吗？')) {
                                                                    return true;
                                                                } else {
                                                                    return false;
                                                                }">停止<a/><?php } else { ?> <a class="btn btn-success btn-sm" href="start_task?id=<?php echo $item['id']; ?>" onclick="if (confirm('你确定要启动该进程吗？')) {
                                                                            return true;
                                                                        } else {
                                                                            return false;
                                                                        }">启动<a/> <?php } ?> <a class="btn btn-danger btn-sm" href="showlogs?id=<?php echo $item['id']; ?>" onclick="return showlog('<?php echo $item['id']; ?>')">查看日志</a> <?php if ($item['status'] == 1) { ?><a class="btn btn-default btn-sm" href="check_status?pid=<?php echo $item['pid']; ?>">状态校验<a/><?php } else { ?>　　　　　<?php } ?></td>
                                                            </tr>

                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<td style=text-align:center>暂无数据!</td>';
                                                    }
                                                    ?>
                                                    </tbody>		
                                                    </table>
                                                    </div>
                                                    </body>
                                                    </html>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">查看日志详情</h4>
                                                                </div>
                                                                <div class="modal-body" id="logs">

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script type="text/javascript" src="../assets/js/jquery.js"></script>
                                                    <script type="text/javascript" src="../assets/js/bootstrap.js"></script>
                                                    <script type="text/javascript">
                                                                function showlog(id) {
                                                                    $("#myModal").modal('show');
                                                                    $.get('showlogs?id=' + id, '', function (data) {
                                                                        $("#logs").html(data);
                                                                    });
                                                                    return false;
                                                                }
                                                    </script>