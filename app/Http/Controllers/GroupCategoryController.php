<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;

class GroupCategoryController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $model_name = $dataType->model_name;
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;
        $model = $model_name::findOrFail($id);
        $model->category = $request->input('category');
        $model->save();
        $arr = $request->input('group_category_belongstomany_group_relationship');
        $model->groups()->sync($arr);
        return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                    'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        //parent::update($request, $id);
    }

    public function store(Request $request)
    {
        $slug = $this->getSlug($request);
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
        $model_name = $dataType->model_name;
        $model_name = $model_name::create([
            'category' => $request->input('category')
        ]);
        $arr = $request->input('group_category_belongstomany_group_relationship');
        $model_name->groups()->sync($arr);
        return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                        'message'    => __('voyager::generic.successfully_added_new')." {$dataType->display_name_singular}",
                        'alert-type' => 'success',
                    ]);
        //parent::store($request);
    }


}
