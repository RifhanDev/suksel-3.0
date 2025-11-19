{!! App\Libraries\Asset::push('js', 'news') !!}
@if($global_news)
	<div id="announcements" class="portlet box blue-selangor">
	    	<div class="portlet-title">
	        	<div class="caption"><i class="fa fa-newspaper-o"></i> Berita Terkini</div>
	    	</div>
	    	<div class="portlet-body">
	        	<div id="announcements-ticker">
	            <div class="general-item-list">
	               	@foreach ($global_news as $news)
	                		<div class="item">
	                    		<div class="item-head">
	                        	<div class="item-details">
	                            	<a href="{{ asset('news/'.$news->id) }}">{{ $news->title }}</a>
	                            	<span class="item-label">{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('j M Y') }}</span>
	                        	</div>
	                    		</div>
	                		</div>
	                	@endforeach
	            </div>
	        	</div>
	        	<a href="/news" id="btn-view-all" class="btn btn-sm btn-block bg-blue-selangor">Lihat Semua</a>
	    	</div>
	</div>
@endif