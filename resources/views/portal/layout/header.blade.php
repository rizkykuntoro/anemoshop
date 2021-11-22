<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="{{url('/')}}"><h2>Artificial <em>Intelligence</em></h2></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item {{Helpers::menuActiveChecker($_SERVER,url('/').'/')}}">
          <a class="nav-link" href="{{url('/')}}">Beranda
            <span class="sr-only">(current)</span>
          </a>
        </li> 
        <li class="nav-item {{Helpers::menuActiveChecker($_SERVER,url('/webinar/list'))}}">
          <a class="nav-link" href="{{url('/webinar/list')}}">Webinar</a>
        </li>
        <li class="nav-item {{Helpers::menuActiveChecker($_SERVER,url('/kontak-kami'))}}">
          <a class="nav-link" href="{{url('/kontak-kami')}}">Kontak Kami</a>
        </li>
        <li class="nav-item {{Helpers::menuActiveChecker($_SERVER,url('/tentang-kami'))}}">
          <a class="nav-link" href="{{url('/tentang-kami')}}">Tentang Kami</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
