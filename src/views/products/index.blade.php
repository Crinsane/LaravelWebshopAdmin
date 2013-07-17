@extends('admin::master')

@section('container')

<div class="page-header clearfix">
    <h1 class="pull-left">Product overzicht <small>Een overzicht van alle ingevoerde producten</small></h1>
    {{ link_to_route('admin.products.create', 'Nieuw Product', null, ['class' => 'btn btn-primary pull-right']) }}
</div>

<h5 class="pull-left">Naam begint met:
    @foreach(array_merge(['#'], range('A', 'Z')) AS $f)

        <a href="{{ URL::route('admin.products.index') }}?f=<?=($f == '#') ? 'num' : $f;?>">{{ $f }}</a>

    @endforeach
</h5>

{{ Form::open(['route' => 'admin.products.index', 'method' => 'get', 'class' => 'form-search pull-right']) }}
    {{ Form::text('s', '', ['class' => 'input-medium search-query', 'placeholder' => 'Zoeken...']) }}
    {{ Form::submit('Zoeken', ['class' => 'btn']) }}
{{ Form::close(); }}

@if(count($products))

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Afbeelding</th>
                <th>Naam</th>
                <th>Prijs</th>
                <th>Categorie</th>
                <th>Merk</th>
                <th>Afdeling</th>
                <th>Maat</th>
                <th>Kleur</th>
                <th>Voorraad</th>
                <th style="width:183px;">Acties</th>
            </tr>
        </thead>

        <tbody>

            @foreach($products as $product)

                <tr>
                    <td>
                        @if( ! $product->images->isEmpty())
                            <a href="{{ URL::route('admin.products.edit', [$product->id]) }}">
                                {{ HTML::image('assets/products/thumbnails/' . $product->images->first()->filename, $product->name, ['style' => 'max-width:80px', 'class' => 'img-polaroid']) }}
                            </a>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>&euro; {{ $product->price }}</td>
                    <td>{{ $product->getTerm('category') }}</td>
                    <td>{{ $product->getTerm('brand') }}</td>
                    <td>{{ $product->getTerm('department') }}</td>
                    <td>
                        <ul class="table_list">
                            @foreach($product->sizes as $size)
                                <li>{{ $size->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul class="table_list">
                            @foreach($product->sizes as $size)
                                <li>{{ Color::find($size->pivot->color_id)->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul class="table_list">
                            @foreach($product->sizes as $size)
                                <li>{{ $size->pivot->qty }} stuk(s)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <div class="btn-group">
                            {{ link_to_route('admin.products.edit', 'Wijzigen', [$product->id], ['class' => 'btn btn-mini btn-info']) }}
                            {{ link_to_route('admin.products.destroy', 'Verwijderen', [$product->id], ['class' => 'btn btn-mini btn-danger', 'data-method' => 'delete', 'data-confirm' => 'Weet je zeker dat je dit product wilt verwijderen?']) }}
                        </div>
                    </td>
                </tr>

            @endforeach

        </tbody>
    </table>

    <div class="pull-left">
        {{ $products->appends(Input::only('f', 's'))->links() }}
    </div>

    <h5 class="pull-right">Totaal aantal producten: {{ Product::count() }}</h5>

@else

    <div class="alert alert-info clear">
        Er zijn nog geen producten ingevoerd.
    </div>

@endif

@stop