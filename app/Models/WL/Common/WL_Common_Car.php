<?php
namespace App\Models\WL\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WL_Common_Car extends Model
{
    use SoftDeletes;
    //
    protected $table = "wl__common__car";
    protected $fillable = [
        'active', 'status', 'result',
        'category', 'type', 'group',
        'item_active', 'item_status', 'item_result',
        'item_category', 'item_type', 'item_group',
        'car_active', 'car_status', 'car_result',
        'car_category', 'car_type', 'car_group',

        'owner_id', 'creator_id', 'updater_id', 'user_id', 'belong_id', 'source_id', 'object_id', 'p_id', 'parent_id',

        'name', 'username', 'nickname', 'true_name', 'short_name', 'alias_name',
        'title', 'subtitle', 'description', 'content', 'remark', 'tag', 'custom', 'custom2', 'custom3', 'attachment', 'portrait_img', 'cover_pic',

        'contact', 'contact_name', 'contact_phone', 'contact_email', 'contact_wx_id', 'contact_wx_qr_code_img', 'contact_address',
        'linkman', 'linkman_name', 'linkman_phone', 'linkman_email', 'linkman_wx_id', 'linkman_wx_qr_code_img', 'linkman_address',

        'motorcade_id',
        'trailer_id',

        'driver_id',
        'copilot_id',

        'trailer_type',
        'trailer_length',
        'trailer_volume',
        'trailer_weight',
        'trailer_axis_count',

        'car_info_type',
        'car_info_owner',
        'car_info_function',
        'car_info_brand',
        'car_info_identification_number',

        'car_info_engine_number',
        'car_info_locomotive_wheelbase',
        'car_info_main_fuel_tank',
        'car_info_auxiliary_fuel_tank',
        'car_info_total_mass',
        'car_info_curb_weight',
        'car_info_load_weight',
        'car_info_traction_mass',
        'car_info_overall_size',
        'car_info_purchase_date',
        'car_info_purchase_price',
        'car_info_sale_date',
        'car_info_sale_date',
        'car_info_registration_date',
        'car_info_issue_date',
        'car_info_inspection_validity',
        'car_info_transportation_license_validity',
        'car_info_transportation_license_change_time',
        'ETC_account',

        'visit_num', 'share_num', 'favor_num',  'follow_num', 'fans_num',
    ];
    protected $dateFormat = 'U';

    protected $hidden = ['content','custom'];

    protected $dates = ['created_at','updated_at','deleted_at'];
//    public function getDates()
//    {
////        return array(); // 原形返回；
//        return array('created_at','updated_at');
//    }


    // 拥有者
    function owner()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','owner_id','id');
    }
    // 创作者
    function creator()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','creator_id','id');
    }
    // 创作者
    function updater()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','updater_id','id');
    }
    // 创作者
    function completer()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','completer_id','id');
    }
    // 用户
    function user()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','user_id','id');
    }




    // 车队
    function motorcade_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Motorcade','motorcade_id','id');
    }
    // 车挂
    function trailer_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Car','trailer_id','id');
    }

    // 驾驶员（主驾）
    function driver_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Driver','driver_id','id');
    }

    // 驾驶员（副驾）
    function copilot_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Driver','copilot_id','id');
    }




    // 车辆订单
    function car_order_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','car_id','id');
    }
    // 车挂订单
    function trailer_order_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','trailer_id','id');
    }
    // 车辆订单【当前】
    function car_order_list_for_current()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','car_id','id');
    }
    // 车挂订单【当前】
    function trailer_order_list_for_current()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','trailer_id','id');
    }
    // 车辆订单【已完成】
    function car_order_list_for_completed()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','car_id','id');
    }
    // 车挂订单【已完成】
    function trailer_order_list_for_completed()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','trailer_id','id');
    }
    // 车辆订单【未来】
    function car_order_list_for_future()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','car_id','id');
    }
    // 车挂订单【未来】
    function trailer_order_list_for_future()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','trailer_id','id');
    }




    // 附件
    function attachment_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Attachment','item_id','id');
    }




    // 其他人的
    function pivot_item_relation()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Pivot_User_Item','item_id','id');
    }

    // 其他人的
    function others()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Pivot_User_Item','item_id','id');
    }

    // 收藏
    function collections()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Pivot_User_Collection','item_id','id');
    }

    // 转发内容
    function forward_item()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Item','item_id','id');
    }




    // 与我相关的话题
    function pivot_collection_item_users()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Staff','pivot_user_item','item_id','user_id');
    }




    // 一对多 关联的目录
    function menu()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Menu','menu_id','id');
    }

    // 多对多 关联的目录
    function menus()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Menu','pivot_menu_item','item_id','menu_id');
    }


    /**
     * 获得此文章的所有评论。
     */
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'itemable');
    }

    /**
     * 获得此文章的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }
}
