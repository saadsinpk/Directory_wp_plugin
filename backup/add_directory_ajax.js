<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

// $('#address').keyup(function () {
//     var address = $('#address').val();
//     $.ajax({
//         url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek",
//         type: 'GET',
//         dataType: 'json', // added data type
//         success: function (res) {
//             console.log(res);
//             var lattitude = res['results'][0]['geometry']['location']['lat'];
//             var longitude = res['results'][0]['geometry']['location']['lng'];
//             var location = lattitude + ',' + longitude;
//             $('#location_address').val(location);
//         }
//     });
// })

// $('#address').keyup(function() {var address = $('#address').val();$.ajax({url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek",type: 'GET',dataType: 'json', success: function(res) {console.log(res);var lattitude = res['results'][0]['geometry']['location']['lat'];var longitude = res['results'][0]['geometry']['location']['lng'];var location = lattitude + ',' + longitude;$('#location_address').val(location);}});})