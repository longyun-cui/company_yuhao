<style>
    .select2-container { height:100%; border-radius:0; float:left; }
    .select2-container .select2-selection--single { border-radius:0; }
    .select2-container--classic .select2-selection--multiple  { height:32px; border-radius:0; }
    .sidebar-menu>li>a {
        /*padding: 12px 4px 12px 8px;*/
        font-size:12px;
    }
    .sidebar-menu>li>a span {
        font-size:12px;
    }
    @media (min-width: 768px) {
        .main-sidebar, .left-side {
            width: 150px;
        }
        .content-wrapper, .right-side, .main-footer {
            margin-left: 150px;
        }
        .main-header .logo {
            width: 150px;
        }
        .main-header .navbar {
            margin-left: 150px;
        }
        .form-horizontal .control-label {
            padding-right: 2px;
        }
    }
    @media (min-width: 768px) {
    }

    .nav-tabs-custom>.nav-tabs>li.active { border-top-color: #605ca8; }

    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 4px 0; }
    .form-box .table>tbody>tr>th,
    .form-box .table>tfoot>tr>th,
    .form-box .table>thead>tr>th { padding-top:8px; font-weight:normal; color:#aaa; }
    .form-box .table>tbody>tr>td,
    .form-box .table>tfoot>tr>td,
    .form-box .table>thead>tr>td { padding:0; }
    .form-box input { font-color:#000; font-weight:bold; background-color:#f8f8f8; }

    .statistics-row input { border:0; }

    .select2-container .select2-selection--single { height:30px; }
    .select2-container .select2-selection--single .select2-selection__rendered { margin-top: 0; }
    .select2-container--classic .select2-selection--single .select2-selection__arrow { height:28px; }



    .datatable-search-row { font-size:12px; }
    .datatable-search-row .btn-filter { padding: 4px; }
    .datatable-search-row .btn-filter { height:32px; height:32px; font-size:12px; padding:0 12px; margin:2px; float:left; }
    .datatable-search-row .time-picker-move { width:30px; padding:0; text-align:center; }
    .datatable-search-row .time-picker-move.picker-move-pre { margin-right:-3px; }
    .datatable-search-row .time-picker-move.picker-move-next { margin-left:-4px; }

    .datatable-search-row .search-filter { width:100px; height:32px; margin:2px; border:1px solid #ddd; font-size:12px; text-align:center; float:left; }
    .datatable-search-row .search-filter.filter-xs { width:60px; }
    .datatable-search-row .search-filter.filter-sm { width:80px; }
    .datatable-search-row .search-filter.filter-md { width:120px; }
    .datatable-search-row .search-filter.filter-lg { width:140px; }
    .datatable-search-row .search-filter.filter-xl { width:160px; }
    .datatable-search-row .search-filter.filter-xxl { width:200px; }



    .datatable-search-row .select2-container { height:32px; margin:2px; }
    .datatable-search-row .select2-container .select2-selection { height:32px; line-height:28px; }
    .datatable-search-row .select2-container .select2-selection__rendered,
    .datatable-search-row .select2-container .select2-selection__arrow { height:30px !important; line-height:30px !important; font-size:12px;  }
    .datatable-search-row .select2-container .select2-selection__choice  { margin-top:3px; height:24px;line-height:24px; font-size:12px; }
    .datatable-search-row .select2-container .select2-selection__choice__remove { height:24px;line-height:24px; }
    .datatable-search-row .select2-container .select2-search { height:24px; line-height:24px; }




    .datatable-search-row .dropdown-menu { position:absolute; width:400px; top:-4px; left:auto; right:76px; padding:4px; }
    .datatable-search-row .pull-left .dropdown-menu { position:absolute; width:400px; top:-4px; left:auto; padding:4px; }
    .datatable-search-row .pull-right .dropdown-menu { position:absolute; width:400px; top:-4px; left:auto; padding:4px; }

    .dropdown.filter-menu { position:relative; float:left;}
    .pull-left .dropdown.filter-menu { position:relative; float:right; }
    .pull-left .dropdown.filter-menu .dropdown-toggle { margin-left:4px; }
    .pull-right .dropdown.filter-menu { position:relative; float:left; }
    .pull-right .dropdown.filter-menu .dropdown-toggle { margin-right:4px; }

    .dropdown.filter-menu .box-body { padding:10px 0; margin:0; border-bottom:1px solid #eee; }
    .dropdown.filter-menu .box-body label { padding-right:8px; margin-bottom:0; line-height:28px; text-align:right; }
    .dropdown.filter-menu .box-body .filter-body { height:100%; }
    .dropdown.filter-menu .box-body .filter-body input { width:100%; text-align:center; height:28px; line-height:28px; }
    .dropdown.filter-menu .box-body .filter-body select { width:100%; height:30px; line-height:28px; }
    .dropdown.filter-menu .box-body .filter-body .select2-container { width:100% !important; height:30px; }
    .dropdown.filter-menu .box-body .filter-body .select2-container .select2-selection { height:28px; line-height:28px; }
    .dropdown.filter-menu .box-body .filter-body .select2-container .select2-selection__rendered,
    .dropdown.filter-menu .box-body .filter-body .select2-container .select2-selection__arrow { height:26px !important; line-height:26px !important; }
    .dropdown.filter-menu .box-body .filter-body .select2-container .select2-selection__choice  { margin-top:8px; height:22px;line-height:22px; }
    .dropdown.filter-menu .box-body .filter-body .select2-container .select2-selection__choice__remove { height:22px;line-height:22px; }
    .dropdown.filter-menu .box-body .filter-body .select2-container .select2-search { height:22px; line-height:22px; }

    .dropdown.filter-menu .divider { margin:6px 0; }
    .dropdown.filter-menu .box-footer { border-top:0; }
    .dataTables_wrapper.no-footer .dataTables_scrollBody { border-bottom:0 }



    .tableArea.full table { width:100% !important; min-width:1380px; }
    .tableArea.table-delivery table { min-width:2400px; }
    .tableArea.table-dental table { min-width:2200px; }
    .tableArea.table-luxury table { min-width:2600px; }
    .tableArea { margin-top:16px; }
    .tableArea tr.operating { background:#99bbff !important; }
    .tableArea tr.operating td { background:#99bbff !important; }
    .tableArea table ._total { font-weight:bold; color:green; }

    .tableArea.table-order table { min-width:3400px; }
    .tableArea.table-order-finance table { min-width:4000px; }
    .tableArea.table-car-list table { min-width:4000px; }
    .tableArea.table-driver-list table { min-width:2400px; }

    .tableArea table label { margin-bottom:0; }
    .tableArea table input[type=checkbox] { width:20px; height:14px; margin-top:4px; }


    .close-tab { margin-left:4px; cursor: pointer; opacity: 0.6; transition: opacity 0.3s; }
    .close-tab:hover { opacity: 1; }
    /*.tab-control { min-width: 50px; }*/

    .form-group { margin-bottom: 6px; }
    /*.btn-group { height: 28px; }*/
    .form-group .btn { padding: 6px 8px; }
    .btn-group .btn { padding: 2px 12px; }
    .form-control { height: 30px; }


    .main-content .callout .callout-body span { margin-right:12px; }

    .toggle-button {
        position: relative;
        width: 50px;
        height: 25px;
        background-color: #ccc;
        border: none;
        border-radius: 15px;
    }

    .toggle-handle {
        position: absolute;
        top: 0;
        width: 25px;
        height: 25px;
        background-color: #fff;
        border-radius: 50%;
    }

    .toggle-button.toggle-button-on { background-color: #66a3cc; transition: background-color 0.1s; }
    .toggle-button.toggle-button-off { background-color: #dddddd; transition: background-color 0.1s; }

    .toggle-button.toggle-button-on .toggle-handle { right: 0; background-color: #20e28b; transition: right 0.1s; }
    .toggle-button.toggle-button-off .toggle-handle { left: 0; background-color: #e00000; transition: left 0.1s; }

</style>