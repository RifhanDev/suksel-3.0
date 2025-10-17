<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Zizaco\Entrust\Traits\EntrustUserTrait;
use Spatie\Permission\Traits\HasRoles;
use \Venturecraft\Revisionable\RevisionableTrait;
use App\Traits\EntrustCompatTrait;

use App\Role;

class User extends Authenticatable
{
	use Notifiable, HasRoles, EntrustCompatTrait;
	use RevisionableTrait;

	/**
	 * $show_authorize_flag
	 * 0 => all
	 * 1 => show mine only
	 * 2 => if i'm a head of ou, show all under my ou
	 * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
	 */
	public static $show_authorize_flag = 0;

	/**
	 * $update_authorize_flag
	 * 0 => all
	 * 1 => show mine only
	 * 2 => if i'm a head of ou, show all under my ou
	 * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
	 */
	public static $update_authorize_flag = 0;

	/**
	 * $delete_authorize_flag
	 * 0 => all
	 * 1 => show mine only
	 * 2 => if i'm a head of ou, show all under my ou
	 * 3 => if i'm a head of ou, show all under my ou and other entries under his ou's children
	 */
	public static $delete_authorize_flag = 0;

	protected $table = 'users';

	protected $fillable = [
		'username', // used in usercontroller@create
		'email',
		'tel',
		'department',
		'password',
		'confirmation_code',
		'confirmed',
		'vendor_id',
		'organization_unit_id',
		'name',
		'approved',
		'role_applied',
		'approver_id',
		'remark'
	];

	/**
	 * Validation Rules
	 */
	public static $_rules = [
		'store' => [
			'name'      => 'required',
			'email'     => 'required|email|unique:users,email',
			'password'  => [
				'required',
				'min:8',
				'confirmed',
				'regex:/^(?=.*[!@#$%^&*(),.?\":{}|<>_])(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{8,}$/'
				// 'regex:/^(? =.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{1,}$/u'
			],
			'password_confirmation' => 'required',
		],
		'update' => [
			'email' => 'required|email|unique:users,email',
		],
		'setPassword' => [
			'password'  => [
				'required',
				'min:8',
				'confirmed',
				'regex:/^(?=.*[!@#$%^&*(),.?\":{}|<>_])(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{8,}$/'
			],
			'password_confirmation' => 'required',
		],
		'setConfirmation' => [
			'confirmed' => 'numeric|min:0|max:1',
		],
		'changePassword' => [
			'password' => [
				'required',
				'min:8',
				'confirmed',
				'regex:/^(?=.*[!@#$%^&*(),.?\":{}|<>_])(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{8,}$/'
			],
			'password_confirmation' => 'required',
		],
		'emailResetPassword' => [
			'token' => 'required',
			'password' => [
				'required',
				'min:8',
				'confirmed',
				'regex:/^(?=.*[!@#$%^&*(),.?\":{}|<>_])(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{8,}$/'
			],
			'required|min:8|confirmed|alpha_num',
			'password_confirmation' => 'required',
		],
		'storeUser' => [
			'name' => 'required',
			'email' => 'required|email|email_domain|unique:users,email',
			'password' => [
				'required',
				'min:8',
				'confirmed',
				'regex:/^(?=.*[!@#$%^&*(),.?\":{}|<>_])(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{8,}$/'
			],
			'password_confirmation' => 'required',
			'organization_unit_id' => 'required'
		],
		'storeApproval' => [
			'email'    => 'required|email|unique:users,email',
			'approved' => 'required|boolean',
		],
	];

	public static $rules = [];

