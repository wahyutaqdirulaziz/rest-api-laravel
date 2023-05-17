<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\organizer;
use Validator;
use Illuminate\Http\Request;

class OrganizersControlller extends BaseController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $organizers = organizer::paginate($perPage, ['*'], 'page', $page);
        return $this->sendResponse($organizers->items(), 'Organizer retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'organizerName' => 'required',
            'imageLocation' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $organizer = organizer::create($input);

        return $this->sendResponse($organizer, 'Organizer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $organizer = organizer::find($id);

        if (is_null($organizer)) {
            return $this->sendError('Organizer not found.');
        }

        return $this->sendResponse($organizer, 'Organizer retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, organizer $organizer)
    {
        $input = $request->all();

        // $validator = Validator::make($input, [
        //     'organizerName' => 'required',
        //     'imageLocation' => 'required'
        // ]);

        // if($validator->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());
        // }

        $organizer->organizerName = $input['organizerName'];
        $organizer->imageLocation = $input['imageLocation'];
        $organizer->save();

        return $this->sendResponse($organizer, 'Organizer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(organizer $organizer)
    {
        $organizer->delete();

        return $this->sendResponse([], 'Organizer deleted successfully.');
    }
}
