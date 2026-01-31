<?php
namespace App\Models\WL\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class WL_Common_Transport_Journey extends Model
{
    use SoftDeletes;
    //
    protected $table = "wl__common__transport__journey";
    protected $fillable = [
        'active', 'status', 'result',
        'category', 'type', 'group',
        'item_active', 'item_status', 'item_result',
        'item_category', 'item_type',  'item_group',

        'journey_active', 'journey_status', 'journey_result',
        'journey_category', 'journey_type', 'journey_group',

        'is_published', 'publisher_id', 'published_at', 'published_date',
        'is_verified', 'verifier_id', 'verified_at', 'verified_date',
        'is_audited', 'auditor_id', 'audited_at', 'audited_date',
        'is_completed', 'completer_id', 'completed_at', 'completed_date',

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

        'driver_name',
        'driver_phone',
        'copilot_name',
        'copilot_phone',


        'transport_departure_place',
        'transport_stopover_place',
        'transport_destination_place',
        'transport_distance',
        'transport_time_limitation',
        'transport_actual_mileage',
        'transport_actual_duration',

        'should_departure_datetime',
        'should_arrival_datetime',
        'actual_departure_datetime',
        'actual_arrival_datetime',
        'stopover_arrival_datetime',
        'stopover_departure_datetime',

        'is_GPS',
        'is_delay',

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
        return $this->belongsTo('App\Models\YH\YH_User','owner_id','id');
    }
    // 创作者
    function creator()
    {
        return $this->belongsTo('App\Models\YH\YH_User','creator_id','id');
    }
    // 创作者
    function updater()
    {
        return $this->belongsTo('App\Models\YH\YH_User','updater_id','id');
    }
    // 创作者
    function completer()
    {
        return $this->belongsTo('App\Models\YH\YH_User','completer_id','id');
    }
    // 用户
    function user()
    {
        return $this->belongsTo('App\Models\YH\YH_User','user_id','id');
    }


    // 客户
    function client_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Client','client_id','id');
    }


    // 车辆
    function car_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Car','car_id','id');
    }
    // 车挂
    function trailer_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Car','trailer_id','id');
    }
    // 车厢
    function container_er()
    {
        return $this->belongsTo('App\Models\YH\YH_Car','container_id','id');
    }




    // 附件
    function attachment_list()
    {
        return $this->hasMany('App\Models\YH\YH_Attachment','order_id','id');
    }




    // 其他人的
    function pivot_item_relation()
    {
        return $this->hasMany('App\Models\YH\YH_Pivot_User_Item','item_id','id');
    }

    // 其他人的
    function others()
    {
        return $this->hasMany('App\Models\YH\YH_Pivot_User_Item','item_id','id');
    }

    // 收藏
    function collections()
    {
        return $this->hasMany('App\Models\YH\YH_Pivot_User_Collection','item_id','id');
    }

    // 转发内容
    function forward_item()
    {
        return $this->belongsTo('App\Models\YH\YH_Item','item_id','id');
    }




    // 与我相关的话题
    function pivot_collection_item_users()
    {
        return $this->belongsToMany('App\Models\YH\YH_User','pivot_user_item','item_id','user_id');
    }




    // 一对多 关联的目录
    function menu()
    {
        return $this->belongsTo('App\Models\YH\YH_Menu','menu_id','id');
    }

    // 多对多 关联的目录
    function menus()
    {
        return $this->belongsToMany('App\Models\YH\YH_Menu','pivot_menu_item','item_id','menu_id');
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




    /**
     * 自定义更新
     */
//    public function update_batch_in($setColumn,$setValue,$whereColumn,$whereValue)
//    {
//        $sql ="UPDATE ".$this->table." SET ".$setColumn." = ".$setValue." WHERE ".$whereColumn." = ".$whereValue;
//        return DB::update(DB::raw($sql);
//    }
    
    
}
