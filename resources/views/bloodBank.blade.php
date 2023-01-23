<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript">
         function showLocation(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            document.getElementById("banklat").value = latitude;
            document.getElementById("banklong").value = longitude;
            var latlongvalue = position.coords.latitude + "," + position.coords.longitude;
            var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+latlongvalue+"&amp;zoom=14&amp;size=400x300&amp;key=AIzaSyAa8HeLH2lQMbPeOiMlM9D1VxZ7pbGQq8o";
            document.getElementById("mapholder").innerHTML ="<img src='"+img_url+"'>";
         }
         function errorHandler(err) {
            if(err.code == 1) {
               alert("Error: Access is denied!");
            } else if( err.code == 2) {
               alert("Error: Position is unavailable!");
            }
         }
         function getLocation(){
            if(navigator.geolocation){
               // timeout at 60000 milliseconds (60 seconds)
               var options = {timeout:60000};
               navigator.geolocation.getCurrentPosition
               (showLocation, errorHandler, options);
            } else{
               alert("Sorry, browser does not support geolocation!");
            }
         }
      </script>

</head>
<body>
   
<div class="container">
   <form method="post" action="{{URL::to('/')}}/bloodBank" enctype="multipart/form-data">
      <div class="form-group">
         <input class="form-control" name="bankImage" id="bankImage" type="file" />
         </div>
      <div class="form-group">
         <label for="bankName">Blood bank name :</label>
         <input class="form-control" id="bankName" type="text" name="name" value=""/>
      </div>
      <div class="form-group">
         <label for="bankNumber">Phone number :</label>
         <input class="form-control" id="bankNumber" type="text" name="phone_number" value=""/>
      </div>
      <div class="form-group">
         <label for="">Blood bank address :</label>
         <input class="form-control" id="address" type="text" name="address" value=""/>
      </div>
      <div class="form-group">
         <label for="bankNumber">Facebook link :</label>
         <input class="form-control" id="bankNumber" type="text" name="facebook_link" value=""/>
      </div>
      <div class="form-group">
         <label for="bankNumber">instagrame link :</label>
         <input class="form-control" id="bankNumber" type="text" name="instagrame_link" value=""/>
      </div>
      
      <div id="mapholder"></div>
      
      <input type="button" onclick="getLocation();" value="Get Location"/>
      

      <input class="form-control" id="banklat" type="hidden" name="address_latitude" value=""/>
      <input class="form-control" id="banklong" type="hidden" name="address_longitude" value=""/>
      <input type="submit" value="submit"/>
   </form>
</div>

</body>
</html>