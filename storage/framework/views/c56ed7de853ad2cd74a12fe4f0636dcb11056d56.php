<?php $__env->startSection('head_title'); ?>
    
    <?php echo nl2br(e(isset($head_title) ? $head_title : '员工系统')); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection('title'); ?><span class="box-title">员工系统</span><?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?><span class="box-title">员工系统</span><?php $__env->stopSection(); ?>
<?php $__env->startSection('header','员工系统'); ?>





<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">


        <div class="nav-tabs-custom" id="index-nav-box">

            
            <ul class="nav nav-tabs">
                <li class="nav-item active" id="home">
                    <a href="#tab-home" data-toggle="tab" aria-expanded="true" id="home-default">首页</a>
                </li>
            </ul>


            
            <div class="tab-content">

                <div class="tab-pane active" id="tab-pane-width" style="width:100%;">
                    &nbsp;
                </div>

            </div>

        </div>


    </div>
</div>




<div class="component-container _none">

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.company.company-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.department.department-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.team.team-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car-location-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car--statistic--task-amount', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car--statistic--task-recent', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car--statistic--external-task-recent', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.client.client-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.project.project-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.project.project--statistic--task-recent', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-list--for--duplicate', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-list--for--financial', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--page--item--comprehensive', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.fee.fee-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.finance.finance-list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.export.export', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-order.statistic-view-of-order-by-daily', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-client.statistic-view-of-client-by-daily', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-project.statistic-view-of-project-by-daily', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-car.statistic-view-of-car-by-daily', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-driver.statistic-view-of-driver-by-daily', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

</div>


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.my-account.my-account--password-change', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.company.company-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.company.company--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.department.department-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.department.department--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.team.team-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.team.team--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.staff.staff--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car-import--by-excel', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-import--by-excel', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.driver.driver--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.client.client-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.client.client--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.project.project-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.project.project--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-import--by-excel', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-follow-create', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-journey-create', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-fee-create', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-fee-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-financial-accounting-set', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.fee.fee-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.fee.fee-import--by-excel', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.fee.fee--item-finance-bookkeeping', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.fee.fee--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.finance.finance-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.finance.finance--item-operation-record', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.finance.finance-operation', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->stopSection(); ?>




<?php $__env->startSection('custom-style'); ?>
<style>
</style>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('custom-js'); ?>
    <script src="<?php echo nl2br(e(asset('/resource/component/js/echarts-5.4.1.min.js'))); ?>"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom-script'); ?>


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.my-account.my-account-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.company.company-edit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.company.company-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.company.company-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.company.company--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.department.department-edit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.department.department-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.department.department-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.department.department--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.team.team-edit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.team.team-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.team.team-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.team.team--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-edit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.staff.staff--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    

    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car-edit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car-location-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car--statistic--task-amount--datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car--statistic--task-recent--datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.car.car--statistic--external-task-recent--datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-edit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.driver.driver--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.client.client-edit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.client.client-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.client.client-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.client.client--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.project.project-edit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.project.project-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.project.project-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.project.project--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.project.project--statistic--task-recent--datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-edit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-list-datatable--for--duplicate', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-list-datatable--for--financial', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-journey-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-fee-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--page--item--operation-record--datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--page--item--fee-record--datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.module.order.order--page--script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.fee.fee-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.fee.fee-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.fee.fee--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.finance.finance-list-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.finance.finance-list-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.finance.finance--item-operation-record-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.export.export-datatable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.export.export-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-client.statistic-datatable-of-client-by-daily-for-order', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-client.statistic-datatable-of-client-by-daily-for-fee', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-project.statistic-datatable-of-project-by-daily-for-order', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-project.statistic-datatable-of-project-by-daily-for-fee', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-order.statistic-datatable-of-order-by-daily', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-car.statistic-datatable-of-car-by-daily-for-order', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-car.statistic-datatable-of-car-by-daily-for-fee', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-driver.statistic-datatable-of-driver-by-daily-for-order', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make(env('TEMPLATE_WL_STAFF').'component.statistic-driver.statistic-datatable-of-driver-by-daily-for-fee', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<script>
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make(env('TEMPLATE_WL_STAFF').'layout.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>