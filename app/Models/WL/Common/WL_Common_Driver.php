<?php
namespace App\Models\WL\Common;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class WL_Common_Driver extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

//    protected $connection = 'mysql0';
//    protected $connection = 'mysql_def';

    protected $table = "wl__common__driver";

    protected $fillable = [
        'active', 'status', 'result',
        'category', 'type', 'group',
        'item_active', 'item_status', 'item_result',
        'item_category', 'item_type', 'item_group',
        'driver_active', 'driver_status', 'driver_result',
        'driver_category', 'driver_type', 'driver_group',

        'login_number', 'password', 'wx_union_id',

        'owner_id', 'creator_id', 'updater_id', 'user_id', 'belong_id', 'source_id', 'object_id', 'p_id', 'parent_id',
        'superior_id',

        'name', 'username', 'nickname', 'true_name', 'short_name', 'alias_name',
        'title', 'subtitle', 'description', 'content', 'remark', 'tag', 'custom', 'custom2', 'custom3', 'attachment', 'portrait_img', 'cover_pic',

        'contact', 'contact_name', 'contact_phone', 'contact_email', 'contact_wx_id', 'contact_wx_qr_code_img', 'contact_address',
        'linkman', 'linkman_name', 'linkman_phone', 'linkman_email', 'linkman_wx_id', 'linkman_wx_qr_code_img', 'linkman_address',

        'motorcade_id',

        'driver_name',
        'driver_phone',
        'driver_address',
        'driver_title',
        'driver_entry_date',
        'driver_leave_date',
        'driver_ID',
        'driver_ID_front',
        'driver_ID_back',
        'driver_licence',
        'driver_certification',
        'driver_emergency_contact_name',
        'driver_emergency_contact_phone',
        'driver_job_performance',

        'copilot_name',
        'copilot_phone',
        'copilot_address',
        'copilot_title',
        'copilot_entry_date',
        'copilot_leave_date',
        'copilot_ID',
        'copilot_ID_front',
        'copilot_ID_back',
        'copilot_licence',
        'copilot_certification',
        'copilot_emergency_contact_name',
        'copilot_emergency_contact_phone',
        'copilot_job_performance',

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


    // 司机订单
    function driver_order_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','driver_id','id');
    }
    // 副驾司机订单
    function copilot_order_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Order','copilot_id','id');
    }




    // 附件
    function attachment_list()
    {
        return $this->hasMany('App\Models\WL\Common\W_CommonL_Attachment','item_id','id');
    }




    // 所属
    function parent()
    {
        return $this->belongsTo('App\Models\WL\Common\WL_Common_Staff','parent_id','id');
    }

    // 名下
    function children()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Staff','parent_id','id');
    }

    // 成员
    function members()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Staff','parent_id','id');
    }

    // 粉丝
    function fans()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Staff','parent_id','id');
    }

    // 名下客户
    function clients()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Common_Staff','parent_id','id');
    }

    // 与我相关的内容
    function fans_list()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Pivot_User_Relation','relation_user_id','id');
    }




    // 内容
    function items()
    {
        return $this->hasMany('App\Models\WL\Common\WL_Item','owner_id','id');
    }

    // 介绍
    function introduction()
    {
        return $this->hasOne('App\Models\WL\Common\WL_Item','id','introduction_id');
    }

    // 与我相关的内容
    function pivot_item()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Item','pivot_user_item','user_id','item_id')
            ->withPivot(['active','relation_active','type','relation_type'])->withTimestamps();
    }




    //
    function pivot_user()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Staff','pivot_user_user','user_1_id','user_2_id')
            ->withPivot(['active','relation_active','type','relation_type'])->withTimestamps();
    }

    // 与我相关的内容
    function pivot_relation()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Staff','pivot_user_relation','mine_user_id','relation_user_id')
            ->withPivot(['active','relation_active','type','relation_type'])->withTimestamps();
    }

    // 与我相关的内容
    function pivot_sponsor_list()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Staff','pivot_user_relation','mine_user_id','relation_user_id')
            ->withPivot(['active','relation_active','type','relation_type'])->withTimestamps();
    }

    // 与我相关的内容
    function pivot_org_list()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Staff','pivot_user_relation','relation_user_id','mine_user_id')
            ->withPivot(['active','relation_active','type','relation_type'])->withTimestamps();
    }

    // 与我相关的内容
    function pivot_follow_list()
    {
        return $this->belongsToMany('App\Models\WL\Common\WL_Common_Staff','pivot_user_relation','relation_user_id','mine_user_id')
            ->withPivot(['active','relation_active','type','relation_type'])->withTimestamps();
    }




    function children_keywords()
    {
        return $this->hasManyThrough(
            'App\Models\MT\SEOKeyword',
            'App\Models\MT\User',
            'pid', // 用户表外键...
            'createuserid', // 文章表外键...
            'id', // 国家表本地键...
            'id' // 用户表本地键...
        );
    }


}
