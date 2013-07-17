@extends('admin::master')

@section('container')

<div class="page-header clearfix">
    <h1 class="pull-left">Termen overzicht <small>Een overzicht van de termen van de taxonomie "{{ Taxonomy::where('slug', $taxonomy)->first()->name }}"</small></h1>
    {{ link_to_route('admin.terms.store', 'Nieuwe Term', [$taxonomy], ['class' => 'btn btn-primary pull-right', 'id' => 'new_term', 'data-csrf' => csrf_token()]) }}
</div>

@if(count($terms))

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Beschrijving</th>
                <th>Aantal producten</th>
                <th style="width:183px;">Acties</th>
            </tr>
        </thead>

        <tbody>

            @foreach($terms as $term)

                <tr>
                    <td>{{ $term->name }}</td>
                    <td>{{ $term->description }}</td>
                    <td>{{ $term->products()->count() }}</td>
                    <td>
                        <div class="btn-group">
                            {{ link_to_route('admin.terms.update', 'Wijzigen', [$term->taxonomy, $term->id], ['class' => 'btn btn-mini btn-info edit_term', 'data-id' => $term->id, 'data-taxonomy' => $term->taxonomy, 'data-csrf' => csrf_token()]) }}
                            {{ link_to_route('admin.terms.destroy', 'Verwijderen', [$term->taxonomy, $term->id], ['class' => 'btn btn-mini btn-danger', 'data-method' => 'delete', 'data-confirm' => 'Weet je zeker dat je deze term wilt verwijderen?']) }}
                        </div>
                    </td>
                </tr>

            @endforeach

        </tbody>
    </table>

    <div class="pull-left">
        {{ $terms->links() }}
    </div>

    <h5 class="pull-right">Totaal aantal termen: {{ $count }}</h5>

@else

    <div class="alert alert-info clear">
        Er zijn nog geen termen ingevoerd.
    </div>

@endif

<div class="modal hide fade" id="new_term_modal">
    <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h3>Voer een nieuwe term in</h3>
	</div>
	<div class="modal-body">
		<label>Naam</label>
	    <input type="text" name="name" class="input-block-level" value="">
	    <label>Beschrijving</label>
	    <textarea name="description" class="input-block-level"></textarea>
	</div>
	<div class="modal-footer">
	    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Annuleren</a>
	    <a href="#" class="btn btn-primary add" id="new_term_action">Toevoegen</a>
    </div>
</div>

@stop