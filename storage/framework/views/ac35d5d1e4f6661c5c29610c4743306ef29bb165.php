
<div class="content-wrapper">
    
    <section class="content-header _none">
        <h1>
            <?php echo $__env->yieldContent('header'); ?>
            <small><?php echo $__env->yieldContent('description'); ?></small>
        </h1>
        <ol class="breadcrumb">
            <?php echo $__env->yieldContent('breadcrumb'); ?>
        </ol>
    </section>

    
    <section class="content main-content" style="padding:8px 15px">
        <?php echo $__env->yieldContent('content'); ?> 
    </section>
    
</div>
