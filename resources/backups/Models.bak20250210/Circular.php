<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'pdf_link',
        'position',
        'published'
    ];

    public static $rules = array(
             'title' => 'required'
    );

    /* Relationship */

    public function file()
    {
        return $this->morphOne('App\Upload', 'uploadable');
    }

    /* Permission */



    public static function canList()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        } else {
            return false;
        }
    }

    public function canShow()
    {
        return true;
    }

    public static function canCreate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        } else {
            return false;
        }
    }

    public function canUpdate()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        } else {
            return false;
        }
    }

    public function canDelete()
    {
        if (auth()->check() && auth()->user()->ability(['Admin'], [])) {
            return true;
        } else {
            return false;
        }
    }
    
	public static function boot() {
		parent::boot();
		
		self::saved(function($circular){
		Upload::setRules('store');
		
		$file = request()->file('file');
		
			if($file && $file->isValid()) {
				Upload::where('uploadable_type', 'App\Models\Circular')->where('uploadable_id', $circular->id)->delete();
				
				$upload = [];
				$upload['path']     = public_path() . '/uploads/circulars/';
				$upload['url']      = url('uploads/circulars');
				$upload['name']     = $file->getClientOriginalName();
				$upload['size']     = $file->getSize();
				$upload['type']     = $file->getMimeType();
				$upload['public']   = 0;
				$upload['label']    = 'Circular #' . $circular->id;
				
				$upload['cr_approved']      = 0;
				$upload['uploadable_type']  = 'App\Models\Circular';
				$upload['uploadable_id']    = $circular->id;
				
				$file->move($upload['path'], $upload['name']);
				
				$new_upload = new Upload;
				$new_upload->fill($upload);
				$new_upload->save();
			}
		});
	}
}
