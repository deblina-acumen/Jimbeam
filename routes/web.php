<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

		
Route::get('/', function () {
	return view('login');
});
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('user-login', 'Auth\LoginController@check_login_details');

Route::any('/forget-password', [
			'as' => 'forget-password',
			'uses' => 'Profile\ProfileController@forgot_password'
		]);
		
		 Route::post('/forgot-pass-submit', [
			'as' => 'forgot-pass-submit',
			'uses' => 'Profile\ProfileController@submit_forgot_pass'
		]);
		
		 Route::any('/set-new-password/{number}/{id}', [
			'as' => 'set-new-password',
			'uses' => 'Profile\ProfileController@set_new_password'
		]);
		
		Route::post('/submit-new-set-password', [
			'as' => 'submit-new-set-password',
			'uses' => 'Profile\ProfileController@submit_newset_pass'
		]);
		



Route::group(['middleware' => 'auth'], function () {
	Route::get('dashboard', [
		'as' => 'dashboard',
		'uses' => 'Dashboard\DashboardController@index'
	]);
	Route::group(['prefix' => 'profile-management'], function () {
		Route::get('profile/{id}', 'Profile\ProfileController@profile');

		Route::post('profile-update', 'Profile\ProfileController@profile_update');
		
	});
	
	//<!-- master role section-->
	Route::get('add-role', 'Master\RoleController@add_role');
	Route::post('save-role-data', 'Master\RoleController@save_role_data');
	Route::get('role-list', 'Master\RoleController@role_list');
	Route::get('edit-role/{id}', 'Master\RoleController@role_edit');
	Route::post('update-role-data', 'Master\RoleController@update_role_data');
	Route::get('delete-role/{id}', 'Master\RoleController@delete_role');
	Route::any('role-active/{id?}/{value?}', 'Master\RoleController@changeStatus');
	//Role User
	Route::get('role-user-list', 'Master\RoleUserController@user_list');
	Route::get('add-role-user', 'Master\RoleUserController@add_user');
	Route::any('save-role-user-data', 'Master\RoleUserController@save_user_data');
	Route::get('role-user-edit/{id}', 'Master\RoleUserController@user_edit');
	Route::post('update-role-user-data', 'Master\RoleUserController@update_user_data');
	Route::any('role-user-active/{id?}/{value?}', 'Master\RoleUserController@changeStatus');
	Route::get('delete-role-user/{id}', 'Master\RoleUserController@delete_user');
	
	//<!-- master Brand section-->
	Route::get('add-brand', 'Master\BrandController@add_brand');
	Route::post('save-brand-data', 'Master\BrandController@save_brand_data');
	Route::get('brand-list', 'Master\BrandController@brand_list');
	Route::get('edit-brand/{id}', 'Master\BrandController@brand_edit');
	Route::post('update-brand-data', 'Master\BrandController@update_brand_data');
	Route::get('delete-brand/{id}', 'Master\BrandController@delete_brand');
	Route::any('brand-active/{id?}/{value?}', 'Master\BrandController@changeStatus');
	//<!-- master store category section-->
	Route::get('add-store-category', 'Master\StoreCategoryController@add_store_category');
	Route::post('save-store-category-data', 'Master\StoreCategoryController@save_store_category_data');
	Route::get('store-category-list', 'Master\StoreCategoryController@store_category_list');
	Route::get('edit-store-category/{id}', 'Master\StoreCategoryController@store_category_edit');
	Route::post('update-store-category-data', 'Master\StoreCategoryController@update_store_category_data');
	Route::get('delete-store-category/{id}', 'Master\StoreCategoryController@delete_store_category');
	Route::any('store-category-active/{id?}/{value?}', 'Master\StoreCategoryController@changeStatus');
	
	
	// Region
	Route::get('region-master-list', 'Master\RegionController@list');
	Route::get('add-region', [
		'as' => 'add-region',
		'uses' => 'Master\RegionController@addRegion'
	]);

	Route::get('edit-region/{id}', [
		'as' => 'edit-region',
		'uses' => 'Master\RegionController@editRegion'
	]);
	
	Route::post('save-region', [
		'as' => 'save-region',
		'uses' => 'Master\RegionController@save_region'
	]); 
	
	Route::post('update-region', [
		'as' => 'update-region',
		'uses' => 'Master\RegionController@update_region'
	]);
	
	Route::post('region-details', 'Master\RegionController@view');
	Route::any('region/active/{id?}/{value?}', 'Master\RegionController@changeStatus');
	Route::any('region/delete/{id?}/{value?}', 'Master\RegionController@delete_fn');
	
	//State
	Route::get('state-master-list', 'Master\StateController@list');
	Route::get('add-state', [
		'as' => 'add-state',
		'uses' => 'Master\StateController@addState'
	]);
	Route::post('save-state', [
		'as' => 'save-state',
		'uses' => 'Master\StateController@save_state'
	]);
	
	Route::get('edit-state/{id}', [
		'as' => 'edit-state',
		'uses' => 'Master\StateController@editState'
	]);
	
	Route::post('update-state', [
		'as' => 'update-state',
		'uses' => 'Master\StateController@update_state'
	]);
	
	//Route::post('state-details', 'Master\StateController@view');
	Route::any('state/active/{id?}/{value?}', 'Master\StateController@changeStatus');
	Route::any('state/delete/{id?}/{value?}', 'Master\StateController@delete_fn');
	
	
	
	// Supplier
	Route::get('supplier-master-list', 'Master\SupplierController@list');
	Route::get('add-supplier', [
		'as' => 'add-supplier',
		'uses' => 'Master\SupplierController@addSupplier'
	]);
	
	Route::get('edit-supplier/{id}', [
		'as' => 'edit-supplier',
		'uses' => 'Master\SupplierController@editSupplier'
	]);
	
	Route::post('save-supplier', [
		'as' => 'save-supplier',
		'uses' => 'Master\SupplierController@save_supplier'
	]);
	
	Route::post('update-supplier', [
		'as' => 'update-supplier',
		'uses' => 'Master\SupplierController@update_supplier'
	]);
	
	Route::post('supplier-details', 'Master\SupplierController@view');
	Route::any('supplier/active/{id?}/{value?}', 'Master\SupplierController@changeStatus');
	Route::any('supplier/delete/{id?}/{value?}', 'Master\SupplierController@delete_fn');
	
	
	
	//////// Master store section ///
	Route::get('store-list', 'Master\StoreController@store_list');
	Route::get('add-store', 'Master\StoreController@add_store');
	Route::post('submit-store', 'Master\StoreController@save_store');
	Route::get('edit-store/{storeId}', 'Master\StoreController@edit_store');
	Route::post('update-store', 'Master\StoreController@update_store');
	Route::any('store-active/{id?}/{value?}', 'Master\StoreController@changeStatus');
	Route::get('delete-store/{id}', 'Master\StoreController@delete_store');
	
	
	/////////////user management ///////////////
	
	// listing user
		Route::get('user-list', 'Master\UserController@user_list');
		// adding user form
		Route::get('add-user', 'Master\UserController@add_user')->name('user.add');
		// saving user data
		Route::any('save-user-data', 'Master\UserController@save_user_data')->name('save-data');
		// editing user data
		Route::get('user-edit/{id}', 'Master\UserController@user_edit');
		// updating user data
		Route::post('update-user-data', 'Master\UserController@update_user_data');
		
		Route::any('user-active/{id?}/{value?}', 'Master\UserController@changeStatus');
	Route::get('delete-user/{id}', 'Master\UserController@delete_user');
	
});





//Clear Cache facade value:
Route::get('/clear-cache', function () {
	$exitCode = Artisan::call('cache:clear');
	return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function () {
	$exitCode = Artisan::call('optimize');
	return '<h1>Reoptimized class loader</h1>';
});

//Clear Route cache:
Route::get('/route-cache', function () {
	$exitCode = Artisan::call('route:cache');
	return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function () {
	$exitCode = Artisan::call('view:clear');
	return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function () {
	$exitCode = Artisan::call('config:cache');
	return '<h1>Clear Config cleared</h1>';
});
//Clear Config cache:
Route::get('/config-clear', function () {
	$exitCode = Artisan::call('config:clear');
	return '<h1>Clear Config cleared</h1>';
});
//Asset dist
Route::get('/asset-dist', function () {
	$exitCode = Artisan::call('asset:dist');
	return '<h1>Layouts rendered</h1>';
});


//contractor section end //

Auth::routes();
