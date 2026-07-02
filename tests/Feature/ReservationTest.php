<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ConferenceRoom;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name'     => '管理者',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    private function createRoom(): ConferenceRoom
    {
        return ConferenceRoom::create([
            'name'     => '会議室A',
            'capacity' => 10,
        ]);
    }

    // 正常系：予約が成功する
    public function test_予約が成功する(): void
    {
        $user = User::factory()->create();
        $room = $this->createRoom();

        $response = $this->actingAs($user)
            ->post('/reservations', [
                'conference_room_id' => $room->id,
                'reservation_date'   => now()->addDay()->toDateString(),
                'start_time'         => '10:00',
                'end_time'           => '11:00',
            ]);

        $response->assertRedirect('/mypage');
        $this->assertDatabaseHas('reservations', [
            'user_id'            => $user->id,
            'conference_room_id' => $room->id,
            'status'             => '予約中',
        ]);
    }

    // 異常系：二重予約できない
    public function test_同じ時間帯に二重予約できない(): void
    {
        $user = User::factory()->create();
        $room = $this->createRoom();

        // 1回目の予約
        Reservation::create([
            'user_id'            => $user->id,
            'conference_room_id' => $room->id,
            'reservation_date'   => now()->addDay()->toDateString(),
            'start_time'         => '10:00',
            'end_time'           => '11:00',
            'status'             => '予約中',
        ]);

        // 2回目の予約（同じ時間帯）
        $response = $this->actingAs($user)
            ->post('/reservations', [
                'conference_room_id' => $room->id,
                'reservation_date'   => now()->addDay()->toDateString(),
                'start_time'         => '10:00',
                'end_time'           => '11:00',
            ]);

        $response->assertSessionHas('error');
    }

    // 異常系：過去の日付で予約できない
    public function test_過去の日付で予約できない(): void
    {
        $user = User::factory()->create();
        $room = $this->createRoom();

        $response = $this->actingAs($user)
            ->post('/reservations', [
                'conference_room_id' => $room->id,
                'reservation_date'   => now()->subDay()->toDateString(),
                'start_time'         => '10:00',
                'end_time'           => '11:00',
            ]);

        $response->assertSessionHasErrors('reservation_date');
    }

    // 正常系：利用者が予約キャンセルできる
    public function test_利用者が予約キャンセルできる(): void
    {
        $user = User::factory()->create();
        $room = $this->createRoom();

        $reservation = Reservation::create([
            'user_id'            => $user->id,
            'conference_room_id' => $room->id,
            'reservation_date'   => now()->addDay()->toDateString(),
            'start_time'         => '10:00',
            'end_time'           => '11:00',
            'status'             => '予約中',
        ]);

        $response = $this->actingAs($user)
            ->delete("/reservations/{$reservation->id}");

        $response->assertRedirect('/mypage');
        $this->assertDatabaseHas('reservations', [
            'id'     => $reservation->id,
            'status' => 'キャンセル',
        ]);
    }

    // 正常系：管理者が予約キャンセルできる
    public function test_管理者が予約キャンセルできる(): void
    {
        $admin = $this->createAdmin();
        $user  = User::factory()->create();
        $room  = $this->createRoom();

        $reservation = Reservation::create([
            'user_id'            => $user->id,
            'conference_room_id' => $room->id,
            'reservation_date'   => now()->addDay()->toDateString(),
            'start_time'         => '10:00',
            'end_time'           => '11:00',
            'status'             => '予約中',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->delete("/admin/reservations/{$reservation->id}");

        $response->assertRedirect('/admin/reservations');
        $this->assertDatabaseHas('reservations', [
            'id'     => $reservation->id,
            'status' => 'キャンセル',
        ]);
    }

    // 異常系：未ログインは予約できない
    public function test_未ログインは予約できない(): void
    {
        $room = $this->createRoom();

        $response = $this->post('/reservations', [
            'conference_room_id' => $room->id,
            'reservation_date'   => now()->addDay()->toDateString(),
            'start_time'         => '10:00',
            'end_time'           => '11:00',
        ]);

        $response->assertRedirect('/login');
    }
}