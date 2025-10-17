<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Comment;

class CommentsController extends Controller
{
	/**
	 * Display a listing of comments
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		if(!Comment::canList()) {
			return $this->_access_denied();
		}
		if($request->ajax())
		{
			$users_under_me = auth()->user()->getAuthorizedUserids(Comment::$show_authorize_flag);
			if(empty($users_under_me)) {
				$comments = Comment::whereNotNull('comments.created_at');	
			} else {
				$comments = Comment::whereIn('comments.user_id', $users_under_me);	
			}
			$comments = $comments->select([
						'comments.id',
						'comments.organization_unit_id',
						'comments.email',
						'comments.body'
			]);
			return Datatables::of($comments)
						->addColumn('actions', function($comment){
							$actions   = [];
							$actions[] = $comment->canShow() ? link_to_action('comments.show', 'Show', $comment->id, ['class' => 'btn btn-xs btn-primary'] ) : '';
							$actions[] = $comment->canUpdate() ? link_to_action('comments.edit', 'Update', $comment->id, ['class' => 'btn btn-xs btn-default'] ) : '';
							$actions[] = $comment->canDelete() ? Former::open(action('comments.destroy', $comment->id))->class('form-inline') 
							. Former::hidden('_method', 'DELETE')
							. '<button type="button" class="btn btn-xs btn-danger confirm-delete">Delete</button>'
							. Former::close() : '';
							return implode(' ', $actions);
						})
				->removeColumn('id')
				->rawColumns(['organization_unit_id', 'email', 'body', 'actions'])
				->make();
			return Datatables::of($comments)->make();
		}
		return view('comments.index');
	}

	/**
	 * Show the form for creating a new comment
	 *
	 * @return Response
	 */
	public function create() {
		if($request->ajax()) {
			return $this->_ajax_denied();
		}
		if(!Comment::canCreate()) {
			return $this->_access_denied();
		}
		return view('comments.create');
	}

	/**
	 * Store a newly created comment in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request) {
		$data = $request->all();
		Comment::setRules('store');
		if(!Comment::canCreate()) {
			return $this->_access_denied();
		}
		$comment = new Comment;
		$comment->fill($data);
		if(!$comment->save()) {
			return $this->_validation_error($comment);
		}
		if($request->ajax()) {
			return response()->json($comment, 201);
		}
		return redirect('comments')->with('success', $this->created_message);
	}

	/**
	 * Display the specified comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id) {
		$comment = Comment::findOrFail($id);
		if(!$comment->canShow()) {
			return $this->_access_denied();
		}
		if($request->ajax()) {
			return response()->json($comment);
		}
		return view('comments.show', compact('comment'));
	}

	/**
	 * Show the form for editing the specified comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id) {
		$comment = Comment::findOrFail($id);
		if($request->ajax()) {
			return $this->_ajax_denied();
		}
		if(!$comment->canUpdate()) {
			return _access_denied();
		}
		return view('comments.edit', compact('comment'));
	}

	/**
	 * Update the specified comment in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id) {
		$comment = Comment::findOrFail($id);
		Comment::setRules('update');
		$data = Input::all();
		if(!$comment->canUpdate()) {
			return $this->_access_denied();
		}
		if(!$comment->update($data)) {
			return $this->_validation_error($comment);
		}
		if($request->ajax()) {
			return $comment;
		}
		session()->forget('_old_input');
		return redirect('comments/'.$id.'/edit')->with('success', $this->updated_message);
	}

	/**
	 * Remove the specified comment from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id) {
		$comment = Comment::findOrFail($id);
		if(!$comment->canDelete()) {
			return $this->_access_denied();
		}
		$comment->delete();
		if($request->ajax()) {
			return response()->json($this->deleted_message);
		}
		return redirect('comments')->with('success', $this->deleted_message);
	}

	/**
	 * Custom Methods. Dont forget to add these to routes: Route::get('example/name', 'ExampleController@getName');
	 */
	
	// public function getName()
	// {
	// }

	/**
	 * Constructor
	 */

	public function __construct() {
		// parent::__construct();
		// view()->share('controller', 'Comment');
	}
}
