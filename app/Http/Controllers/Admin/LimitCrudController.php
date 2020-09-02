<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LimitRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LimitCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LimitCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Limit::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/limit');
        CRUD::setEntityNameStrings('лимит', 'Лимиты');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'category_id',
          'label' => 'ИД категории',
        ]);
        $this->crud->addColumn([
          'type' => 'text',
          'name' => 'user_id',
          'label' => 'ИД пользователя',
        ]);
        $this->crud->addColumn([
          'type' => 'number',
          'name' => 'limit',
          'label' => 'Лимит по категории',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(LimitRequest::class);

        $this->crud->addField([
          'type' => 'select2',
          'name' => 'category_id',
          'entity' => 'category',
          'attribute' => 'id',
          'label' => 'ИД категории',
        ]);
        $this->crud->addField([
          'type' => 'select2',
          'name' => 'user_id',
          'entity' => 'user',
          'attribute' => 'id',
          'label' => 'ИД пользователя',
        ]);
        $this->crud->addField([
          'type' => 'number',
          'name' => 'limit',
          'label' => 'Лимит по категории',
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
