<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>ARTIFICIAL INTELEGENCE</title>

    <!-- Bootstrap core CSS -->
    <link href="{{url('/')}}/ass-portal//vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{url('/')}}/ass-portal/assets/css/fontawesome.css">
    <link rel="stylesheet" href="{{url('/')}}/ass-portal/assets/css/templatemo-sixteen.css">
    <link rel="stylesheet" href="{{url('/')}}/ass-portal/assets/css/owl.css">
    <link rel="icon" type="image/png" href="{{url('/')}}/ass-portal/assets/images/logo-pari.png"/>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>

  <body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->

    <!-- Header -->
    <header class="">
        @include('portal.layout.header')
    </header>

    @yield('content')

    @include('portal.layout.footer')
    <!-- Bootstrap core JavaScript -->
    <script src="{{url('/')}}/ass-portal/vendor/jquery/jquery.min.js"></script>
    <script src="{{url('/')}}/ass-portal/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


    <!-- Additional Scripts -->
    <script src="{{url('/')}}/ass-portal/assets/js/custom.js"></script>
    <script src="{{url('/')}}/ass-portal/assets/js/owl.js"></script>
    <script src="{{url('/')}}/ass-portal/assets/js/slick.js"></script>
    <script src="{{url('/')}}/ass-portal/assets/js/isotope.js"></script>
    <script src="{{url('/')}}/ass-portal/assets/js/accordions.js"></script>


    <script language = "text/Javascript"> 
      cleared[0] = cleared[1] = cleared[2] = 0; //set a cleared flag for each field
      function clearField(t){                   //declaring the array outside of the
      if(! cleared[t.id]){                      // function makes it static and global
          cleared[t.id] = 1;  // you could use true and false, but that's more typing
          t.value='';         // with more chance of typos
          t.style.color='#fff';
          }
      }
    </script>


  </body>

</html>