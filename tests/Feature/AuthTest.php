<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // 正常系：利用者ログインが成功する
    public function test_利用者ログインが成功する(): void
    {
        User::factory()->create([
            'email'    => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'user@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/conference-rooms');
    }

    // 異常系：パスワードが違うとログインできない
    public function test_パスワードが違うと利用者ログインできない(): void
    {
        User::factory()->create([
            'email'    => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }

    // 正常系：会員登録が成功する
    public function test_会員登録が成功する(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'テストユーザー',
            'email'                 => 'user@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/conference-rooms');
        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
        ]);
    }

    // 正常系：管理者ログインが成功する
    public function test_管理者ログインが成功する(): void
    {
        Admin::create([
            'name'     => '管理者',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/admin/login', [
            'email'    => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    // 異常系：未ログインは会議室一覧にアクセスできない
    public function test_未ログインは会議室一覧にアクセスできない(): void
    {
        $response = $this->get('/conference-rooms');
        $response->assertRedirect('/login');
    }

    // 異常系：未ログインは管理者ダッシュボードにアクセスできない
    public function test_未ログインは管理者ダッシュボードにアクセスできない(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
                                                                                                    }
                                                                                                }git remote add origin https://github.com/moriyumi0859-ux/library-app.git
git branch -M main
git push -u origin main