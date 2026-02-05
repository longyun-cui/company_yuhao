<?php
namespace App\Models\WL\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WL_Common_Fee extends Model
{
    use SoftDeletes;
    //
    protected $table = "wl__common__fee";
    protected $fillable = [
        'active', 'status', 'result',
        'category', 'type', 'group',
        'item_active', 'item_status', 'item_result',
        'item_category', 'item_type', 'item_group',

        'fee_active', 'fee_status', 'fee_result',
        'fee_category', 'fee_type', 'fee_group',

        'is_published', 'publisher_id', 'published_at', 'published_date', 'published_datetime',
        'is_verified', 'verifier_id', 'verified_at', 'verified_date', 'verified_datetime',
        'is_audited', 'auditor_id', 'audited_at', 'audited_date', 'audited_datetime',

        'is_confirmed', 'confirmer_id', 'confirmed_at', 'confirmed_date', 'confirmed_datetime',
        'is_completed', 'completer_id', 'completed_at', 'completed_date', 'completed_datetime',

        'is_recorded', 'recorder_id', 'recorded_at', 'recorded_date', 'recorded_datetime',

        'create_type',
        'owner_active',
        'owner_id', 'creator_id', 'updater_id', 'user_id', 'belong_id', 'source_id', 'object_id', 'p_id', 'parent_id',
        'superior_id',

        'menu_id',
        'item_id',

        'company_id',
        'department_id',
        'team_id',

        'client_id',
        'project_id',

        'order_id',
        'order_operation_record_id',
        'order_task_date',

        'car_id',
        'trailer_id',
        'driver_id',
        'copilot_id',

        'finance_id',

        'fee_category',
        'fee_type',
        'fee_time',
        'fee_date',
        'fee_datetime',
        'fee_payment_method',
        'fee_amount',
        'fee_account',
        'fee_account_from',
        'fee_account_to',
        'fee_reference_no',
        'fee_attachment_url',
        'fee_title',
        'fee_description',

        'funds_total',
        'funds_balance',
        'funds_available',
        'funds_init_freeze',
        'funds_freeze',

        'name', 'username', 'nickname', 'true_name', 'short_name', 'alias_name',
        'title', 'subtitle', 'description', 'content', 'remark', 'tag', 'custom', 'custom2', 'custom3', 'attachment', 'portrait_img', 'cover_pic',

        'visit_num',
        'share_num',
        'favor_num',

        'follow_num',
        'fans_num',

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
    // 创建者
    function creator()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','creator_id','id');
    }
    // 更新者
    function updater()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','updater_id','id');
    }
    // 完成者
    function completer()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','completer_id','id');
    }
    // 完成者
    function confirmer()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','confirmer_id','id');
    }
    // 用户
    function user()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','user_id','id');
    }




    // 客户
    function client_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Client','client_id','id');
    }

    // 客户
    function project_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Project','project_id','id');
    }

    // 订单
    function order_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Order','order_id','id');
    }

    // 车辆
    function car_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Car','car_id','id');
    }

    // 驾驶员
    function driver_er()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Driver','driver_id','id');
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
