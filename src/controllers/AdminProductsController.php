<?php

class AdminProductsController extends \BaseController {

	/**
     * Number of results per page
     *
     * @var integer
     */
	protected $perPage = 5;

	/**
	 * AdminProductsController Constructor
	 */
	public function __construct()
	{
		$this->beforeFilter('csrf', array('on' => 'post'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$products = Product::with(['sizes' => function($query)
		{
			return $query->order();
		}, 'images', 'category', 'brand', 'department']);

		if(Input::has('f'))
		{
			if(Input::get('f') == 'num')
			{
				$products = $products->whereBetween(DB::raw('LEFT(name, 1)'), [0, 9]);
			}
			else
			{
				$products = $products->where('name', 'like', Input::get('f') . '%');
			}
		}

		if(Input::has('s'))
		{

			$products = $products->where('name', 'like', '%' . Input::get('s') . '%');
		}

		$products = $products->paginate($this->perPage);

		return View::make('admin::products.index', compact('products'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$departments = Department::all()->lists('name', 'id');
		$categories = Category::all()->lists('name', 'id');
		$brands = Brand::all()->lists('name', 'id');
		$sizes = Size::all()->lists('name', 'id');
		$colors = Color::all()->lists('name', 'id');

		return View::make('admin::products.create', compact('departments', 'categories', 'brands', 'sizes', 'colors'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$product = new Product(
			array_merge(
				Input::only(['name', 'description', 'price', 'vat']),
				[
					'active' => Input::has('active'),
					'temp_price' => Input::has('temp_price') ? Input::get('temp_price') : null
				]
			)
		);

		if( ! $product->save())
		{
			return Redirect::back()->withInput()->withErrors($product->errors);
		}

		$product->terms()->sync([Input::get('department'), Input::get('category'), Input::get('brand')]);

		$this->_uploadImages($product);

		$this->_syncProductStock($product);

		return Redirect::route('admin.products.index')->with('success', 'Het product is succesvol toegevoegd.');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$product = Product::find($id);

		$departments = Department::all()->lists('name', 'id');
		$categories = Category::all()->lists('name', 'id');
		$brands = Brand::all()->lists('name', 'id');
		$sizes = Size::all()->lists('name', 'id');
		$colors = Color::all()->lists('name', 'id');

		return View::make('admin::products.edit', compact('product', 'departments', 'categories', 'brands', 'sizes', 'colors'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$product = Product::find($id);

		$input = Input::only(['name', 'description', 'price', 'temp_price', 'vat']);


		foreach($input as $key => $value)
		{
			if($key == 'temp_price')
			{
				$value = ! empty($value) ? $value : null;
			}

			$product->{$key} = $value;
		}

		$product->active = (int) Input::has('active');

		if( ! $product->save())
		{
			return Redirect::back()->withInput()->with('validation', $product->errors);
		}

		$product->terms()->sync([Input::get('department'), Input::get('category'), Input::get('brand')]);

		$this->_uploadImages($product);

		$this->_syncProductStock($product);

		return Redirect::route('admin.products.edit', [$id])->with('success', 'Het product is succesvol gewijzigd.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Product::destroy($id);

		return Redirect::route('admin.products.index')->with('info', 'Het product is succesvol verwijderd.');
	}

	/**
	 * Upload the images that were send for uploading
	 *
	 * @param  Product $product The product model to attach the image to
	 * @return void
	 */
	private function _uploadImages($product)
	{
		foreach(range(1, 4) as $num)
		{
			if(Input::hasFile('image_' . $num))
			{
				$this->_saveImage(Input::file('image_' . $num), $product);
			}
		}
	}

	/**
	 * Upload a new image
	 *
	 * @param  string $image
	 * @param  Object $product
	 * @return boolean
	 */
	private function _saveImage($image, Product $product)
	{
		if( ! $this->_validateImage($image))
		{
			return false;
		}

		$filename = Str::random(32) . '.' . $image->getClientOriginalExtension();

		$image->move('./public/assets/products', $filename);

		$image = new Image(['filename' => $filename]);

		if( ! $product->images()->save($image))
		{
			@unlink('./public/assets/products/' . $filename);
			return false;
		}

		$this->_createThumbnail($filename);

		return true;
	}

	/**
	 * Validate the image mimetype
	 *
	 * @param  Symfony\Component\HttpFoundation\File\UploadedFile $image The image object to validate
	 * @return boolean
	 */
	private function _validateImage($image)
	{
		$validator = Validator::make(['filename' => $image], ['filename' => 'mimes:jpg,jpeg,bmp,png']);

		if($validator->fails())
			return false;

		return true;
	}

	/**
	 * Create a thumbnail of the uploaded image
	 *
	 * @param  string $filename Image filename
	 * @return boolean
	 */
	private function _createThumbnail($filename)
	{
		$img = Resizer::make('public/assets/products/' . $filename)->resize(80, 80, true);

		$img->save('public/assets/products/thumbnails/' . $filename);
	}

	/**
	 * Sync the product stock
	 *
	 * @param  Product $product The product model to sync to
	 * @return void
	 */
	private function _syncProductStock($product)
	{
		$product->sizes()->detach();

		$product_codes = Input::get('product_code');
		$product_sizes = Input::get('product_size');
		$product_colors = Input::get('product_color');
		$product_stock = Input::get('product_stock');

		for($i = 0; $i < count($product_codes); $i++)
		{
			$product->sizes()->attach($product_sizes[$i], [
				'product_code' => $product_codes[$i],
				'color_id' => $product_colors[$i],
				'qty' => $product_stock[$i]
			]);
		}

		return;
	}

}