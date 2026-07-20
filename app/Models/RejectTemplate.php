<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RejectTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'applicable_0',
        'applicable_1',
        'applicable_2',
    ];

    public static function applicableDescription($num)
    {
        switch ($num) {
            case 0:
                return 'Pendaftaran/Kemaskini';
                break;
            case 1:
                return 'Pemulangan Semula';
                break;
            case 2:
                return 'Kebenaran Khas';
                break;
            
            default:
                return '';
                break;
        }
    }

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

    private static $_rules = [
        'store' => [
            'title' => 'required',
            'content' => 'required',
        ],
        'update' => [
            'title' => 'required',
            'content' => 'required',
        ]
    ];

    public static $rules = [];

    public static function setRules($name)
    {
        self::$rules = self::$_rules[$name];
    }

    public static function canList()
    {
        return (auth()->user() && auth()->user()->ability(['Admin', 'RejectTemplate Admin'], ['RejectTemplate:list']));
    }

    public static function canCreate()
    {
        return (auth()->user() && auth()->user()->ability(['Admin', 'RejectTemplate Admin'], ['RejectTemplate:create']));
    }

    public function canShow()
    {

        $user = auth()->user();
        if (auth()->user() && auth()->user()->ability(['Admin', 'RejectTemplate Admin'], ['RejectTemplate:show']))
            return true;
        return false;
    }

    public function canUpdate()
    {

        $user = auth()->user();
        if (auth()->user() && auth()->user()->ability(['Admin', 'RejectTemplate Admin'], ['RejectTemplate:edit']))
            return true;
        return false;
    }

    public function canDelete()
    {

        $user = auth()->user();
        if (auth()->user() && auth()->user()->ability(['Admin', 'RejectTemplate Admin'], ['RejectTemplate:delete']))
            return true;
        return false;
    }
}
