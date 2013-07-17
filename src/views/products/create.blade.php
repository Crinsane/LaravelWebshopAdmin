@extends('admin::master')

@section('container')

{{ Form::open(['route' => 'admin.products.store', 'files' => true, 'class' => 'form-horizontal']) }}

    <div class="row">

        <div class="span6">

            <fieldset>
                <legend>Productgegevens</legend>

                <div class="control-group {{ (Session::has('validation') && Session::get('validation')->has('name')) ? 'error' : '' }}">
                    {{ Form::label('name', 'Product naam', ['class' => 'control-label']) }}
                    <div class="controls">
                        {{ Form::text('name', '', ['class' => 'input-xlarge', 'id' => 'name']) }}
                        @if(Session::has('validation') && Session::get('validation')->has('name'))
                            <span class="help-inline">Vul alsjeblieft een productnaam in.</span>
                        @endif
                    </div>
                </div>

                <div class="control-group {{ (Session::has('validation') && Session::get('validation')->has('description')) ? 'error' : '' }}">
                    {{ Form::label('description', 'Product beschrijving', ['class' => 'control-label']) }}
                    <div class="controls">
                        {{ Form::textarea('description', '', ['class' => 'input-xlarge', 'id' => 'description', 'rows' => 4]) }}
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
                            {{ Form::text('price', '', ['class' => 'input-xlarge', 'id' => 'price']) }}
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
                            {{ Form::text('temp_price', '', ['class' => 'input-xlarge', 'id' => 'temp_price']) }}
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
                            {{ Form::radio('vat', '6', false, ['id' => 'vat']) }}
                            6%
                        </label>
                        <label class="radio">
                            {{ Form::radio('vat', '21', true, ['id' => 'vat']) }}
                            21%
                        </label>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox">
                            {{ Form::checkbox('active', 'active', true) }} Product actief?
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
                        {{ Form::select('department', $departments, null, ['class' => 'input-xlarge', 'id' => 'department']) }}
                    </div>
                </div>

                <div class="control-group">
                    {{ Form::label('category', 'Categorie', ['class' => 'control-label']) }}
                    <div class="controls">
                        {{ Form::select('category', $categories, null, ['class' => 'input-xlarge', 'id' => 'category']) }}
                    </div>
                </div>

                <div class="control-group">
                    {{ Form::label('brand', 'Merk', ['class' => 'control-label']) }}
                    <div class="controls">
                        {{ Form::select('brand', $brands, null, ['class' => 'input-xlarge', 'id' => 'brand']) }}
                    </div>
                </div>

            </fieldset>

        </div>

    </div>

    <div class="row">

        <div class="span6">

            <fieldset>
                <legend>Productafbeeldingen</legend>

                <div class="control-group">
                    {{ Form::label('dummy_1', 'Afbeelding 1', ['class' => 'control-label']) }}
                    <div class="controls">
                        <div class="input-append">
                            {{ Form::text('dummy_1', '', ['class' => 'input-large dummy_input', 'id' => 'dummy_1']) }}<a class="btn dummy_buttons" id="dummy_button_1">Bladeren...</a>
                        </div>
                    </div>
                </div>

                <div class="control-group">
                    {{ Form::label('dummy_1', 'Afbeelding 2', ['class' => 'control-label']) }}
                    <div class="controls">
                        <div class="input-append">
                            {{ Form::text('dummy_2', '', ['class' => 'input-large dummy_input', 'id' => 'dummy_2']) }}<a class="btn dummy_buttons" id="dummy_button_2">Bladeren...</a>
                        </div>
                    </div>
                </div>

                <div class="control-group">
                    {{ Form::label('dummy_1', 'Afbeelding 3', ['class' => 'control-label']) }}
                    <div class="controls">
                        <div class="input-append">
                            {{ Form::text('dummy_3', '', ['class' => 'input-large dummy_input', 'id' => 'dummy_3']) }}<a class="btn dummy_buttons" id="dummy_button_3">Bladeren...</a>
                        </div>
                    </div>
                </div>

                <div class="control-group">
                    {{ Form::label('dummy_1', 'Afbeelding 4', ['class' => 'control-label']) }}
                    <div class="controls">
                        <div class="input-append">
                            {{ Form::text('dummy_4', '', ['class' => 'input-large dummy_input', 'id' => 'dummy_4']) }}<a class="btn dummy_buttons" id="dummy_button_4">Bladeren...</a>
                        </div>
                    </div>
                </div>

                {{ Form::file('image_1', ['class' => 'image_upload', 'id' => 'image_1']) }}
                {{ Form::file('image_2', ['class' => 'image_upload', 'id' => 'image_2']) }}
                {{ Form::file('image_3', ['class' => 'image_upload', 'id' => 'image_3']) }}
                {{ Form::file('image_4', ['class' => 'image_upload', 'id' => 'image_4']) }}

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

                    <div class="product_stock clear">
                        {{ Form::text('product_code[]', '', ['class' => 'input-medium']) }}
                        {{ Form::select('product_size[]', $sizes, '', ['class' => 'input-small']) }}
                        {{ Form::select('product_color[]', $colors, '', ['class' => 'input-medium']) }}
                        {{ Form::text('product_stock[]', '', ['class' => 'input-mini']) }}
                    </div>

                    <a href="#" id="add_product" class="small add_link"><i class="icon-plus"></i>&nbsp;Toevoegen</a>
                </div>

            </fieldset>

        </div>

    </div>

    <div class="row">

        <div class="span12">

            <fieldset>
                <legend>&nbsp;</legend>

                {{ Form::submit('Toevoegen', ['class' => 'btn btn-large btn-primary']) }}

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
        {{ Form::text('product_stock[]', '', ['class' => 'input-small']) }}
    </div>
</script>

@stop