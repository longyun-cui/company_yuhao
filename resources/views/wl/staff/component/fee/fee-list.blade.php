<div class="row datatable-body datatable-wrapper fee-list-clone" data-datatable-item-category="fee" data-item-name="费用">


    <div class="col-md-12 datatable-search-row  datatable-search-box">


        <div class=" pull-left">

            @if(in_array($me->user_type,[0,1,9,11,19,41,81,61]))
            <button type="button" onclick="" class="btn btn-filter btn-success- item-create-modal-show"
                    data-form-id="form-for-fee-edit"
                    data-modal-id="modal-for-fee-edit"
                    data-title="添加费用"
            >
                <i class="fa fa-plus"></i> 添加
            </button>
            @endif
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-play"></i> 启用</button>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-stop"></i> 禁用</button>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-download"></i> 导出</button>
            <button type="button" onclick="" class="btn btn-default btn-filter _none"><i class="fa fa-trash-o"></i> 批量删除</button>

        </div>


        <div class="pull-right">


            <input type="text" class="search-filter form-filter filter-keyup" name="fee-id" placeholder="ID" />
            <input type="text" class="search-filter form-filter filter-keyup" name="fee-order-id" placeholder="订单ID" />
            <input type="text" class="search-filter form-filter filter-keyup" name="fee-title" placeholder="名目" />


            <select class="search-filter form-filter filter-md select2-box-c" name="fee-type">
                <option value="-1">全部类型</option>
                <option value="1">收入</option>
                <option value="99">费用</option>
                <option value="101">扣款</option>
                <option value="111">罚款</option>
            </select>

            <select class="search-filter form-filter filter-md select2-box-c" name="fee-is-recorded">
                <option value="-1">全部状态</option>
                <option value="0">待入账</option>
                <option value="1">已入账</option>
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
                <div class="tableArea full- margin-top-8px">
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