<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GameRequest as StoreRequest;
use App\Http\Requests\GameRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class GameCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GameCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Game');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/game');
        $this->crud->setEntityNameStrings('game', 'games');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn(['name' => 'name', 'type' => 'text', 'label' => 'Name']);
        $this->crud->addColumn(['name' => 'executable', 'text' => 'text', 'label' => 'Executable']);
        $this->crud->addColumn([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Groups",
            'type' => 'select_multiple',
            'name' => 'groups', // the method that defines the relationship in your Model
            'entity' => 'groups', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\\Models\\XFGroup", // foreign key model
        ]);

        $this->crud->addField(['name' => 'name', 'type' => 'text', 'label' => 'Game Name']);
        $this->crud->addField(['name' => 'executable', 'type' => 'text', 'label' => 'Game Executable']);
        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Groups",
            'type' => 'select2_multiple',
            'name' => 'groups', // the method that defines the relationship in your Model
            'entity' => 'groups', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\\Models\\XFGroup", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            // 'select_all' => true, // show Select All and Clear buttons?

            // optional
            'options'   => (function ($query) {
                return $query->orderBy('user_group_id', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ]);

        // add asterisk for fields that are required in GameRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
