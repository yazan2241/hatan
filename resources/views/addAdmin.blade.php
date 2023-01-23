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
   
<header>
    <div class="px-3 bg-dark py-2 text-white">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
          <a href="/add" class="btn btn-success d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
           Add New Bank
          </a>

          <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
            <li>
              <a href="/dashboard" class="nav-link text-white" >
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-speedometer" viewBox="0 0 16 16">
                <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
                <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z"/>
              </svg>
                Dashboard
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>


<div class="container" style="width: 600px;">
   <form method="post" action="addAdminBank" enctype="multipart/form-data">
      <div class="form-group mt-3">
      <label for="bankName">Choose Blood bank Image :</label>
         <input class="form-control" name="bankImage" id="bankImage" type="file" required/>
         </div>
      <div class="form-group mt-3">
         <label for="bankName">Blood bank name :</label>
         <input class="form-control" id="bankName" type="text" name="name" value="" required/>
      </div>
      <div class="form-group mt-3">
         <label for="bankNumber">Phone number :</label>
         <input class="form-control" id="bankNumber" type="text" name="phone_number" value="" required/>
      </div>
      <div class="form-group mt-3">
         <label for="password">Password :</label>
         <input class="form-control" id="password" type="password" name="password" value="" required/>
      </div>
      <div class="form-group mt-3">
         <label for="">Blood bank address :</label>
         <input class="form-control" id="address" type="text" name="address" value=""/>
      </div>
      <div class="form-group mt-3">
         <label for="bankNumber">Facebook link :</label>
         <input class="form-control" id="bankNumber" type="text" name="facebook_link" value=""/>
      </div>
      <div class="form-group mt-3">
         <label for="bankNumber">instagrame link :</label>
         <input class="form-control" id="bankNumber" type="text" name="instagrame_link" value=""/>
      </div>
      
      <div id="mapholder" class="mt-3 text-center"></div>
      
      <div class="mt-3 text-center">
          <input type="button" onclick="getLocation();" value="Get Location"/>
          <input class="btn btn-success"  type="submit" value="submit"/>
      </div>
      

      <input class="form-control" id="banklat" type="hidden" name="address_latitude" value=""/>
      <input class="form-control" id="banklong" type="hidden" name="address_longitude" value=""/>
      
   </form>
</div>

</body>
</html>