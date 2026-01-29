<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            'Motor' => [
                'Olajok és folyadékok',
                'Szűrők',
                'Szíjak és görgők',
                'Tömítések',
                'Gyújtás',
                'Üzemanyag rendszer',
                'Hűtés',
                'Levegőellátás / Turbo',
            ],
            'Futómű' => [
                'Lengőkar',
                'Szilent',
                'Gömbfej',
                'Kormányzás',
                'Kerékcsapágy',
                'Rugózás / Lengéscsillapító',
            ],
            'Fék' => [
                'Fékbetét',
                'Féktárcsa',
                'Féknyereg',
                'Fékcső',
                'Fékfolyadék',
            ],
            'Elektromos' => [
                'Akkumulátor',
                'Generátor',
                'Indítómotor',
                'Világítás',
                'Szenzorok',
            ],
            'Karosszéria' => [
                'Lökhárító',
                'Sárvédő',
                'Ajtó elemek',
                'Tükrök',
                'Rácsok / díszlécek',
            ],
            'Kipufogó' => [
                'Katalizátor',
                'Részecskeszűrő (DPF)',
                'Dobok / csövek',
                'Lambda / NOx szenzor',
            ],
            'Kuplung / Váltó' => [
                'Kuplung szett',
                'Kettőstömegű lendkerék',
                'Váltóolaj',
                'Váltó alkatrészek',
            ],
            'Klíma / Fűtés' => [
                'Klímakompresszor',
                'Kondenzátor',
                'Párologtató',
                'Pollenszűrő',
            ],
        ];

        $sort = 1;

        foreach ($tree as $parentName => $children) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($parentName)],
                ['name' => $parentName, 'parent_id' => null, 'sort' => $sort++]
            );

            $childSort = 1;

            foreach ($children as $childName) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($parentName . '-' . $childName)],
                    ['name' => $childName, 'parent_id' => $parent->id, 'sort' => $childSort++]
                );
            }
        }
    }
}
