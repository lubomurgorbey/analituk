<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>Analysis</title>
        <link rel="stylesheet" href="/css/fontawesome.min.css"/>
        <link rel="stylesheet" href="/css/bootstrap.min.css"/>
        <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.5.0/css/bootstrap4-toggle.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body>
    <div class="flex-center position-ref full-height">
        <div class="container mt-5">
            <form id="form-id"  method="post" action="{{ route('index')}}">
                {{csrf_field()}}
                <div class="input-group mb-3">
                    <input name="search" id="searchQuery" type="text" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button id="your-id" class="btn btn-outline-secondary" type="submit" >Search</button>
                    </div>
                </div>
            </form>
            <div class="page-header">
                <h3>All Sites</h3>
            </div>
            <div class="float-left">
                Знайдено <b>{{$pagination->total()}}</b> Сторінка <b>{{$pagination->currentPage()}}/ {{$pagination->lastPage()}}</b>
            </div>
            <div class="float-right">
                <div class="text-right">
                    <a href="" class="btn btn btn-primary btn-rounded mb-4" data-toggle="modal" data-target="#modalContactForm">Додати новий сайт</a>
                </div>
            </div>
            <table class="table table-center table-brd a-color">
                <thead class="thead-light">
                <tr>
                    <th scope="col">Назва сайта</th>
                    <th scope="col">Url</th>
                    <th scope="col"> Середный час відповіді</th>
                    <th scope="col">Cтатус провірки</th>
                    <th scope="col">Остання перевірка</th>
                    <th scope="col">Видалити</th>
                    <th scope="col">Сканувати</th>
                </tr>
                </thead>
                <tbody>
{{--                {{dd($sites)}}--}}
                @foreach ($sites as $site)
                    <tr>
                        <td><a href="{{ $site->status=='on' ? route('site', ['id' =>$site->id]):''}}">{{$site->site_name}} </a></td>
                        <td>{{$site->domain}}</td>
                        <td>{{$site->avg_time}}</td>
                        <td>{{$site->status}}</td>
                        <td>{{$mytime}}</td>
                        <td> <a type="button" class="btn btn-secondary" style="-webkit-appearance: none;"><i class="fas fa-edit" style="color: white"></i></a></td>
                        <td> <a type="button" onclick="scanSite({{$site->id}})" class="btn btn-secondary" style="-webkit-appearance: none;"><i class="fas fa-edit" style="color: white"></i></a></td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="content-pagination mb-4 d-flex justify-content-center">
                {{$pagination->links()}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalContactForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Добавлення сайта</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('addSite')}}"  >
                    {{ csrf_field() }}
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">
                        <i class="fas fa-user prefix grey-text"></i>
                        <input type="text" name="site_name" class="form-control validate" required>
                        <label data-error="wrong" data-success="right" for="form34">Назва сайту</label>
                    </div>

                    <div class="md-form mb-5">
                        <i class="fas fa-envelope prefix grey-text"></i>
                        <input type="url" id="form29" name="domain" class="form-control validate" required>
                        <label data-error="wrong"  data-success="right" for="form29">Домен</label>
                    </div>

                    <div class="md-form mb-5">
                        <input type="checkbox" name="status" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="secondary">
                        <label data-error="wrong" data-success="right" for="form29">Проаналізувати</label>
                    </div>

                </div>
                     <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-unique">Send <i class="fas fa-paper-plane-o ml-1"></i></button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.5.0/js/bootstrap4-toggle.min.js"></script>
    </body>
    <script>
        function scanSite(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/scan/' + id,
                dataType: 'json',
                type: 'post',
                success: function (response) {
                    if (response.success === true) {
                        console.log(response)
                    }
                }
            });
        }
    </script>
</html>
