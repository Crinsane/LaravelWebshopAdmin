@extends('admin::master')

@section('container')

{{ Form::model($product, ['route' => ['admin.products.update', $product->id], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal']) }}
{{ Form::hidden('id') }}

    <div class="row">

        <div class="span6">

            <fieldset>
                <legend>Productgegevens</legend>

                <div class="control-group {{ (Session::has('validation') && Session::get('validation')->has('name')) ? 'error' : '' }}">
                    {{ Form::label('name', 'Product naam', ['class' => 'control-label']) }}
                    <div class="controls">
                        {{ Form::text('name', null, ['class' => 'input-xlarge', 'id' => 'name']) }}
                        @if(Session::has('validation') && Session::get('validation')->has('name'))
                            <span class="help-inline">Vul alsjeblieft een productnaam in.</span>
                        @endif
                    </div>
                </div>

                <div class="control-group {{ (Session::has('validation') && Session::get('validation')->has('description')) ? 'error' : '' }}">
                    {{ Form::label('description', 'Product beschrijving', ['class' => 'control-label']) }}
                    <div class="controls">
                        {{ Form::textarea('description', null, ['class' => 'input-xlarge', 'id' => 'description', 'rows' => 4]) }}
                        @if(Session::has('validation') && Session::get('validation')->has('description'))
                            <span class="help-inline">Vul alsjeblieft een product beschrijving in.</span>
                        @endif
                    </div>
                </div>

                <div class="control-group {{ (Session::has('validation') && Session::get('validation')->has('price')) ? 'error' : '' }}">
                    {{ Form::label('price', 'Prijs', ['class' => 'control-label']) }}
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on">&euro;</span>
                            {{ Form::text('price', null, ['class' => 'input-xlarge', 'id' => 'price']) }}
                        </div>
                        @if(Session::has('validation') && Session::get('validation')->has('price'))
                            <span class="help-inline">Vul alsjeblieft een geldige prijs in.</span>
                        @endif
                    </div>
                </div>

                <div class="control-group {{ (Session::has('validation') && Session::get('validation')->has('temp_price')) ? 'error' : '' }}">
                    {{ Form::label('temp_price', 'Tijdelijke prijs', ['class' => 'control-label']) }}
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on">&euro;</span>
                            {{ Form::text('temp_price', null, ['class' => 'input-xlarge', 'id' => 'temp_price']) }}
                        </div>
                        @if(Session::has('validation') && Session::get('validation')->has('temp_price'))
                            <span class="help-inline">Vul alsjeblieft een geldige tijdelijke prijs in.</span>
                        @endif
                    </div>
                </div>

                <div class="control-group">
                    {{ Form::label('vat', 'BTW', ['class' => 'control-label']) }}
                    <div class="controls">
                        <label class="radio">
                            {{ Form::radio('vat', '6', null, ['id' => 'vat']) }}
                            6%
                        </label>
                        <label class="radio">
                            {{ Form::radio('vat', '21', null, ['id' => 'vat']) }}
                            21%
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox">
                            {{ Form::checkbox('active', 'active') }} Product actief?
                        </label>
                    </div>
                </div>

            </fieldset>

        </div>

        <div class="span6">

            <fieldset>
                <legend>Taxonomygegevens</legend>

                <div class="control-group">
                    {{ Form::label('department', 'Afdeling', ['class' => 'control-label']) }}
                    <div class="controls">
                        {{ Form::select('department', $departments, $product->department->first()->id, ['class' => 'input-xlarge', 'id' => 'department']) }}
                    </div>
                </div>

                <div class="control-group">
                    {{ Form::label('category', 'Categorie', ['class' => 'control-label']) }}
                    <div class="controls">
                        {{ Form::select('category', $categories, $product->category->first()->id, ['class' => 'input-xlarge', 'id' => 'category']) }}
                    </div>
                </div>

                <div class="control-group">
                    {{ Form::label('brand', 'Merk', ['class' => 'control-label']) }}
                    <div class="controls">
                        {{ Form::select('brand', $brands, $product->brand->first()->id, ['class' => 'input-xlarge', 'id' => 'brand']) }}
                    </div>
                </div>

            </fieldset>

        </div>

    </div>

    <div class="row">

        <div class="span6">

            <fieldset class="image_fieldset">
                <legend>Productafbeeldingen</legend>

                <?php $i = 1;?>

                @if($product->has('images'))

                    @foreach($product->images as $image)

                        <div class="control-group">
                            {{ Form::label('dummy_' . $i, 'Afbeelding ' . $i, ['class' => 'control-label']) }}
                            <div class="controls">
                                <div class="input-append">
                                    {{ Form::text('dummy_' . $i, $image->filename, ['class' => 'input-large dummy_input image_preview', 'id' => 'dummy_' . $i, 'data-content' => HTML::image('assets/products/thumbnails/' . $image->filename)]) }}<a class="btn btn-danger dummy_delete_buttons" id="dummy_delete_button_{{ $i }}" data-image="{{ $image->id }}">Verwijderen</a>
                                </div>
                            </div>
                        </div>

                        <?php $i++;?>

                    @endforeach

                    @while($i <= 4)

                        <div class="control-group">
                            {{ Form::label('dummy_' . $i, 'Afbeelding ' . $i, ['class' => 'control-label']) }}
                            <div class="controls">
                                <div class="input-append">
                                    {{ Form::text('dummy_' . $i, '', ['class' => 'input-large dummy_input', 'id' => 'dummy_' . $i]) }}<a class="btn dummy_buttons" id="dummy_button_{{ $i }}">Bladeren...</a>
                                </div>
                            </div>
                        </div>

                        <?php $i++;?>

                    @endwhile

                    {{ Form::file('image_1', ['class' => 'image_upload', 'id' => 'image_1']) }}
                    {{ Form::file('image_2', ['class' => 'image_upload', 'id' => 'image_2']) }}
                    {{ Form::file('image_3', ['class' => 'image_upload', 'id' => 'image_3']) }}
                    {{ Form::file('image_4', ['class' => 'image_upload', 'id' => 'image_4']) }}

                @endif

            </fieldset>

        </div>

        <div class="span6">

            <fieldset>
                <legend>Productvoorraad</legend>

                <div class="control-group">
                    <div class="span2 product_stock_label code">Product Code</div>
                    <div class="span1 product_stock_label size">Maat</div>
                    <div class="span2 product_stock_label color">Kleur</div>
                    <div class="span1 product_stock_label stock">Voorraad</div>

                    @if($product->has('sizes'))

                        @foreach($product->sizes as $size)

                            <div class="product_stock clear">
                                {{ Form::text('product_code[]', $size->pivot->product_code, ['class' => 'input-medium']) }}
                                {{ Form::select('product_size[]', $sizes, $size->pivot->size_id, ['class' => 'input-small']) }}
                                {{ Form::select('product_color[]', $colors, $size->pivot->color_id, ['class' => 'input-medium']) }}
                                {{ Form::text('product_stock[]', $size->pivot->qty, ['class' => 'input-mini']) }}
                            </div>

                        @endforeach

                    @endif

                    <a href="#" id="add_product" class="small add_link"><i class="icon-plus"></i>&nbsp;Toevoegen</a>
                </div>

            </fieldset>

        </div>

    </div>

    <div class="row">

        <div class="span12">

            <fieldset>
                <legend>&nbsp;</legend>

                {{ Form::submit('Wijzigen', ['class' => 'btn btn-large btn-primary']) }}

                {{ link_to_route('admin.products.destroy', 'Verwijderen', [$product->id], ['class' => 'btn btn-large btn-danger pull-right', 'data-method' => 'delete', 'data-confirm' => 'Weet je zeker dat je dit product wilt verwijderen?']) }}

            </fieldset>

        </div>

    </div>

{{ Form::close() }}

@stop

@section('templates')

<script type="amazings/template" id="product_stock_template">
    <div class="product_stock clear">
        {{ Form::text('product_code[]', '', ['class' => 'input-medium']) }}
        {{ Form::select('product_size[]', $sizes, '', ['class' => 'input-small']) }}
        {{ Form::select('product_color[]', $colors, '', ['class' => 'input-medium']) }}
        {{ Form::text('product_stock[]', '', ['class' => 'input-mini']) }}
    </div>
</script>

@stop