	public static function setRules($name)
	{
		self::$rules = self::$_rules[$name];
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function organizationunit()
	{
		return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id');
	}

	public function getAuthorizedUserids($authorization_flag)
	{
		if ($authorization_flag === 0) {
			return [];
		}

		if ($authorization_flag === 1) {
			return [$this->id];
		}

		$key = implode('.', ['User', 'getAuthorizedUserids', $this->id, $authorization_flag]);
		$user = $this;
		return Cache::tags(['User', 'OrganizationUnit'])->rememberForever(
			$key,
			function () use ($authorization_flag, $user) {
				$result = [$user->id];
				if ($user->organizationunit->user_id === $user->id) {
					if ($authorization_flag == 2) {
						$result = $user->organizationunit->users->lists('id');
					}
					if ($authorization_flag == 3) {
						$result = App\User::whereIn(
							'organization_unit_id',
							$user->organizationunit->descendantsAndSelf()->get()->lists('id')
						)->pluck('id');
					}
				}
				return $result;
			}
		);
	}

	public function isAuthorized($authorization_flag, $user_id)
	{
		if ($authorization_flag == 0) {
			return true;
		}
		$users = getAuthorizedUserids($authorization_flag);
		return in_array($user_id, $users);
	}

	public function getHiddenEmailAttribute()
	{
		$emails = explode('@', $this->email);
		$domain = $emails[1];
		$name = substr($emails[0], 0, 1) . str_repeat('*', strlen($emails[0]) - 1) . substr($emails[0], -1);
		return sprintf('%s@%s', $name, $domain);
	}

	/**
	 * Scope
	 */

	public function scopeActive($query)
	{
		return $query->where('confirmed', 1);
	}

	public function scopeNotActive($query)
	{
		return $query->where(function ($subquery) {
			$subquery->where('confirmed', 0)
				->orWhereNull('approved');
		});
	}

	public function scopePendingApproval($query)
	{
		if (auth()->user()->hasRole('Agency Admin')) {
			$query = $query->whereIn('role_applied', Role::availableRoles()->pluck('id'));
		}

		return $query
			->where('confirmed', 1)
			->whereNull('approved');
	}

	public function scopePendingReview($query)
	{
		return $query->whereNotNull('arr')->where('arr', 0);
	}

	public function scopeReviewed($query)
	{
		return $query->where('arr', 1);
	}

	/**
	 * ACL
	 */

	public static function canList()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['User:list']));
	}

	public static function canCreate()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['User:create']));
	}

	public function canShow()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['User:show']));
	}

	public function canUpdate()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['User:edit']));
	}

	public function canApprove()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['User:approve']));
	}

	public function canDelete()
	{
		return false;
	}

	public function canSetPassword()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['User:set_password']));
	}

	public function canSetConfirmation()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['User:set_confirmation']));
	}

	public function canLogin()
	{
		return (auth()->user() && auth()->user()->ability(['Admin'], ['User:login']));
	}

	public function vendor()
	{
		return $this->belongsTo('App\Vendor');
	}

	public function agency()
	{
		return $this->belongsTo('App\OrganizationUnit', 'organization_unit_id');
	}

	public function histories()
	{
		return $this->hasMany('App\UserHistory');
	}

	/**
	 * Other Functions
	 */

	public function status()
	{
		$statuses = [
			0 => 'Tidak Aktif',
			1 => 'Aktif'
		];
		return $statuses[$this->confirmed];
	}

	public function isEmailBlacklist()
	{
		$host = explode('@', trim($this->email));
		return count($host) == 2 && in_array($host[1], config('app.email_blacklists'));
	}

	public static function pendingReviewCount()
	{
		return self::pendingReview()
			->count();
	}

	public static function reviewedCount()
	{
		return self::reviewed()
			->count();
	}

	public static function pendingApprovalCount()
	{
		$query = self::whereNull('approved')
			->where('confirmed', 1)
			->whereNull('vendor_id');
		if (auth()->user()->hasRole('Agency Admin')) {
			$query->where('organization_unit_id', auth()->user()->organization_unit_id);
		}
		return $query->count();
	}

	public static function activeCount()
	{
		$query = self::where('approved', 1)
			->where('confirmed', 1)
			->whereNull('vendor_id');
		if (auth()->user()->hasRole('Agency Admin')) {
			$query->where('organization_unit_id', auth()->user()->organization_unit_id);
		}
		return $query->count();
	}

	public static function inactiveCount()
	{
		$query = self::where('approved', 1)
			->where('confirmed', 0)
			->whereNull('vendor_id');
		if (auth()->user()->hasRole('Agency Admin')) {
			$query->where('organization_unit_id', auth()->user()->organization_unit_id);
		}
		return $query->count();
	}

	public function updateUniques()
	{
		return true;
	}

	/**
	 * Decorators
	 */

	/**
	 * Boot
	 */

	public static function boot()
	{

		parent::boot();

		static::saving(function ($model) {
			$model->preSave();
		});

		static::saved(function ($model) {
			$model->postSave();
		});

		static::deleted(function ($model) {
			$model->preSave();
			$model->postDelete();
			$model->roles()->detach();
			UserHistory::log($model->id, 'delete', auth()->check() ? auth()->user()->id : null);
		});

		static::created(function ($model) {
			UserHistory::log($model->id, 'create', auth()->check() ? auth()->user()->id : null);
		});
	}
}
