<?php

class AdminTermsController extends \BaseController {

	/**
     * Number of results per page
     *
     * @var integer
     */
	protected $perPage = 8;

	/**
	 * Terms repositories
	 *
	 * @var Eloquent Model
	 */
	protected $afdeling;
	protected $categorie;
	protected $merk;
	protected $kleur;
	protected $size;

	/**
	 * AdminProductsController Constructor
	 */
	public function __construct(Department $department, Category $category, Brand $brand, Color $color, Size $size)
	{
		$this->afdeling = $department;
		$this->categorie = $category;
		$this->merk = $brand;
		$this->kleur = $color;
		$this->size = $size;

		$this->beforeFilter('csrf', array('on' => 'post'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($taxonomy)
	{
		$terms = $this->{$taxonomy}->paginate($this->perPage);

		$count = $this->{$taxonomy}->count();

		return View::make('admin::terms.index', compact('terms', 'taxonomy', 'count'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($taxonomy)
	{
		$term = new $this->{$taxonomy}(Input::only(['name', 'description']));

		$term->taxonomy = $taxonomy;

		if( ! $term->save())
		{
			return Response::json(['status' => 0, 'message' => $term->errors->all()]);
		}

		Session::flash('success', 'De term is succesvol toegevoegd.');

		return Response::json(['status' => 1, 'message' => 'De term is succesvol toegevoegd.']);
	}

	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($taxonomy, $id)
    {
        return $this->{$taxonomy}->find($id);
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($taxonomy, $id)
	{
		$term = $this->{$taxonomy}->find($id);

		$term->name = Input::get('name');
		$term->description = Input::get('description');

		if( ! $term->save())
		{
			return Response::json(['status' => 0, 'message' => $term->errors->all()]);
		}

		Session::flash('success', 'De term is succesvol gewijzigd.');

		return Response::json(['status' => 1, 'message' => 'De term is succesvol gewijzigd.']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($taxonomy, $id)
	{
		$this->{$taxonomy}->destroy($id);

		return Redirect::route('admin.terms.index', [$taxonomy])->with('info', 'De term is succesvol verwijderd.');
	}

}