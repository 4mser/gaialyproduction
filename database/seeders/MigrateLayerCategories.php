<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Layer;
use App\Models\FindingType;



class MigrateLayerCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::whereIn('profile_id', [1, 2])->get();
        foreach ($users as $user) {
            $this->createIdsPerUser($user->id);
            $layers = Layer::where("data", "!=", null)->where('user_id', $user->id)->get();
            foreach ($layers as $layer) {
                $layer->data = json_decode($layer->data);
                foreach ($layer->data as $subdata) {
                    if( !is_null($subdata->category) ){
                        $name = FindingType::where('id', $subdata->category)->first()->name;
                        echo "layer: " . $layer->id . " - from category: " . $subdata->category;
                        $subdata->category = FindingType::where('name', $name)->where('parent_user_id', $layer->user_id)->first()->id;
                        //write in console
                        echo " -  to category: " . $subdata->category . "\n";
                    }
                }
                $layer->data = json_encode($layer->data);
                $layer->save();
            }
        }

    }


    public function createIdsPerUser($userId){
        $items = FindingType::where("parent_user_id", null)->get()->toArray();
        foreach ($items as $item) {
            if (FindingType::where('name', $item['name'])->where('parent_user_id', $userId)->first() == null) {
                $item['parent_user_id'] = $userId;
                unset($item['id']);
                FindingType::create($item);
            }
        }
    }
}
