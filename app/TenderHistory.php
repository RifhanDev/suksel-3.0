<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenderHistory extends Model
{

	public $timestamps = false;

	protected $fillable = [
		'action',
		'user_id',
		'tender_id',
		'created_at'
	];
	
	public function user() {
		return $this->belongsTo('App\User');
	}
	
	public function tender()
	{
	return $this->belongsTo('App\Tender');
	}
	
	public static function log($tender_id, $action, $user_id=null) {
		if(array_key_exists($action, self::$types)) {
				if(empty($user_id) && auth()->check()) {
					$user_id = auth()->user()->id;
			}
		
			$history = new self([
				'action'    => $action,
				'tender_id' => $tender_id,
				'user_id'   => $user_id,
				'created_at' => now()
			]);
		
			try {
				return $history->save();   
			} catch (Exception $e) {
				return false;
			}
		}
	}
	
	// public static function boot() {
	// 	static::creating( function ($model) {
	// 		$model->setCreatedAt($model->freshTimestamp());
	// 	});
	// }
	
	public function getLabelAttribute() {
		if(array_key_exists($this->action, self::$types)) {
			return self::$types[$this->action];
		} else {
			boolean_icon(null);
		}
	}
	
	static $types = [
		'create'             => 'Masukkan Tender Baru',
		'edit'               => 'Kemaskini Tender',
		'delete'             => 'Hapus Tender',
		'publish'            => 'Siar Tender',
		'publish-prices'     => 'Siar Carta Tender',
		'publish-winner'     => 'Siar Penender Berjaya',
		'unpublish'          => 'Batal Siar',
		'unpublish-prices'   => 'Batar Siar Carta Tender',
		'unpublish-winner'   => 'Batal Siar Penender Berjaya',

		'update-invites'     => 'Kemaskini Senarai Jemputan',
		'update-vendors'     => 'Kemaskini Maklumat Syarikat',
		'exception'     	 => 'Kebenaran Khas',

		'rate-vendor-tender' => 'Penilaian Syarikat Vendor',
		
		'add-pkk'            => 'Tambah Kod PKK',
		'edit-pkk'           => 'Kemaskini Kod PKK',
		'delete-pkk'         => 'Hapus Kod PKK',
		'add-cidb'           => 'Tambah Maklumat CIDB',
		'edit-cidb'          => 'Kemaskini Maklumat CIDB',
		'delete-cidb'        => 'Hapus Maklumat CIDB',
		'add-mof'            => 'Tambah Kod Bidang MOF',
		'edit-mof'           => 'Kemaskini Kod Bidang MOF',
		'delete-mof'         => 'Kemaskini Kod Bidang MOF',
		'add-visit'          => 'Tambah Lawatan Tapak',
		'edit-visit'         => 'Kemaskini Lawatan Tapak',
		'delete-visit'       => 'Hapus Lawatan Tapak',
	];
}
