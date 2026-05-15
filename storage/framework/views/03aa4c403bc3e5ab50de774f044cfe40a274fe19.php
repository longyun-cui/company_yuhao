<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" type="image/ico" href="<?php echo nl2br(e(env('FAVICON_WL_STAFF'))); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo nl2br(e(env('FAVICON_WL_STAFF'))); ?>">
    <link rel="icon" sizes="16x16 32x32 64x64" href="<?php echo nl2br(e(env('FAVICON_WL_STAFF'))); ?>">
    <link rel="icon" type="image/png" sizes="196x196" href="<?php echo nl2br(e(env('FAVICON_WL_STAFF'))); ?>">

    <title><?php echo $__env->yieldContent('head_title'); ?></title>
    <meta name="_token" content="<?php echo nl2br(e(csrf_token())); ?>"/>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    
    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/AdminLTE/bootstrap/css/bootstrap.min.css'))); ?>">

    <!-- Font Awesome -->
    
    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/font-awesome-4.5.0.min.css'))); ?>">

    <!-- Ionicons -->
    
    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/ionicons-2.0.1.min.css'))); ?>">

    <!-- Theme style -->
    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/AdminLTE/dist/css/AdminLTE.min.css'))); ?>">
    
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/AdminLTE/dist/css/skins/_all-skins.min.css'))); ?>">
    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/AdminLTE/dist/css/skins/skin-blue.min.css'))); ?>">

    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/AdminLTE/plugins/iCheck/all.css'))); ?>">
    
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    
    
    
    
    


    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/AdminLTE/plugins/datatables/dataTables.bootstrap.css'))); ?>">
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/jquery.dataTables-1.13.1.min.css'))); ?>">
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/fixedColumns.dataTables.min.css'))); ?>">

    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/fileinput-4.4.8.min.css'))); ?>">
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/fileinput-only.css'))); ?>">

    
    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/bootstrap-datetimepicker-4.17.47.min.css'))); ?>">
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/bootstrap-datepicker-1.9.0.min.css'))); ?>">

    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/bootstrap-switch-3.3.4.min.css'))); ?>">

    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/swiper-4.2.2.min.css'))); ?>">

    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/component/css/lightcase-2.5.0.min.css'))); ?>">

    
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/lib/css/select2-4.0.5.min.css'))); ?>">




    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/common/css/common.css'))); ?>" media="all" />
    <link rel="stylesheet" href="<?php echo nl2br(e(asset('/resource/common/css/AdminLTE/index.css'))); ?>">

    <?php echo $__env->yieldContent('css'); ?>
    <?php echo $__env->yieldContent('style'); ?>
    <?php echo $__env->yieldContent('custom-css'); ?>
    <?php echo $__env->yieldContent('custom-style'); ?>

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.layout-style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-purple sidebar-mini sidebar-collapse sidebar-expanded-on-hover">
<div class="wrapper main-wrapper">


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.main-header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.main-sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.main-content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.control-sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->



<script src="<?php echo nl2br(e(asset('/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js'))); ?>"></script>



<script src="<?php echo nl2br(e(asset('/AdminLTE/bootstrap/js/bootstrap.min.js'))); ?>"></script>



<script src="<?php echo nl2br(e(asset('/AdminLTE/dist/js/app.min.js'))); ?>"></script>

<script src="<?php echo nl2br(e(asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js'))); ?>"></script>
<script src="<?php echo nl2br(e(asset('AdminLTE/plugins/datatables/dataTables.bootstrap.min.js'))); ?>"></script>
<script src="<?php echo nl2br(e(asset('/resource/component/js/jquery.dataTables-1.13.1.min.js'))); ?>"></script>
<script src="<?php echo nl2br(e(asset('/resource/component/js/dataTables.fixedColumns.min.js'))); ?>"></script>




<script src="<?php echo nl2br(e(asset('/AdminLTE/plugins/iCheck/icheck.min.js'))); ?>"></script>


<script src="<?php echo nl2br(e(asset('/resource/component/js/jquery-ui-1.12.1.min.js'))); ?>"></script>




<script src="<?php echo nl2br(e(asset('/resource/component/js/layer-3.5.1/layer.js'))); ?>"></script>



<script src="<?php echo nl2br(e(asset('/resource/component/js/fileinput-4.4.8.min.js'))); ?>"></script>
<script src="<?php echo nl2br(e(asset('/resource/component/js/fileinput-only-1.js'))); ?>"></script>


<script src="<?php echo nl2br(e(asset('/resource/component/js/jquery.form-4.2.2.min.js'))); ?>"></script>


<script src="<?php echo nl2br(e(asset('/resource/component/js/moment-2.19.0-with-locales.min.js'))); ?>"></script>





<script src="<?php echo nl2br(e(asset('/resource/component/js/bootstrap-datetimepicker-4.17.47.min.js'))); ?>"></script>

<script src="<?php echo nl2br(e(asset('/resource/component/js/bootstrap-datepicker-1.9.0.min.js'))); ?>"></script>
<script src="<?php echo nl2br(e(asset('/resource/component/js/bootstrap-datepicker-1.9.0.zh-CN.min.js'))); ?>"></script>


<script src="<?php echo nl2br(e(asset('/resource/component/js/bootstrap-switch-3.3.4.min.js'))); ?>"></script>


<script src="<?php echo nl2br(e(asset('/resource/component/js/lightcase-2.5.0.min.js'))); ?>"></script>


<script src="<?php echo nl2br(e(asset('/resource/component/js/swiper-4.2.2.min.js'))); ?>"></script>


<script src="<?php echo nl2br(e(asset('/resource/component/js/select2-4.0.5.min.js'))); ?>"></script>


<script src="<?php echo nl2br(e(asset('/resource/component/js/echarts-5.4.1.min.js'))); ?>"></script>






<?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.layout-script-for-tab', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.layout-script-for-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.layout-script-for-item-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.layout-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php echo $__env->yieldContent('js'); ?>
<?php echo $__env->yieldContent('script'); ?>
<?php echo $__env->yieldContent('custom-js'); ?>
<?php echo $__env->yieldContent('custom-script'); ?>


</body>
</html>
