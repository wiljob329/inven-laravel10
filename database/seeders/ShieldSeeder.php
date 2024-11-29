<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_entrada","view_any_entrada","create_entrada","update_entrada","restore_entrada","restore_any_entrada","replicate_entrada","reorder_entrada","delete_entrada","delete_any_entrada","force_delete_entrada","force_delete_any_entrada","view_material","view_any_material","create_material","update_material","restore_material","restore_any_material","replicate_material","reorder_material","delete_material","delete_any_material","force_delete_material","force_delete_any_material","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_salida","view_any_salida","create_salida","update_salida","restore_salida","restore_any_salida","replicate_salida","reorder_salida","delete_salida","delete_any_salida","force_delete_salida","force_delete_any_salida","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]},{"name":"deposito","guard_name":"web","permissions":["view_entrada","view_any_entrada","create_entrada","update_entrada","restore_entrada","restore_any_entrada","replicate_entrada","reorder_entrada","view_material","view_any_material","create_material","update_material","restore_material","restore_any_material","replicate_material","reorder_material","view_salida","view_any_salida","create_salida","update_salida","restore_salida","restore_any_salida","replicate_salida","reorder_salida"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
