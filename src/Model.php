<?php

namespace Sinofaneliu\LaravelStart;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    //
    public static function boot() {
        parent::boot();
        
        $events = [
            'retrieved',
            'creating',
            'created',
            'updating',
            'updated',
            'saving',
            'saved',
            'deleting',
            'deleted',
            'restoring',
            'restored'
        ];

        foreach($events as $event) {
            $method = 'do'.ucfirst($event);
            if (!method_exists(static::class, $method)) {
                continue;
            }
            static::$event(function ($model) use ($method) {
                static::$method($model);
            });
        }
    }

    public static function destroy($ids)
    {
        DB::beginTransaction();
        try {
            parent::destroy($ids);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function scopeGetTree($query, $key = null, $parentKey = null, $coverKey = null, $childrenStr = 'children')
    {
        $key = $key ?: $this->treeKey ?: 'id';
        $parentKey = $parentKey ?: $this->treeParentKey ?: 'parent_'.$key;
        $coverKey = $coverKey ?: $key;

        $data = $query->get()->keyBy($key);

        $data->each(function ($item) use ($childrenStr) {
            $item->$childrenStr = collect();
        });

        foreach($data as $k => $item) {
            if($item->$parentKey && $item->$parentKey!=$item->$key){
 
                $data[$item->$parentKey]->$childrenStr->push($item);
            }
        }

        foreach($data as $k => $item) {
            if ($item->$parentKey && $item->$parentKey != $item->$key) {
                unset($data[$k]);
            }
        }
        
        return $data->values();
    }
}
