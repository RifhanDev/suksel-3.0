<?php

function boolean_icon($value)
{
	$value = !!$value;
	return $value ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>';
}

function blank_icon($value)
{
	if (empty($value)) {
		return '<span class="glyphicon glyphicon-remove"></span>';
	} else {
		return $value;
	}
}


function tender_cidb_grade($grade, $user)
{
	if (empty($user) || empty($user->vendor)) {
		return sprintf('(%s) %s', $grade->code, $grade->name);
	}

	$vendor = $user->vendor;

	if ($vendor->cidbGrades()->whereCodeId($grade->id)->first()) {
		$format = '(%s) %s';
	} else {
		$format = '<span class="text-danger">(%s) %s <span class="glyphicon glyphicon-remove"></span></span>';
	}

	return sprintf($format, $grade->code, $grade->name);
}

function tender_vendor_codes($codes, $user)
{
	if (empty($user) || empty($user->vendor)) {
		return array_values($codes);
	}

	$vendor		= $user->vendor;
	$strings 	= [];

	foreach ($codes as $id => $string) {
		if ($vendor->vendorCodes()->whereCodeId($id)->first()) {
			$strings[] = $string;
		} else {
			$strings[] = '<span class="text-danger">' . $string . ' <span class="glyphicon glyphicon-remove"></span></span>';
		}
	}

	return $strings;
}

function str_random_unique($length)
{
	$available  = [2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
	$data       = $available;
	$string     = [];

	for ($i = 0; $i < $length; $i++) {
		$index = rand(0, count($data) - 1);
		$string[] = $data[$index];
		array_splice($data, $index, 1);

		if (count($data) == 0) {
			$data = $available;
		}
	}

	return implode('', $string);
}


/**
 * Convert an attributes array to an HTML attribute string.
 */
function html_attributes($attributes)
{
	if (empty($attributes) || !is_array($attributes)) {
		return '';
	}

	$parts = [];
	foreach ($attributes as $key => $value) {
		if (is_bool($value)) {
			if ($value) {
				$parts[] = ' ' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
			}
			continue;
		}

		$parts[] = ' ' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '"';
	}

	return implode('', $parts);
}


/**
 * Simple link builder.
 */
function link_to($url, $title = null, $attributes = [])
{
	$title = $title ?? $url;
	return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"' . html_attributes($attributes) . '>' . $title . '</a>';
}


/**
 * Create an anchor to a named route.
 * Usage: link_to_route('route.name', 'Label', $params, ['class'=>'btn'])
 */
function link_to_route($name, $title = null, $parameters = [], $attributes = [])
{
	if (is_scalar($parameters)) {
		$parameters = [$parameters];
	}

	// Use Laravel's route() if available, fall back to url composition
	if (function_exists('route')) {
		$href = route($name, $parameters);
	} else {
		$href = $name;
	}

	$title = $title ?? $href;
	return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"' . html_attributes($attributes) . '>' . $title . '</a>';
}


/**
 * Create an anchor to a controller action.
 * Usage: link_to_action('Controller@method', 'Label', $params, ['class'=>'btn'])
 */
function link_to_action($action, $title = null, $parameters = [], $attributes = [])
{
	if (is_scalar($parameters)) {
		$parameters = [$parameters];
	}

	if (function_exists('action')) {
		$href = action($action, $parameters);
	} else {
		$href = $action;
	}

	$title = $title ?? $href;
	return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"' . html_attributes($attributes) . '>' . $title . '</a>';
}


/**
 * Return a standardized AJAX denied JSON response.
 */
function _ajax_denied()
{
	if (function_exists('response')) {
		return response()->json(['error' => 'denied'], 403);
	}

	// fallback: return a simple JSON string
	header('Content-Type: application/json', true, 403);
	echo json_encode(['error' => 'denied']);
	exit;
}


/**
 * Return a standardized AJAX OK JSON response.
 */
function _ajax_ok($data = [])
{
	if (function_exists('response')) {
		return response()->json(array_merge(['status' => 'ok'], (array) $data), 200);
	}

	header('Content-Type: application/json', true, 200);
	echo json_encode(array_merge(['status' => 'ok'], (array) $data));
	exit;
}
