<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid mt-4">
    <div class="row text-center">
        @foreach($group->exams as $exam)
            <div class="col-3 mb-3">
                <h3 style="color: #4181a7">{{$exam->title}}</h3>
                <img height="100" src="data:image/png;base64,{!!  base64_encode(QrCode::format('png')->size(200)
                    ->generate(route('exams.intro', ['exam' => $exam->id]))); !!}" alt="">
            </div>
        @endforeach
    </div>
</div>


</body>
</html>
