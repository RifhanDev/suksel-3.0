@if(isset($organizationunit))
<div class="footer">
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <a title="Open in Google Maps" id="footer-map" href="https://www.google.com.my/maps?q={{str_replace(' ', '+', $organizationunit->name)}}" target="_blank" class="card has-tooltip" style="display:block; background-image: url(http://maps.googleapis.com/maps/api/staticmap?center={{str_replace(' ', '+', $organizationunit->name)}}&zoom=14&scale=2&size=640x250&maptype=roadmap&sensor=false&format=png&visual_refresh=true&markers=size:mid%7Ccolor:red%7C{{str_replace(' ', '+', $organizationunit->name)}})"></a>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="no-margin">Comments</h4>
                        <br>
                        <form role="form" method="post" action="{{action('CommentsController@store')}}">
                            <input type="hidden" name="organization_unit_id" value="{{$organizationunit->id}}">
                            <div class="form-group">
                                <label class="control-label" for="email">Your Email</label>
                                <input name="email" class="form-control" id="email" placeholder="Insert your email here" type="email">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="footer-comment">Comment
                                    <br>
                                </label>
                                <textarea name="body" id="footer-comment" class="form-control" placeholder="Place your comment here"></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning btn-raised">Submit</button>
                        </form>
                    </div>
                    <div class="col-md-6" id="footer-address">
                        <h4 class="no-margin">Hubungi Kami</h4>
                        <br>
                        <address>
                            <b>{{$organizationunit->name}}</b>
                            <br>
                            <br>
                            <?=$organizationunit->address?>
                        </address>
                        <p>{{$organizationunit->tel}}<br>
                            {{$organizationunit->email}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>
@endif
<div class="footer-darker">
    <br>
    Best Viewed in Google Chrome, Mozilla Firefox, IE11+
    <br>12,345 Visitors
    <br>
    <br>
</div>
<div style="height: 3px; background-color:#4285F4"></div>