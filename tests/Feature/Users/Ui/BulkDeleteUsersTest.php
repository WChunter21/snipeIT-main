<?php

namespace Tests\Feature\Users\Ui;

use App\Models\Accessory;
use App\Models\Consumable;
use App\Models\Statuslabel;
use App\Models\User;
use Tests\TestCase;

class BulkDeleteUsersTest extends TestCase
{
    public function testAccessoryCheckinsAreProperlyLogged()
    {
        [$accessoryA, $accessoryB] = Accessory::factory()->count(2)->create();
        [$userA, $userB] = User::factory()->count(2)->create();

        // Add checkouts for multiple accessories to multiple users to get different ids in the mix
        $this->attachAccessoryToUser($accessoryA, $userA);
        $this->attachAccessoryToUser($accessoryA, $userB);
        $this->attachAccessoryToUser($accessoryB, $userA);
        $this->attachAccessoryToUser($accessoryB, $userB);

        $this->actingAs(User::factory()->editUsers()->create())
            ->post(route('users/bulksave'), [
                'ids' => [
                    $userA->id,
                ],
                'status_id' => Statuslabel::factory()->create()->id,
            ])
            ->assertRedirect();

        // These assertions check against a bug where the wrong value from
        // accessories_users was being populated in action_logs.item_id.
        $this->assertDatabaseHas('action_logs', [
            'action_type' => 'checkin from',
            'target_id' => $userA->id,
            'target_type' => User::class,
            'note' => 'Bulk checkin items',
            'item_type' => Accessory::class,
            'item_id' => $accessoryA->id,
        ]);

        $this->assertDatabaseHas('action_logs', [
            'action_type' => 'checkin from',
            'target_id' => $userA->id,
            'target_type' => User::class,
            'note' => 'Bulk checkin items',
            'item_type' => Accessory::class,
            'item_id' => $accessoryB->id,
        ]);
    }

    public function testConsumableCheckinsAreProperlyLogged()
    {
        [$consumableA, $consumableB] = Consumable::factory()->count(2)->create();
        [$userA, $userB] = User::factory()->count(2)->create();

        // Add checkouts for multiple consumables to multiple users to get different ids in the mix
        $this->attachConsumableToUser($consumableA, $userA);
        $this->attachConsumableToUser($consumableA, $userB);
        $this->attachConsumableToUser($consumableB, $userA);
        $this->attachConsumableToUser($consumableB, $userB);

        $this->actingAs(User::factory()->editUsers()->create())
            ->post(route('users/bulksave'), [
                'ids' => [
                    $userA->id,
                ],
                'status_id' => Statuslabel::factory()->create()->id,
            ])
            ->assertRedirect();

        // These assertions check against a bug where the wrong value from
        // consumables_users was being populated in action_logs.item_id.
        $this->assertDatabaseHas('action_logs', [
            'action_type' => 'checkin from',
            'target_id' => $userA->id,
            'target_type' => User::class,
            'note' => 'Bulk checkin items',
            'item_type' => Consumable::class,
            'item_id' => $consumableA->id,
        ]);

        $this->assertDatabaseHas('action_logs', [
            'action_type' => 'checkin from',
            'target_id' => $userA->id,
            'target_type' => User::class,
            'note' => 'Bulk checkin items',
            'item_type' => Consumable::class,
            'item_id' => $consumableB->id,
        ]);
    }

    private function attachAccessoryToUser(Accessory $accessory, User $user): void
    {
        $accessory->users()->attach($accessory->id, [
            'accessory_id' => $accessory->id,
            'assigned_to' => $user->id,
        ]);
    }

    private function attachConsumableToUser(Consumable $consumable, User $user): void
    {
        $consumable->users()->attach($consumable->id, [
            'consumable_id' => $consumable->id,
            'assigned_to' => $user->id,
        ]);
    }
}