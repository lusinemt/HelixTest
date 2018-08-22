<!DOCTYPE html>
<html>
<head>
    <title>Autocomplete Vue js using Laravel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyD6OPE42o29g-28EpTrMBqGWZ22maQoJ_Q&callback=initMap"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script>
</head>
<body>
<div class="container" id="app">
    <div class="row">
        <div class="col-sm-8">
            <h1>Autocomplete Form</h1>
            <div class="panel panel-default">
                <div class="panel-heading">Please Enter for Search</div>
                {!! Form::text('search_text', null, array( 'method' => 'GET','placeholder' => 'Search Text','class' => 'form-control','id'=>'search_text')) !!}
            </div>
            <div id="mymap" style="width:500px;height:500px;"></div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var url = "{{ route('autocomplete.search') }}";
    // var url = 'autocomplete/search';
    var latitude, longitude;
    $('#search_text').on('keyup', function () {
        $('#mymap').css('display', 'none');
    });
    $('#search_text').autocomplete({
        source: url,
        minlength: 1,
        autoFocus: true,
        select: function (e, ui) {
            $('#searchname').val(ui.item.value);
            latitude = ui.item.lat;
            longitude = ui.item.long;
            $.ajax({
                type: 'get',
                url: 'map?lat=' + latitude + '&long=' + longitude,
                success: function (response) {
                    $('#mymap').css('display', 'block');
                    var mymap = new GMaps({
                        el: '#mymap',
                        lat: latitude,
                        lng: longitude,
                        zoom: 10
                    });

                    $.each(response, function (index, value) {
                        mymap.addMarker({
                            lat: value.latitude,
                            lng: value.longitude,
                            title: value.name,
                            click: function (e) {
                                alert('This is ' + value.name);
                            }
                        });
                    });
                }
            });
        }
    });

</script>

</body>
</html>
