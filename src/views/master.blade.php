<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Amazings - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{ Asset::styles() }}
</head>

<body>

    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="brand" href="{{ URL::route('admin.home') }}">Amazings - Admin Dashboard</a>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li {{ Request::is('admin') ? 'class="active"' : '' }}>{{ link_to_route('admin.home', 'Home') }}</li>
                        <li {{ Request::is('admin/products*') ? 'class="active"' : '' }}>{{ link_to_route('admin.products.index', 'Producten') }}</li>
                        <li class="dropdown {{ Request::is('admin/terms*') ? 'active' : '' }}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Taxonomie&euml;n
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li {{ Request::is('admin/terms/afdeling') ? 'class="active"' : '' }}>{{ link_to_route('admin.terms.index', 'Afdelingen', ['afdeling']) }}</li>
                                <li {{ Request::is('admin/terms/categorie') ? 'class="active"' : '' }}>{{ link_to_route('admin.terms.index', 'Categorie&euml;n', ['categorie']) }}</li>
                                <li {{ Request::is('admin/terms/merk') ? 'class="active"' : '' }}>{{ link_to_route('admin.terms.index', 'Merken', ['merk']) }}</li>
                                <li {{ Request::is('admin/terms/kleur') ? 'class="active"' : '' }}>{{ link_to_route('admin.terms.index', 'Kleuren', ['kleur']) }}</li>
                                <li {{ Request::is('admin/terms/size') ? 'class="active"' : '' }}>{{ link_to_route('admin.terms.index', 'Maten', ['size']) }}</li>
                            </ul>
                        </li>
                    </ul>
                    <p class="navbar-text pull-right">Hallo {{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        @if(Session::has('validation'))
            <div class="alert alert-error">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                Het product is nog niet toegevoegd, zie de fouten hieronder.
            </div>
        @endif

        @if(Session::has('success'))
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                {{ Session::get('success') }}
            </div>
        @endif

        @if(Session::has('info'))
            <div class="alert alert-info">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                {{ Session::get('info') }}
            </div>
        @endif

        @if(Session::has('errors'))
            <div class="alert alert-danger">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                {{ implode('<br>', Session::get('errors')->all()) }}
            </div>
        @endif

        @yield('container')

    </div>

    @yield('templates')

    {{ Asset::scripts() }}

</body>
</html>