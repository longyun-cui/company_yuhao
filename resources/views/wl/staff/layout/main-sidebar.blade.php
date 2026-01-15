{{--<!-- Left side column. contains the logo and sidebar -->--}}
<aside class="main-sidebar">

    {{--<!-- sidebar: style can be found in sidebar.less -->--}}
    <section class="sidebar">

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">


            {{--部门列表--}}
            @if(in_array($me->staff_type,[0,1,9,11]))
            <li class="treeview">
                <a class="tab-control datatable-control"
                   data-type="create"
                   data-unique="y"
                   data-id="department-list"
                   data-title='部门列表'
                   data-content='<i class="fa fa-columns text-white"></i> 部门列表'
                   data-icon='<i class="fa fa-columns text-blue"></i>'

                   data-datatable-type="create"
                   data-datatable-unique="y"
                   data-datatable-id="datatable-department-list"
                   data-datatable-target="department-list"
                   data-datatable-clone-object="department-list-clone"
                >
                    <i class="fa fa-columns text-white"></i>
                    <span>部门列表</span>
                </a>
            </li>
            @endif
            {{--团队列表--}}
            @if(in_array($me->staff_type,[0,1,9,11]))
                <li class="treeview">
                    <a class="tab-control datatable-control"
                       data-type="create"
                       data-unique="y"
                       data-id="team-list"
                       data-title='团队列表'
                       data-content='<i class="fa fa-sitemap text-white"></i> 团队列表'
                       data-icon='<i class="fa fa-sitemap text-blue"></i>'

                       data-datatable-type="create"
                       data-datatable-unique="y"
                       data-datatable-id="datatable-team-list"
                       data-datatable-target="team-list"
                       data-datatable-clone-object="team-list-clone"
                    >
                        <i class="fa fa-sitemap text-white"></i>
                        <span>团队列表</span>
                    </a>
                </li>
            @endif
            {{--员工列表--}}
            @if(in_array($me->staff_type,[0,1,9,11,81,84]))
            <li class="treeview">
                <a class="tab-control datatable-control"
                   data-type="create"
                   data-unique="y"
                   data-id="staff-list"
                   data-title='员工列表'
                   data-content='<i class="fa fa-user text-white"></i> 员工列表'
                   data-icon='<i class="fa fa-user text-blue"></i>'

                   data-datatable-type="create"
                   data-datatable-unique="y"
                   data-datatable-id="datatable-staff-list"
                   data-datatable-target="staff-list"
                   data-datatable-clone-object="staff-list-clone"
                >
                    <i class="fa fa-user text-white"></i>
                    <span>员工列表</span>
                </a>
            </li>
            @endif




            {{--车辆列表--}}
            @if(in_array($me->staff_type,[0,1,9,11,81,84]))
                <li class="treeview">
                    <a class="tab-control datatable-control"
                       data-type="create"
                       data-unique="y"
                       data-id="car-list"
                       data-title='车辆列表'
                       data-content='<i class="fa fa-truck text-white"></i> 车辆列表'
                       data-icon='<i class="fa fa-truck text-blue"></i>'

                       data-datatable-type="create"
                       data-datatable-unique="y"
                       data-datatable-id="datatable-car-list"
                       data-datatable-target="car-list"
                       data-datatable-clone-object="car-list-clone"
                    >
                        <i class="fa fa-truck text-white"></i>
                        <span>车辆列表</span>
                    </a>
                </li>
            @endif
            {{--司机列表--}}
            @if(in_array($me->staff_type,[0,1,9,11,81,84]))
                <li class="treeview">
                    <a class="tab-control datatable-control"
                       data-type="create"
                       data-unique="y"
                       data-id="driver-list"
                       data-title='司机列表'
                       data-content='<i class="fa fa-male text-white"></i> 司机列表'
                       data-icon='<i class="fa fa-male text-blue"></i>'

                       data-datatable-type="create"
                       data-datatable-unique="y"
                       data-datatable-id="datatable-driver-list"
                       data-datatable-target="driver-list"
                       data-datatable-clone-object="driver-list-clone"
                    >
                        <i class="fa fa-male text-white"></i>
                        <span>司机列表</span>
                    </a>
                </li>
            @endif

            
            
            {{--客户列表--}}
            @if(in_array($me->staff_type,[0,1,9,11,61]))
                <li class="treeview _none-">
                    <a class="tab-control datatable-control"
                       data-type="create"
                       data-unique="y"
                       data-id="client-list"
                       data-title='客户列表'
                       data-content='<i class="fa fa-user-secret text-white"></i> 客户列表'
                       data-icon='<i class="fa fa-user-secret text-blue"></i>'

                       data-datatable-type="create"
                       data-datatable-unique="y"
                       data-datatable-id="datatable-client-list"
                       data-datatable-target="client-list"
                       data-datatable-clone-object="client-list-clone"
                    >
                        <i class="fa fa-user-secret text-white"></i>
                        <span>客户列表</span>
                    </a>
                </li>
            @endif


            {{--项目列表--}}
            @if(in_array($me->staff_type,[0,1,9,11,41,61,71,81]))
            <li class="treeview _none-">
                <a class="tab-control datatable-control"
                   data-type="create"
                   data-unique="y"
                   data-id="project-list"
                   data-title='项目列表'
                   data-content='<i class="fa fa-cube text-white"></i> 项目列表'
                   data-icon='<i class="fa fa-cube text-blue"></i>'

                   data-datatable-type="create"
                   data-datatable-unique="y"
                   data-datatable-id="datatable-project-list"
                   data-datatable-target="project-list"
                   data-datatable-clone-object="project-list-clone"
                >
                    <i class="fa fa-cube text-white"></i>
                    <span>项目列表</span>
                </a>
            </li>
            @endif





            {{--工单列表--}}
            <li class="treeview _none-">
                <a class="tab-control datatable-control"
                   data-type="create"
                   data-unique="y"
                   data-id="order-list"
                   data-title='订单'
                   data-content='<i class="fa fa-file-text text-orange"></i> 订单列表'
                   data-icon='<i class="fa fa-file-text text-orange"></i>'

                   data-datatable-type="create"
                   data-datatable-unique="y"
                   data-datatable-id="datatable-order-list"
                   data-datatable-target="order-list"
                   data-datatable-clone-object="order-list-clone"
                >
                    <i class="fa fa-file-text text-orange"></i>
                    <span>订单列表</span>
                </a>
            </li>
            {{--费用列表--}}
            <li class="treeview">
                <a class="tab-control datatable-control"
                   data-type="create"
                   data-unique="y"
                   data-id="fee-list"
                   data-title='费用列表'
                   data-content='<i class="fa fa-cny text-red"></i> 费用列表'
                   data-icon='<i class="fa fa-cny text-red"></i>'

                   data-datatable-type="create"
                   data-datatable-unique="y"
                   data-datatable-id="datatable-fee-list"
                   data-datatable-target="fee-list"
                   data-datatable-clone-object="fee-list-clone"
                >
                    <i class="fa fa-cny text-red"></i>
                    <span>费用列表</span>
                </a>
            </li>
            {{--财务列表--}}
            <li class="treeview">
                <a class="tab-control datatable-control"
                   data-type="create"
                   data-unique="y"
                   data-id="finance-list"
                   data-title='成交记录'
                   data-content='<i class="fa fa-dollar text-red"></i> 财务列表'
                   data-icon='<i class="fa fa-dollar text-red"></i>'

                   data-datatable-type="create"
                   data-datatable-unique="y"
                   data-datatable-id="datatable-finance-list"
                   data-datatable-target="finance-list"
                   data-datatable-clone-object="finance-list-clone"
                >
                    <i class="fa fa-dollar text-red"></i>
                    <span>财务列表</span>
                </a>
            </li>



            <li class="treeview _none-">
                <a class="tab-control datatable-control"
                   data-type="create"
                   data-unique="y"
                   data-id="statistic-order-daily"
                   data-title='每日概览'
                   data-content='<i class="fa fa-line-chart text-green"></i> 每日概览'
                   data-icon='<i class="fa fa-line-chart text-green"></i>'

                   data-chart-id='chart-comprehensive-daily'

                   data-datatable-type="create"
                   data-datatable-unique="y"
                   data-datatable-id="datatable-statistic-order-daily"
                   data-datatable-target="statistic-order-daily"
                   data-datatable-clone-object="statistic-order-daily-clone"
                >
                    <i class="fa fa-line-chart text-green"></i>
                    <span>每日概览</span>
                </a>
            </li>



            {{--交付日报--}}
            @if(in_array($me->staff_type,[0,1,9,11,81,84]))
            <li class="treeview">
                <a class="tab-control datatable-control"
                   data-type="create"
                   data-unique="y"
                   data-id="delivery-daily"
                   data-title="交付日报"
                   data-content='<i class="fa fa-bar-chart text-blues"></i> 交付日报'
                   data-icon='<i class="fa fa-bar-chart text-blues"></i>'

                   data-datatable-type="create"
                   data-datatable-unique="y"
                   data-datatable-id="datatable-delivery-daily"
                   data-datatable-target="delivery-daily"
                   data-datatable-clone-object="delivery-daily-clone"

                   data-chart-id="eChart-delivery-daily"
                >
                    <i class="fa fa-bar-chart text-green"></i>
                    <span>交付日报</span>
                </a>
            </li>
            @endif
            {{--成交记录--}}
            @if(in_array($me->staff_type,[0,1,9,11,81,84,88]))
            @endif





            {{--财务日报--}}
            <li class="treeview _none">
                <a class="tab-control datatable-control"
                   data-type="create"
                   data-unique="y"
                   data-id="finance-daily"
                   data-title="财务日报"
                   data-content="财务日报"

                   data-datatable-type="create"
                   data-datatable-unique="y"
                   data-datatable-id="datatable-finance-daily"
                   data-datatable-target="finance-daily"
                   data-datatable-clone-object="finance-daily-clone"

                   data-chart-id="eChart-finance-daily"
                >
                    <i class="fa fa-pie-chart text-green"></i>
                    <span>财务日报</span>
                </a>
            </li>





        </ul>
        <!-- /.sidebar-menu -->
    </section>
    {{--<!-- /.sidebar -->--}}
</aside>