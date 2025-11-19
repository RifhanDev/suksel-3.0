<?php App\Libraries\Asset::push('css', 'timeline'); ?>
<?php App\Libraries\Asset::push('js', 'timeline'); ?>
<h3>Komen</h3>
<br>
<script>
    var vendorId = <?=$vendor->id?>;
    var userId = <?=Auth::user()->id?>;
</script>
<ul class="timeline" ng-controller="timelineController">
    <li ng-show="!isShow">
        <div ng-show="newRemark.thumbsup == 1" class="timeline-badge success"><i class="glyphicon glyphicon-thumbs-up"></i></div>
        <div ng-show="newRemark.thumbsup == -1" class="timeline-badge danger"><i class="glyphicon glyphicon-thumbs-down"></i></div>
        <div ng-show="newRemark.thumbsup == 0" class="timeline-badge info"><i class="glyphicon glyphicon-comment"></i></div>
        <div class="timeline-panel">
            <div class="timeline-heading">
                <h4 class="timeline-title">Tinggalkan Komen</h4>
            </div>
            <div class="timeline-body">
                <textarea id="remark-text" ng-model="newRemark.remarks" class="form-control" rows="4"></textarea>
                <br>
                <div>
                    <label for="rating-success" class="input-badge-label">
                        <input id="rating-success" type="radio" name="vendor-rating" ng-model="newRemark.thumbsup" value="1">
                        <div class="input-badge success"><i class="glyphicon glyphicon-thumbs-up"></i></div>
                    </label>
                    <label for="rating-info" class="input-badge-label">
                        <input id="rating-info" type="radio" name="vendor-rating" ng-model="newRemark.thumbsup" value="0">
                        <div class="input-badge info"><i class="glyphicon glyphicon-comment"></i></div>
                    </label>
                    <label for="rating-danger" class="input-badge-label">
                        <input id="rating-danger" type="radio" name="vendor-rating" ng-model="newRemark.thumbsup" value="-1">
                        <div class="input-badge danger"><i class="glyphicon glyphicon-thumbs-down"></i></div>
                    </label>
                    <button type="button" ng-click="createRemarks()" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </li>
    <li ng-repeat="remark in remarks">
        <div ng-show="remark.thumbsup == 1" class="timeline-badge success"><i class="glyphicon glyphicon-thumbs-up"></i></div>
        <div ng-show="remark.thumbsup == -1" class="timeline-badge danger"><i class="glyphicon glyphicon-thumbs-down"></i></div>
        <div ng-show="remark.thumbsup == 0" class="timeline-badge info"><i class="glyphicon glyphicon-comment"></i></div>
        <div class="timeline-panel">
            <div class="timeline-heading">
                <h4 class="timeline-title">{{remark.user.username}} menulis...</h4>
                <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <span am-time-ago="remark.created_at"></span></small></p>
            </div>
            <div class="timeline-body">
                <p  ng-bind-html="remark.remarks | nl2br"></p>
            </div>
            <div ng-show="!isShow">
                <div ng-show="remark.isEditing">
                    <br>
                    <textarea ng-model="remark.remarks" class="form-control" rows="4"></textarea>
                    <br>
                    <div>
                        <label for="rating-success" class="input-badge-label">
                            <input id="rating-success" type="radio" name="vendor-rating-{{$index}}" ng-model="remark.thumbsup" value="1">
                            <div class="input-badge success"><i class="glyphicon glyphicon-thumbs-up"></i></div>
                        </label>
                        <label for="rating-info" class="input-badge-label">
                            <input id="rating-info" type="radio" name="vendor-rating-{{$index}}" ng-model="remark.thumbsup" value="0">
                            <div class="input-badge info"><i class="glyphicon glyphicon-comment"></i></div>
                        </label>
                        <label for="rating-danger" class="input-badge-label">
                            <input id="rating-danger" type="radio" name="vendor-rating-{{$index}}" ng-model="remark.thumbsup" value="-1">
                            <div class="input-badge danger"><i class="glyphicon glyphicon-thumbs-down"></i></div>
                        </label>
                        <button type="button" ng-click="saveEdittedRemark($index)" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <div ng-show="!remark.isEditing && remark.user_id == userId">
                    <br>
                    <div>
                        <button type="button" ng-click="remark.isEditing = !remark.isEditing" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i></button>
                        <button type="button" ng-click="removeRemark($index)" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
<div class="clearfix"></div>
<br>