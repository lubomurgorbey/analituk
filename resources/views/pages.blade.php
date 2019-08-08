<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Analysis</title>
        <link rel="stylesheet" href="/css/fontawesome.min.css"/>
        <link rel="stylesheet" href="/css/bootstrap.min.css"/>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body>
    <div class="flex-center position-ref full-height">
        <div class="container mt-5">
            <form id="form-id"  method="post" action="{{ route('site', ['id' =>$site_id])}}">
                {{csrf_field()}}
                <div class="input-group mb-3">
                    <input name="search" id="searchQuery" type="text" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button id="your-id" class="btn btn-outline-secondary" type="submit" >Search</button>
                    </div>
                </div>
            </form>
            <div class="page-header">
                <h3>All Pages</h3>
            </div>
            <div class="float-left">
                Find <b>{{$pagination->total()}}</b> Page <b>{{$pagination->currentPage()}}/ {{$pagination->lastPage()}}</b>
            </div>
            <div class="float-right">

            </div>
            <table class="table table-center table-brd a-color">
                <thead class="thead-light">
                <tr>
                    <th scope="col">Url</th>
                    <th scope="col">Час відповіді</th>
                    <th scope="col">Cтатус відповіді</th>
                    <th scope="col">Остання перевірка</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($sites as $site)
                    <tr>
{{--                        <td><a href="{{ route('site', ['id' =>$site->id])}}">{{$site->site_name}} </a></td>--}}
                        <td>{{$site->domain}}</td>
                        <td>{{$site->total_time}}</td>
                        <td>{{$site->http_code}}</td>
                        <td>{{$mytime}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="content-pagination mb-4 d-flex justify-content-center">
                {{$pagination->links()}}
            </div>
        </div>
    </div>
    </body>
</html>
