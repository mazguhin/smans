<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smans / Панель управления</title>

    <!-- Styles -->
    <link href="{{ elixir('/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

    <style>
      div#fa-select {
        font-family: 'FontAwesome', 'sans-serif';
      }
    </style>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <!-- local styles -->
    <style>
      @yield('localcss')
    </style>

</head>
<body>
  <main class="container">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/dashboard">Smans</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Статьи <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/dashboard/article/create">Создать статью</a></li>
                <li><a href="/dashboard/article">Список статей</a></li>
                <li class="divider"></li>
                <li><a href="/dashboard/category/create">Создать категорию</a></li>
                <li><a href="/dashboard/category">Список категорий</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Пользователи <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/dashboard/user/create">Создать</a></li>
                <li><a href="/dashboard/user">Список</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Сотрудники <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/dashboard/staff/create">Создать сотрудника</a></li>
                <li><a href="/dashboard/staff">Список сотрудников</a></li>
                <li class="divider"></li>
                <li><a href="/dashboard/staff/category/create">Создать категорию</a></li>
                <li><a href="/dashboard/staff/category">Список категорий</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Клубы <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/dashboard/club/create">Создать клуб</a></li>
                <li><a href="/dashboard/club">Список клубов</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Блоки <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/dashboard/block/create">Создать</a></li>
                <li><a href="/dashboard/block">Список</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Меню <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <!-- <li><a href="/dashboard/menu/create">Создать</a></li> -->
                <li><a href="/dashboard/menu">Список</a></li>
              </ul>
            </li>

            <li>
              <a href="/dashboard/guestbook">Гостевая книга</a>
            </li>

            <li>
              <a href="/dashboard/setting">Настройки</a>
            </li>

          </ul>
          <ul class="nav navbar-nav navbar-right">

          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                  {{ Auth::user()->name }} <span class="caret"></span>
              </a>

              <ul class="dropdown-menu" role="menu">
                  <li>
                    <a href="/">Перейти на сайт</a>
                  </li>

                  <li>
                    <a href="/profile">Профиль</a>
                  </li>

                  <li>
                      <a href="{{ url('/logout') }}"
                          onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                          Выход
                      </a>

                      <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                          {{ csrf_field() }}
                      </form>
                  </li>
              </ul>
          </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="content">
      @yield('content')
    </div>
  </main>

  <script src="{{ elixir('/js/app.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

  <!-- local js -->
  <script>
    @yield('localjs')
  </script>
</body>
</html>
