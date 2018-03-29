<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
            <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector : "#mytext",
            height   : 400,
            width    : 700,
            //toolbar: 'undo redo | styleselect | bold italic | link image',
            plugins : 'codesample link image hr table textcolor contextmenu lists charmap preview anchor spellchecker searchreplace code textcolor',
            toolbar1  : 'undo redo styleselect bold italic forecolor backcolor alignleft aligncenter alignright charmap preview anchor spellchecker searchreplace ',
             toolbar2 :'bullist numlist outdent indent hr blockquote table tabledelete textcolor codesample code link unlink image source',
        });
    </script>

  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Company name</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <button class="btn btn-danger" onclick="event.preventDefault();
          document.getElementById('logout_form').submit();" >Sign out</button>
            <form style="display:hidden" action="{{route('logout')}}" id="logout_form" method="post" >@csrf</form>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Index
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>
                <a class="nav-link" href="#">
                  <span data-feather="file"></span>
                  Orders
                </a>

              </li>
            
            </ul>

          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
        @yield('content')
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/jquery.min.js')}}" ></script>
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
</body>
</html>