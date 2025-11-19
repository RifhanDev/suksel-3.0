<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificationType extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;

    /**
    * $show_authorize_flag
    * 0 => all
    * 1 => show mine only
    * 2 => if i'm a head of ou, show all under my ou
    * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
    */
    static $show_authorize_flag = 0;

    /**
    * $update_authorize_flag
    * 0 => all
    * 1 => show mine only
    * 2 => if i'm a head of ou, show all under my ou
    * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
    */
    static $update_authorize_flag = 0;

    /**
    * $delete_authorize_flag
    * 0 => all
    * 1 => show mine only
    * 2 => if i'm a head of ou, show all under my ou
    * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
    */
    static $delete_authorize_flag = 0;

    /**
    * Fillable columns
    */
    protected $fillable = [
        'name',

    ];

    /**
    * These attributes excluded from the model's JSON form.
    * @var array
    */
    protected $hidden = [
    // 'password'
    ];

    /**
    * Validation Rules
    */
    private static $_rules = [
        'store' => [
            'name' => 'required',

        ],
        'update' => [
            'name' => 'required',

        ]
    ];

    public static $rules = [];

    public static function setRules($name)
    {
        self::$rules = self::$_rules[$name];
    }

    /**
    * ACL
    */

    public static function canList() 
    {
        return (auth()->user() && auth()->user()->ability(['Admin', 'CertificationType Admin'], ['CertificationType:list']));
    }

    public static function canCreate() 
    {
        return (auth()->user() && auth()->user()->ability(['Admin', 'CertificationType Admin'], ['CertificationType:create']));
    }

    public function canShow()
    {
        $user = auth()->user();
        if(auth()->user() && auth()->user()->ability(['Admin', 'CertificationType Admin'], ['CertificationType:show']))
            return true;
        return false;
    }

    public function canUpdate() 
    {
        $user = auth()->user();
        if(auth()->user() && auth()->user()->ability(['Admin', 'CertificationType Admin'], ['CertificationType:edit']))
            return true;
        return false;
    }

    public function canDelete() 
    {
        $user = auth()->user();
        if(auth()->user() && auth()->user()->ability(['Admin', 'CertificationType Admin'], ['CertificationType:delete']))
            return true;
        return false;
    }

    /**
    * Relationships
    */
   
    // public function status()
    // {
    //     return $this->hasOne('Status');
    // }


    /**
    * Decorators
    */

    public function getNameAttribute($value)
    {
        return $value;
    }

    /**
    * Boot Method
    */

    public static function boot()
    {
        parent::boot();

        self::created(function(){
            Cache::tags('CertificationType')->flush();
        });

        self::updated(function(){
            Cache::tags('CertificationType')->flush();
        });

        self::deleted(function(){
            Cache::tags('CertificationType')->flush();
        });

        static::saving(function ($model) {
            $model->preSave();
        });
        static::saved(function ($model) {
            $model->postSave();
        });
        static::deleted(function ($model) {
            $model->preSave();
            $model->postDelete();
        });
    }
}
