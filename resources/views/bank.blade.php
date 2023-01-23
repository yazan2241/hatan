<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

</head>

<body>
    @foreach($user as $user)
    <div class="container">
        <div class="text-center">
            <h3 class="mt-3">{{ $user->name }}</h3>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="row mt-5">
                    <img src="{{ URL::to('/') }}/public/images/{{$user->image}}" height="400px" width="400px" />
                </div>
                <div class="row">
                    <form method="post" action="{{ URL::to('/send-notification') }}">
                        <label class="mt-3">Choose requested blood type : </label>
                        <select class="form-select mt-3" name="blood">
                            <option selected value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                        <input type="hidden" name="id" value="{{$user->id}}" />
                        <div class="text-center mt-3">
                            <button class="btn btn-success">Request</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-8 mt-5">
                <div class="row" style="width: 80%; float: right;">
                    <div class="col-md-4">
                        <h5>Donor name</h5>
                    </div>
                    <div class="col-md-4">
                        <h5>Donor phone</h5>
                    </div>
                    <div class="col-md-4">
                        <h5>Donor date</h5>
                    </div>
                </div>
                @foreach($res as $r)
                <div class="row mt-3" style="width: 80%; float: right;">
                    <div class="col-md-4 alert alert-warning text-center">
                        <p class="mt-3">{{ $r->name }}</p>
                    </div>
                    <div class="col-md-4 alert alert-warning text-center" >
                        <p class="mt-3">{{ $r->donor_phone }}</p>
                    </div>
                    <div class="col-md-4 alert alert-success text-center" >
                        <p class="mt-3">{{ $r->date }}</p>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>
    </div>
    @endforeach
</body>

</html>