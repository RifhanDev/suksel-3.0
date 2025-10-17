@if($user->ability(['Admin', 'Registration Assessor'], []))
    	<div class="row">
        	<div class="col-sm-6">
            <div class="panel panel-primary text-center">
                	<div class="panel-body">
                    	<h1>{{	number_format(App\User::pendingReviewCount(), 0) }}</h1>
                	</div>
                	<div class="panel-heading">
                    	<p style="color:white; margin:0; padding:0;">
                        Semak Akaun<br>
                        Belum Selesai
                    	</p>
                	</div>
            	</div>
        	</div>
        	<div class="col-sm-6">
            <div class="panel panel-primary text-center">
                	<div class="panel-body">
                    	<h1>{{number_format(App\User::reviewedCount(), 0)}}</h1>
                	</div>
                	<div class="panel-heading">
                    	<p style="color:white; margin:0; padding:0;">
                        Semak Akaun<br>
                        Selesai
                    	</p>
                	</div>
            </div>
        	</div>
    	</div>

@endif