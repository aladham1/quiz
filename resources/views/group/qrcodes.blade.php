<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @foreach($group->exams as $exam)
        <div style="margin: auto; width: 200px;  margin-bottom: 40px">
            <h3 style="color: #4181a7">{{$exam->title}}</h3>
            <img src="data:image/png;base64,{!!  base64_encode(QrCode::format('png')->size(200)
                    ->generate(route('exams.intro', ['exam' => $exam->id]))); !!}" alt=""></div>
        <hr>
    @endforeach
</body>
</html>
