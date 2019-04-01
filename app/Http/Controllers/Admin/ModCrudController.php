<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ModRequest as StoreRequest;
use App\Http\Requests\ModRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ModCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ModCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Mod');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/mod');
        $this->crud->setEntityNameStrings('mod', 'mods');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns

        $this->crud->addColumn(['name' => 'name', 'type' => 'text', 'label' => 'Name']);
        $this->crud->addColumn(['name' => 'version', 'text' => 'number', 'label' => 'Version Number']);
        $this->crud->addColumn([
            // 1-n relationship
            'label' => "Game", // Table column heading
            'type' => "select",
            'name' => 'game_id', // the db column for the foreign key
            'entity' => 'game', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Game", // foreign key model
        ]);
        $this->crud->addColumn([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Groups",
            'type' => 'select_multiple',
            'name' => 'groups', // the method that defines the relationship in your Model
            'entity' => 'groups', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\\Models\\XFGroup", // foreign key model
        ]);



        //$this->crud->setFromDb();
        $this->crud->addField(['name' => 'name', 'type' => 'text', 'label' => 'Mod Name']);
        $this->crud->addField(['name' => 'version', 'type' => 'number', 'label' => 'Version Number', 'attributes' => ["step" => "any"]]);
        $this->crud->addField([   // Upload
            'name' => 'mod_file',
            'label' => 'Mod File',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'local' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
        ],'both');
        $this->crud->addField([  // Select2
            'label' => "Game",
            'type' => 'select2',
            'name' => 'game_id', // the db column for the foreign key
            'entity' => 'game', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Game", // foreign key model

            // optional
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ]);
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
        $this->crud->addField(['name' => 'secret', 'type' => 'text', 'label' => 'Mod Authentication Secret']);

        // add asterisk for fields that are required in ModRequest
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
