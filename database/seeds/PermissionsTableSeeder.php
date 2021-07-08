<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => '1',
                'title' => 'user_management_access',
            ],
            [
                'id'    => '2',
                'title' => 'permission_create',
            ],
            [
                'id'    => '3',
                'title' => 'permission_edit',
            ],
            [
                'id'    => '4',
                'title' => 'permission_show',
            ],
            [
                'id'    => '5',
                'title' => 'permission_delete',
            ],
            [
                'id'    => '6',
                'title' => 'permission_access',
            ],
            [
                'id'    => '7',
                'title' => 'role_create',
            ],
            [
                'id'    => '8',
                'title' => 'role_edit',
            ],
            [
                'id'    => '9',
                'title' => 'role_show',
            ],
            [
                'id'    => '10',
                'title' => 'role_delete',
            ],
            [
                'id'    => '11',
                'title' => 'role_access',
            ],
            [
                'id'    => '12',
                'title' => 'user_create',
            ],
            [
                'id'    => '13',
                'title' => 'user_edit',
            ],
            [
                'id'    => '14',
                'title' => 'user_show',
            ],
            [
                'id'    => '15',
                'title' => 'user_delete',
            ],
            [
                'id'    => '16',
                'title' => 'user_access',
            ],
            [
                'id'    => '17',
                'title' => 'news_create',
            ],
            [
                'id'    => '18',
                'title' => 'news_edit',
            ],
            [
                'id'    => '19',
                'title' => 'news_delete',
            ],
            [
                'id'    => '20',
                'title' => 'news_access',
            ],
            [
                'id'    => '21',
                'title' => 'tizer_create',
            ],
            [
                'id'    => '22',
                'title' => 'tizer_edit',
            ],
            [
                'id'    => '23',
                'title' => 'tizer_show',
            ],
            [
                'id'    => '24',
                'title' => 'tizer_delete',
            ],
            [
                'id'    => '25',
                'title' => 'tizer_access',
            ],
            [
                'id'    => '26',
                'title' => 'category_access',
            ],
            [
                'id'    => '27',
                'title' => 'category_create',
            ],
            [
                'id'    => '28',
                'title' => 'category_edit',
            ],
            [
                'id'    => '29',
                'title' => 'category_delete',
            ],
            [
                'id'    => '30',
                'title' => 'category_show',
            ],
            [
                'id'    => '31',
                'title' => 'template_create',
            ],
            [
                'id'    => '32',
                'title' => 'template_edit',
            ],
            [
                'id'    => '33',
                'title' => 'template_show',
            ],
            [
                'id'    => '34',
                'title' => 'template_delete',
            ],
            [
                'id'    => '35',
                'title' => 'template_access',
            ],

        ];
        
        
        
        
        
        
        
        
        
        


        Permission::insert($permissions);
    }
}
