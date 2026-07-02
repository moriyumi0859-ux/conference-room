<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ConferenceRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;                    
use Tests\TestCase;

class ConferenceRoomTest extends TestCase
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

    // 正常系：会議室登録が成功する
    public function test_会議室登録が成功する(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->post('/admin/conference-rooms', [
                'name'      => '会議室A',
                'capacity'  => 10,
                'equipment' => 'プロジェクター',
            ]);

        $response->assertRedirect('/admin/conference-rooms');
        $this->assertDatabaseHas('conference_rooms', [
            'name'     => '会議室A',
            'capacity' => 10,
        ]);
    }

    // 異常系：会議室名が未入力だと登録できない
    public function test_会議室名が未入力だと登録できない(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->post('/admin/conference-rooms', [
                'name'     => '',
                'capacity' => 10,
            ]);

        $response->assertSessionHasErrors('name');
    }

    // 異常系：会議室名が重複すると登録できない
    public function test_会議室名が重複すると登録できない(): void
    {
        $admin = $this->createAdmin();

        ConferenceRoom::create([
            'name'     => '会議室A',
            'capacity' => 10,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->post('/admin/conference-rooms', [
                'name'     => '会議室A',
                'capacity' => 5,
            ]);

        $response->assertSessionHasErrors('name');
    }

    // 異常系：定員数が数字以外だと登録できない
    public function test_定員数が数字以外だと登録できない(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->post('/admin/conference-rooms', [
                'name'     => '会議室B',
                'capacity' => 'abc',
            ]);

        $response->assertSessionHasErrors('capacity');
    }

    // 正常系：会議室編集が成功する
    public function test_会議室編集が成功する(): void
    {
        $admin = $this->createAdmin();
        $room  = ConferenceRoom::create([
            'name'     => '会議室A',
            'capacity' => 10,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->put("/admin/conference-rooms/{$room->id}", [
                'name'     => '会議室A（改）',
                'capacity' => 15,
            ]);

        $response->assertRedirect('/admin/conference-rooms');
        $this->assertDatabaseHas('conference_rooms', [
            'name'     => '会議室A（改）',
            'capacity' => 15,
        ]);
    }

    // 正常系：会議室削除が成功する
    public function test_会議室削除が成功する(): void
    {
        $admin = $this->createAdmin();
        $room  = ConferenceRoom::create([
            'name'     => '会議室A',
            'capacity' => 10,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->delete("/admin/conference-rooms/{$room->id}");

        $response->assertRedirect('/admin/conference-rooms');
        $this->assertDatabaseMissing('conference_rooms', [
            'id' => $room->id,
        ]);
    }

    // 異常系：未ログインは会議室管理にアクセスできない
    public function test_未ログインは会議室管理にアクセスできない(): void
    {
        $response = $this->get('/admin/conference-rooms');
        $response->assertRedirect('/admin/login');
    }
}