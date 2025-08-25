<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_user_has_required_attributes(): void
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $this->assertEquals('Jane Doe', $user->name);
        $this->assertEquals('jane@example.com', $user->email);
        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }

    public function test_user_can_have_role(): void
    {
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create();

        $user->assignRole($role);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole($role));
    }

    public function test_user_can_have_multiple_roles(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $user = User::factory()->create();

        $user->assignRole([$adminRole, $moderatorRole]);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('moderator'));
        $this->assertTrue($user->hasRole($adminRole));
        $this->assertTrue($user->hasRole($moderatorRole));
    }

    public function test_user_can_remove_role(): void
    {
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create();

        $user->assignRole($role);
        $this->assertTrue($user->hasRole('admin'));

        $user->removeRole($role);
        $this->assertFalse($user->hasRole('admin'));
    }

    public function test_user_can_sync_roles(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        
        $user = User::factory()->create();

        // Assigner admin et moderator
        $user->assignRole([$adminRole, $moderatorRole]);
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('moderator'));

        // Synchroniser avec user et moderator seulement
        $user->syncRoles([$userRole, $moderatorRole]);
        $this->assertFalse($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('moderator'));
        $this->assertTrue($user->hasRole('user'));
    }

    public function test_user_can_check_permission(): void
    {
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $permission = \Spatie\Permission\Models\Permission::create([
            'name' => 'delete users',
            'guard_name' => 'web'
        ]);
        
        $role->givePermissionTo($permission);
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->assertTrue($user->hasPermissionTo('delete users'));
        $this->assertTrue($user->can('delete users'));
    }

    public function test_user_has_any_role(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $user = User::factory()->create();

        $user->assignRole($adminRole);

        $this->assertTrue($user->hasAnyRole(['admin', 'moderator']));
        $this->assertTrue($user->hasAnyRole('admin', 'moderator'));
        $this->assertFalse($user->hasAnyRole(['user', 'guest']));
    }

    public function test_user_has_all_roles(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $user = User::factory()->create();

        $user->assignRole([$adminRole, $moderatorRole]);

        $this->assertTrue($user->hasAllRoles(['admin', 'moderator']));
        $this->assertTrue($user->hasAllRoles('admin', 'moderator'));
        $this->assertFalse($user->hasAllRoles(['admin', 'user']));
    }

    public function test_user_can_get_role_names(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $user = User::factory()->create();

        $user->assignRole([$adminRole, $moderatorRole]);

        $roleNames = $user->getRoleNames();
        $this->assertContains('admin', $roleNames);
        $this->assertContains('moderator', $roleNames);
        $this->assertCount(2, $roleNames);
    }

    public function test_user_can_get_permission_names(): void
    {
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $permission1 = \Spatie\Permission\Models\Permission::create([
            'name' => 'delete users',
            'guard_name' => 'web'
        ]);
        $permission2 = \Spatie\Permission\Models\Permission::create([
            'name' => 'edit users',
            'guard_name' => 'web'
        ]);
        
        $role->givePermissionTo([$permission1, $permission2]);
        $user = User::factory()->create();
        $user->assignRole($role);

        $permissionNames = $user->getPermissionNames();
        $this->assertContains('delete users', $permissionNames);
        $this->assertContains('edit users', $permissionNames);
        $this->assertCount(2, $permissionNames);
    }

    public function test_user_factory_creates_valid_user(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->email);
        $this->assertNotEmpty($user->password);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_user_factory_creates_multiple_users(): void
    {
        $users = User::factory()->count(3)->create();

        $this->assertCount(3, $users);
        $this->assertInstanceOf(User::class, $users->first());
        $this->assertInstanceOf(User::class, $users->last());
    }

    public function test_user_can_be_updated(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $user->update([
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_user_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $userId = $user->id;
        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }
}
