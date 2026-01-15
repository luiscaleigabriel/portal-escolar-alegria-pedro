<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5"></div>

            <div class="col-lg-7">
                <div class="header-right">

                    <div class="profile-box ml-15">
                        <button class="dropdown-toggle bg-transparent border-0" data-bs-toggle="dropdown">
                            <img src="{{ Vite::asset('resources/template/images/profile/profile-image.png') }}">
                            <span>{{ auth()->user()->name }}</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a href="/profile">Perfil</a></li>
                            <li>
                                <form method="POST" action="/logout">@csrf
                                    <button class="dropdown-item">Sair</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
