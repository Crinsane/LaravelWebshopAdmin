<?php

use Illuminate\Support\Collection;

Route::group(['prefix' => 'admin', 'before' => 'admin|auth.basic'], function()
{

    Route::get('/', ['as' => 'admin.home', function()
    {
    	return View::make('admin::home');
    }]);

    Route::resource('products', 'AdminProductsController', ['except' => ['show']]);

    Route::get('terms/{taxonomy}', ['uses' => 'AdminTermsController@index', 'as' => 'admin.terms.index']);
    Route::get('terms/{taxonomy}/{id}', ['uses' => 'AdminTermsController@show', 'as' => 'admin.terms.show']);
    Route::post('terms/{taxonomy}', ['uses' => 'AdminTermsController@store', 'as' => 'admin.terms.store']);
    Route::put('terms/{taxonomy}/{term}', ['uses' => 'AdminTermsController@update', 'as' => 'admin.terms.update']);
    Route::delete('terms/{taxonomy}/{term}', ['uses' => 'AdminTermsController@destroy', 'as' => 'admin.terms.destroy']);

    Route::delete('images/{id}', function($id)
    {
    	$delete = Image::find($id)->delete();

    	if( ! $delete)
    	{
    		return Response::json(['status' => '0', 'message' => 'Fout bij het verwijderen van de afbeelding.']);
    	}

    	return Response::json(['status' => '1', 'message' => 'De afbeelding is succesvol verwijderd.']);
    });

});