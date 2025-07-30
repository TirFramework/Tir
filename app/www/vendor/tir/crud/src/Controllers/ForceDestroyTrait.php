<?php

namespace Tir\Crud\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Tir\Crud\Events\ForceDestroyEvent;

trait ForceDestroyTrait
{

    /**
     *  This function destroy an item
     * @return \Illuminate\Support\Facades\View index
     */
    public function forceDestroy($id)
    {
        event(new ForceDestroyEvent($this->name));
        $item = $this->findForForceDestroy($id);

        $item->delete();
        $message = trans('core::message.item-permanently-deleted', ['item' => trans("message.item.$this->name")]); //translate message
        Session::flash('message', $message);
        return Redirect::to(route("$this->name.trash"));
    }



        /**
     * This function find an object model and if permission == owner return only owner item
     * @return eloquent
     */
    public function findForForceDestroy($id)
    {
        $items = $this->model()::onlyTrashed()->findOrFail($id);
        if($this->permission == 'owner'){
            $items = $items->OnlyOwner();
        }
        return $items;
    }

}
