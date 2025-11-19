<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart {

	public static function count() {
		$items = session('cart_items');
		if(is_null($items)) return 0;
			return count($items);
	}

}
