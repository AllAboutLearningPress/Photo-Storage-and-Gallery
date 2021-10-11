<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['id' => 1, 'name' => 'Send Invite', 'slug' => 'view-i'],
            ['id' => 2, 'name' => 'Upload Photo', 'slug' => 'upload-photo'],
            ['id' => 3, 'name' => 'Delete Invite', 'slug' => 'delete-photo'],
            ['id' => 4, 'name' => 'Download Photo', 'slug' => 'download-photo'],
            ['id' => 5, 'name' => 'View Edited photo', 'slug' => 'view-edited-photo'],
            ['id' => 6, 'name' => 'Clear Trash', 'slug' => 'clear-trash'],
            ['id' => 7, 'name' => 'Add tag to photo', 'slug' => 'add-tags'],
            ['id' => 8, 'name' => 'View Roles', 'slug' => 'view-roles'],
            ['id' => 9, 'name' => 'Create Role', 'slug' => 'create-role'],
            ['id' => 10, 'name' => 'Delete Role', 'slug' => 'delete-role'],
        ];

        $models = [
            [
                'name' => 'Photo',
                'plural' => 'photos'
            ],
            [
                'name' => 'User',
                'plural' => 'users'
            ],
            [
                'name' => 'Invitation',
                'plural' => 'invitations'
            ],
            [
                'name' => 'Role',
                'plural' => 'roles',

            ], [
                'name' => 'Tag',
                'plural' => 'tags'
            ], [
                'name' => 'Photo Share',
                'plural' => 'share_photos'
            ]

        ];
        $permissions = ['index', 'view', 'create', 'delete'];


        foreach ($models as $model) {
            foreach ($permissions as $perm) {
                Permission::forceCreate(
                    [
                        'name' => ucfirst($perm) . " " . $model['name'],
                        'slug' => Str::plural(Str::lower($model['plural'])) . "." . Str::lower($perm)
                    ]
                );
            }
        }
        $extra_perms = [
            [
                'name' => 'View Trash',
                'slug' => 'photos.trash'
            ],

            [
                'name' => 'Download Photo',
                'slug' => 'photos.download'
            ]
        ];

        foreach ($extra_perms as $perm) {
            Permission::forceCreate($perm);
        }
    }
}
