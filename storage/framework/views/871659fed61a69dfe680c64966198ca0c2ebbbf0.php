<div class="row datatable-body datatable-wrapper driver-list-clone" data-datatable-item-category="driver" data-item-name="司机">


    <div class="col-md-12 datatable-search-row  datatable-search-box">


        <div class=" pull-left">

            <?php if(in_array($me->user_type,[0,1,9,11,19,41,81,61])): ?>
            <button type="button" onclick="" class="btn btn-filter modal-show--for--driver-item-create"
                    data-form-id="form--for--driver-item-edit"
                    data-modal-id="modal--for--driver-item-edit"
                    data-title="添加司机"
            >
                <i class="fa fa-plus"></i> 添加
            </button>
            <?php endif; ?>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-play"></i> 启用</button>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-stop"></i> 禁用</button>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-download"></i> 导出</button>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-trash-o"></i> 批量删除</button>

        </div>


        <div class="pull-right">


            <input type="text" class="search-filter form-filter filter-keyup" name="driver-id" placeholder="ID" />
            <input type="text" class="search-filter form-filter filter-keyup" name="driver-mobile" placeholder="工号" />
            <input type="text" class="search-filter form-filter filter-keyup" name="driver-name" placeholder="姓名" />
            <input type="text" class="search-filter form-filter filter-keyup" name="driver-phone" placeholder="电话" />

            <?php if(in_array($me->user_type,[0,1,9,11])): ?>
            <select class="search-filter form-filter filter-md select2-box-c" name="driver-type">
                <option value="-1">全部驾驶员</option>
                <option value="1">主驾</option>
                <option value="11">副驾</option>
            </select>
            <?php endif; ?>










            <select class="search-filter form-filter filter-md select2-box-c" name="driver-item-status">
                <option value ="-1">全部</option>
                <option value ="1">启用</option>
                <option value ="9">禁用</option>
            </select>


            <button type="button" class="btn btn-default btn-filter filter-submit">
                <i class="fa fa-search"></i> 搜索
            </button>

            <button type="button" class="btn btn-default btn-filter filter-empty">
                <i class="fa fa-remove"></i> 清空
            </button>

            <button type="button" class="btn btn-default btn-filter filter-refresh">
                <i class="fa fa-circle-o-notch"></i> 刷新
            </button>

            <button type="button" class="btn btn-default btn-filter filter-cancel">
                <i class="fa fa-undo"></i> 重置
            </button>


        </div>


    </div>


    <div class="col-md-12 datatable-body">
        <div class="box box-primary box-solid-" style="box-shadow:0 0;">

            <div class="box-header with-border- margin-top-16px padding-top-8px _none">
                <h3 class="box-title datatable-title"></h3>
            </div>

            <div class="box-body no-padding">
                <div class="tableArea margin-top-8px">
                    <table class='table table-striped table-bordered table-hover order-column'>
                        <thead>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>


</div>