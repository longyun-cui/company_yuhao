<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="<?php echo nl2br(e(url('/'))); ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>工</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>员工系统</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active-">
                    <a href="javascript:void(0);" style="color:#fff;">
                        <b class="nav-header-title">
                            <?php echo $__env->yieldContent('title'); ?>
                            <span class="sr-only"><?php echo $__env->yieldContent('title'); ?></span>
                        </b>
                        <span class="nav-header-title-2"><?php echo $__env->yieldContent('title-2'); ?></span>
                        <span class="nav-header-title-3"><?php echo $__env->yieldContent('title-3'); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">


                <li class="dropdown tasks-menu add-menu _none">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        统计 <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu right" role="menu">


                        <li class="divider"></li>

                        <?php if(in_array($me->user_type,[0,1,9,11,41,81,84])): ?>
                            <li>
                                <a href="javascript:void(0);" class="tab-control datatable-control"
                                   data-type="create"
                                   data-unique="y"
                                   data-id="statistic-caller-overview"
                                   data-title='<i class="fa fa-bar-chart text-orange"></i> 坐席统计'
                                   data-content=''

                                   data-datatable-type="create"
                                   data-datatable-unique="y"
                                   data-datatable-id="datatable-statistic-caller-overview"
                                   data-datatable-target="statistic-caller-overview"
                                   data-datatable-clone-object="statistic-caller-overview-clone"
                                >
                                    <i class="fa fa-bar-chart text-orange"></i> <span>坐席统计</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>
                        <?php if(in_array($me->user_type,[0,1,9,11,41,81,84])): ?>
                            <li>
                                <a href="javascript:void(0);" class="tab-control datatable-control"
                                   data-type="create"
                                   data-unique="y"
                                   data-id="statistic-caller-rank"
                                   data-title='<i class="fa fa-line-chart text-orange"></i> 坐席排名'
                                   data-content=''

                                   data-datatable-type="create"
                                   data-datatable-unique="y"
                                   data-datatable-id="datatable-statistic-caller-rank"
                                   data-datatable-target="statistic-caller-rank"
                                   data-datatable-clone-object="statistic-caller-rank-clone"
                                >
                                    <i class="fa fa-line-chart text-orange"></i> <span>坐席排名</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>
                        <?php if(in_array($me->user_type,[0,1,9,11,41,81,84])): ?>
                            <li>
                                <a href="javascript:void(0);" class="tab-control datatable-control"
                                   data-type="create"
                                   data-unique="y"
                                   data-id="statistic-caller-recent"
                                   data-title='<i class="fa fa-bar-chart text-orange"></i> 近期成果'
                                   data-content=''

                                   data-datatable-type="create"
                                   data-datatable-unique="y"
                                   data-datatable-id="datatable-statistic-caller-recent"
                                   data-datatable-target="statistic-caller-recent"
                                   data-datatable-clone-object="statistic-caller-recent-clone"
                                >
                                    <i class="fa fa-area-chart text-orange"></i> <span>近期成果</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>
                        <?php if(in_array($me->user_type,[0,1,9,11,61,71])): ?>
                            <li>
                                <a href="javascript:void(0);" class="tab-control datatable-control"
                                   data-type="create"
                                   data-unique="y"
                                   data-id="statistic-inspector-overview"
                                   data-title='<i class="fa fa-pie-chart text-orange"></i> 质检统计'
                                   data-content=''

                                   data-datatable-type="create"
                                   data-datatable-unique="y"
                                   data-datatable-id="datatable-statistic-inspector-overview"
                                   data-datatable-target="statistic-inspector-overview"
                                   data-datatable-clone-object="statistic-inspector-overview-clone"
                                >
                                    <i class="fa fa-pie-chart text-purple"></i> <span>质检统计</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>

                        <?php if(in_array($me->user_type,[0,1,9,11,61])): ?>
                            <li>
                                <a href="javascript:void(0);" class="tab-control datatable-control"
                                   data-type="create"
                                   data-unique="y"
                                   data-id="statistic-deliverer-overview"
                                   data-title='<i class="fa fa-bar-chart text-orange"></i> 运营看板'
                                   data-content=''

                                   data-datatable-type="create"
                                   data-datatable-unique="y"
                                   data-datatable-id="datatable-statistic-deliverer-overview"
                                   data-datatable-target="statistic-deliverer-overview"
                                   data-datatable-clone-object="statistic-deliverer-overview-clone"
                                >
                                    <i class="fa fa-bar-chart text-red"></i> <span>运营统计</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>

                        <?php if(in_array($me->user_type,[0,1,9,11,41,81,84,71,77,61,66])): ?>
                            <li>
                                <a href="javascript:void(0);" class="tab-control datatable-control"
                                   data-type="create"
                                   data-unique="y"
                                   data-id="statistic-production-project"
                                   data-title='<i class="fa fa-area-chart text-teal"></i> <span>项目看板</span>'
                                   data-content=''

                                   data-datatable-type="create"
                                   data-datatable-unique="y"
                                   data-datatable-id="datatable-statistic-production-project"
                                   data-datatable-target="statistic-production-project"
                                   data-datatable-clone-object="statistic-production-project-clone"
                                >
                                    <i class="fa fa-area-chart text-teal"></i> <span>项目看板</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>

                        <?php if(in_array($me->user_type,[0,1,9,11])): ?>
                            <li>
                                <a href="javascript:void(0);" class="tab-control datatable-control"
                                   data-type="create"
                                   data-unique="y"
                                   data-id="statistic-production-department"
                                   data-title='<i class="fa fa-area-chart text-teal"></i> <span>部门看板</span>'
                                   data-content=''

                                   data-datatable-type="create"
                                   data-datatable-unique="y"
                                   data-datatable-id="datatable-statistic-production-department"
                                   data-datatable-target="statistic-production-department"
                                   data-datatable-clone-object="statistic-production-department-clone"
                                >
                                    <i class="fa fa-area-chart text-teal"></i> <span>部门看板</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php endif; ?>


                    </ul>
                </li>

                <li class="dropdown- notifications-alert _none">
                    <a href="javascript:void(0);" class="modal-show--for--finance-item-create"
                       data-form-id="form--for--finance-item-edit"
                       data-modal-id="modal--for--finance-item-edit"
                       data-title="添加财务记录"
                    >
                        <i class="fa fa-plus text-yellow"></i> 财务
                    </a>
                </li>

                <li class="dropdown- notifications-alert _none">
                    <a href="javascript:void(0);" class="modal-show--for--order--item-create"
                       data-form-id="form--for--order--item-edit"
                       data-modal-id="modal--for--order--item-edit"
                       data-title="添加工单"
                    >
                        <i class="fa fa-plus text-yellow"></i> 工单
                    </a>
                </li>

                <!-- 导入 -->
                <?php if(in_array($me->staff_category,[0])): ?>
                <li class="dropdown tasks-menu add-menu _none-">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-plus"></i>
                    </a>
                    <ul class="dropdown-menu">

                        
                        <?php if(in_array($me->user_type,[0])): ?>
                        <li class="header">导入</li>
                        <?php endif; ?>

                        <?php if(in_array(env('APP_ENV'),['local']) || in_array($me->staff_position,[0,1,9])): ?>
                        <li class="header">
                            <a href="javascript:void(0);" class="modal-show--for--driver-import"
                               data-form-id="form--for--driver--import--by-excel"
                               data-modal-id="modal--for--driver--import--by-excel"
                               data-title="导入司机"
                            >
                                <i class="fa fa-file-excel-o text-yellow"></i> 导入司机
                            </a>
                        </li>
                        <li class="header">
                            <a href="javascript:void(0);" class="modal-show--for--car-import"
                               data-form-id="form--for--car--import--by-excel"
                               data-modal-id="modal--for--car--import--by-excel"
                               data-title="导入车辆"
                            >
                                <i class="fa fa-file-excel-o text-yellow"></i> 导入车辆
                            </a>
                        </li>
                        <li class="header">
                            <a href="javascript:void(0);" class="modal-show--for--order-import"
                               data-form-id="form--for--order--import--by-excel"
                               data-modal-id="modal--for--order--import--by-excel"
                               data-title="导入工单"
                            >
                                <i class="fa fa-file-excel-o text-yellow"></i> 导入工单
                            </a>
                        </li>
                            <li class="header">
                                <a href="javascript:void(0);" class="modal-show--for--fee-import"
                                   data-form-id="form--for--fee--import--by-excel"
                                   data-modal-id="modal--for--fee--import--by-excel"
                                   data-title="导入费用"
                                >
                                    <i class="fa fa-file-excel-o text-yellow"></i> 导入费用
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>
                <?php endif; ?>



                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu _none">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <!-- inner menu: contains the messages -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <!-- User Image -->
                                            <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                        </div>
                                        <!-- Message title and timestamp -->
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <!-- The message -->
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <!-- end message -->
                            </ul>
                            <!-- /.menu -->
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>
                <!-- /.messages-menu -->

                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu _none">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 10 notifications</li>
                        <li>
                            <!-- Inner Menu: contains the notifications -->
                            <ul class="menu">
                                <li><!-- start notification -->
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li>
                                <!-- end notification -->
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>

                <!-- Tasks Menu -->
                <li class="dropdown tasks-menu _none">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">9</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 9 tasks</li>
                        <li>
                            <!-- Inner menu: contains the tasks -->
                            <ul class="menu">
                                <li><!-- Task item -->
                                    <a href="#">
                                        <!-- Task title and progress text -->
                                        <h3>
                                            Design some buttons
                                            <small class="pull-right">20%</small>
                                        </h3>
                                        <!-- The progress bar -->
                                        <div class="progress xs">
                                            <!-- Change the css width attribute to simulate progress -->
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li>



                <li class="dropdown- notifications-alert _none">
                    <!-- Menu toggle button -->
                    <a href="javascript:void(0);" class="tab-control datatable-control"
                       data-type="create"
                       data-unique="y"
                       data-id="delivery-list"
                       data-title="交付列表"
                       data-content="交付列表"

                       data-datatable-type="create"
                       data-datatable-unique="y"
                       data-datatable-id="datatable-delivery-list"
                       data-datatable-target="delivery-list"
                       data-datatable-clone-object="delivery-list-clone"
                       data-datatable-reload="y"
                    >
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-danger notification-dom" style="width:16px;border-radius:50%;color:#dd4b39 !important;display: none;">•</span>
                    </a>
                </li>


                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <?php if(!empty($me->portrait_img)): ?>
                            <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$me->portrait_img))); ?>" class="user-image" alt="User">
                        <?php else: ?>
                            <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                        <?php endif; ?>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"><?php echo nl2br(e(isset($me->name) ? $me->name : '')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header" data-id="<?php echo nl2br(e(isset($me->id) ? $me->id : '0')); ?>" data-client-id="<?php echo nl2br(e(isset($me->client_id) ? $me->client_id : '0')); ?>">
                            <?php if(!empty($me->portrait_img)): ?>
                                <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$me->portrait_img))); ?>" class="user-image" alt="User">
                            <?php else: ?>
                                <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                            <?php endif; ?>

                            <p>
                                <small><?php echo nl2br(e(isset($me->name) ? $me->name : '')); ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body _none">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">

                                <a type="button" onclick="" class="btn btn-default modal-show--for--my-account--password-change"
                                   data-form-id="form--for--my-account--password-change"
                                   data-modal-id="modal--for--my-account--password-change"
                                   data-title="修改密码"
                                >
                                    <i class="fa- fa-plus--"></i> 修改密码
                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo nl2br(e(url('/logout'))); ?>" class="btn btn-default btn-flat">退出</a>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- Control Sidebar Toggle Button -->
                <li class="_none">
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>