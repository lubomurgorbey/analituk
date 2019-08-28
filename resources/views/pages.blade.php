<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Analysis</title>
        <link rel="stylesheet" href="/css/fontawesome.min.css"/>
        <link rel="stylesheet" href="/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="/css/custom.css"/>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body>
    <div class="flex-center position-ref full-height">
        <div class="container mt-5">
            <a  href="/" class="btn btn-info float-right mb-4" type="submit" >На головну</a>
            <form id="form-id"  method="post" action="{{ route('site', ['id' =>$site_id])}}">
                {{csrf_field()}}
                <div class="input-group mb-3">
                    <input name="search" id="searchQuery" type="text" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button id="your-id" class="btn btn-outline-secondary" type="submit" >Search</button>
                    </div>
                </div>
            </form>
            <div class="d-flex justify-content-between">
            <div class="">
                <div class="page-header">
                    <h3>All Pages</h3>
                </div>
                Find <b>{{$pagination->total()}}</b> Page <b>{{$pagination->currentPage()}}/ {{$pagination->lastPage()}}</b>
            </div>
            <div class="">
                <div>Статуси відповіді</div>
                <form class="d-flex"  method="post" action="{{ route('site', ['id' =>$site_id])}}">
                    {{csrf_field()}}
                    @foreach($statusCode AS $item)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" name='status[{{$item->http_code}}]' type="checkbox" id="period" value="{{$item->http_code}}">
                            <label class="form-check-label" for="period"><span class="text-secondary small">{{$item->http_code}} -
                                    {{$item->total}}
                                </span></label>
                        </div>
                    @endforeach
                    <button id="your-id" class="btn btn-outline-secondary" type="submit" style="height: 35px;" >Search</button>
                </form>
            </div>
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
            <div class="float-left">
                <div class="d-flex">
                <p class="mr-2"><b>Середній час відклику - {{$avgTime}}</b></p>
            </div>
                <div class="d-flex">
                    <p class="mr-2"><b>Макс час відклику - {{$maxTime->total_time}} </b></p>
                    <a href="{{$maxTime->domain}}">{{$maxTime->domain}}</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    </body>
</html>
