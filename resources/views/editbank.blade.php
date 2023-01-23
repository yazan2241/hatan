<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
 
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
              <a href="{{ URL::to('/dashboard')}}" class="nav-link text-white" >
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

@foreach($bank as $bank)
<div class="container" style="width: 600px;">
   <form method="post" action="{{ URL::to('/editbank') }}" enctype="multipart/form-data">
      <div class="form-group mt-3">
      <label for="bankName">Choose Blood bank Image :</label>
         <input class="form-control" name="bankImage" id="bankImage" type="file" />
         </div>
      <div class="form-group mt-3">
         <label for="bankName">Blood bank name :</label>
         <input class="form-control" id="bankName" type="text" name="name" value= "{{ $bank->name }}"/>
      </div>
         <input type="hidden" class="form-control" id="bankNumber" type="text" name="phone_number" value="{{$bank->phone_number ?? ''}}"/>
      <div class="form-group mt-3">
         <label for="">Blood bank address :</label>
         <input class="form-control" id="address" type="text" name="address" value=""/>
      </div>
      <div class="form-group mt-3">
         <label for="bankNumber">Facebook link :</label>
         <input class="form-control" id="bankNumber" type="text" name="facebook_link" value="{{$bank->facebook_link ?? ''}}"/>
      </div>
      <div class="form-group mt-3">
         <label for="bankNumber">instagrame link :</label>
         <input class="form-control" id="bankNumber" type="text" name="instagrame_link" value="{{$bank->instagrame_link ?? ''}}"/>
      </div>
      
      
      <div class="form-group mt-3">
         <label for="address_longitude">Longitude position :</label>
         <input class="form-control" id="address_longitude" type="text" name="address_longitude" value="{{$bank->address_longitude ?? ''}}"/>
      </div>
      
      <div class="form-group mt-3">
         <label for="address_latitude">Latitude position :</label>
         <input class="form-control" id="address_latitude" type="text" name="address_latitude" value="{{$bank->address_latitude ?? ''}}"/>
      </div>
      
      
      <div class="mt-3 text-center">
          <input class="btn btn-success"  type="submit" value="submit"/>
      </div>
   
   </form>
</div>
@endforeach


<script>
      function initMap() {
        var test= {lat: 23.885942, lng: 45.079162};
        var map = new google.maps.Map(document.getElementById('mapholder'), {
          zoom: 14,
          center: test
        });
        var marker = new google.maps.Marker({
          position: test,
          map: map,
          draggable: true
        });
        marker.setMap(map);
        google.maps.event.addListener(map,'click',function(event) { 
         marker.setPosition(new google.maps.LatLng(event.latLng.lat() , event.latLng.lng()));
        });

       google.maps.event.addListener(marker,'dragend',function() {
         document.getElementById("banklat").value = marker.position.lat();
         document.getElementById("banklong").value = marker.position.lng();
       });
      }
    </script>

 <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBu-916DdpKAjTmJNIgngS6HL_kDIKU0aU&callback=initMap"
      defer
    ></script>
</body>
</html>