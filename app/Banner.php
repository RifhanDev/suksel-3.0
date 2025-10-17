<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
   protected $fillable = [
        	'title',
        	'link',
        	'published'
   ];

   public static $rules = array(
        	'title' => 'required'
   );

   public static function canList() {
        	if(auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        	} else {
            return false;
        	}
   }

   public function canShow() {
        	return true;   
   }

   public static function canCreate() {
        	if(auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        	} else {
            return false;
        	}
   }

   public function canUpdate() {
        	if(auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        	} else {
            return false;
        	}
   }

   public function canDelete() {
        if(auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        	} else {
            return false;
        	}
   }

   public function file() {
        	return $this->morphOne('App\Upload', 'uploadable');
   }

	public static function boot() {
		parent::boot();
		
		self::saved(function($banner){
		Upload::setRules('store');
		
		$file = request()->file('file');
		
			if($file && $file->isValid()) {
				Upload::where('uploadable_type', 'App\Banner')->where('uploadable_id', $banner->id)->delete();
				
				$upload = [];
				$upload['path']     = public_path() . '/uploads/banners/';
				$upload['url']      = url('uploads/banners');
				$upload['name']     = $file->getClientOriginalName();
				$upload['size']     = $file->getSize();
				$upload['type']     = $file->getMimeType();
				$upload['public']   = 0;
				$upload['label']    = 'Banner #' . $banner->id;
				
				$upload['cr_approved']      = 0;
				$upload['uploadable_type']  = 'App\Banner';
				$upload['uploadable_id']    = $banner->id;
				
				$file->move($upload['path'], $upload['name']);
				
				$new_upload = new Upload;
				$new_upload->fill($upload);
				$new_upload->save();
			}
		});
	}
}
