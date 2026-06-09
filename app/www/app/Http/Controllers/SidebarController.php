<?php

namespace App\Http\Controllers;

use App\Infrastructure\Helpers\Sidebar;
use App\Infrastructure\Helpers\MenuItem;
use App\Models\User;
use App\Models\SampleModel;

/**
 * SidebarController - Manages Admin Panel Navigation
 *
 * This controller handles the sidebar and topbar menu generation for the admin panel.
 * It provides:
 * - sidebar(): Admin panel left navigation menu
 * - topbar(): User profile and action menu in top right
 */
class SidebarController extends Controller
{
    /**
     * Generate sidebar menu for admin panel
     *
     * Returns a structured menu showing all available CRUD modules
     * and demo features organized by category.
     *
     * @return array Menu structure for frontend rendering
     */
    public function sidebar()
    {
        $sidebar = new Sidebar();

        $sidebar->add(
            // Dashboard
            MenuItem::name('dashboard')
                ->title('Dashboard')
                ->link('/admin/dashboard')
                ->icon('DashboardOutlined'),

            // Demo CRUD Modules
            MenuItem::name('crudDemo')
                ->title('CRUD Demo Modules')
                ->icon('AppstoreOutlined')
                ->access(true)
                ->children(
                    MenuItem::name('user')
                        ->title('Users')
                        ->link('/admin/user')
                        ->icon('UserOutlined')
                        ->access(true),

                    MenuItem::name('userRole')
                        ->title('User Roles')
                        ->link('/admin/user-role')
                        ->icon('LockOutlined')
                        ->access(true),

                    MenuItem::name('category')
                        ->title('Categories')
                        ->link('/admin/category')
                        ->icon('FolderOutlined')
                        ->access(true),

                    MenuItem::name('sampleModel')
                        ->title('Sample Models')
                        ->link('/admin/sample-model')
                        ->icon('FileTextOutlined')
                        ->access(true),

                    MenuItem::name('minimalExample')
                        ->title('Minimal Examples')
                        ->link('/admin/minimal-example')
                        ->icon('BorderOutlined')
                        ->access(true),
                ),

            // Field Types & Features Demo
            MenuItem::name('fieldsDemo')
                ->title('Field Types Demo')
                ->icon('FileOutlined')
                ->access(true)
                ->children(
                    MenuItem::name('flexibleFields')
                        ->title('Flexible Fields')
                        ->link('/admin/flexible-fields')
                        ->icon('FormOutlined')
                        ->access(true),
                ),

            // Documentation & Help
            MenuItem::name('documentation')
                ->title('Documentation')
                ->icon('FileTextOutlined')
                ->access(true)
                ->children(
                    MenuItem::name('gettingStarted')
                        ->title('Getting Started')
                        ->link('/docs/getting-started')
                        ->icon('RocketOutlined')
                        ->access(true),

                    MenuItem::name('apiReference')
                        ->title('API Reference')
                        ->link('/docs/api-reference')
                        ->icon('ApiOutlined')
                        ->access(true),

                    MenuItem::name('examples')
                        ->title('Code Examples')
                        ->link('/docs/examples')
                        ->icon('CodeOutlined')
                        ->access(true),
                )
        );

        return $sidebar->get();
    }

    /**
     * Generate topbar menu (user profile, settings, logout)
     *
     * Returns user information and quick action menu for the top right corner
     *
     * @return object Topbar configuration
     */
    public function topbar()
    {
        $user = auth()->user();
        $lang = $user->settings['locale'] ?? config('app.locale', 'en');

        return (object)[
            'name' => 'Tir CRUD Demo',
            'home' => '/admin/dashboard',
            'lang' => $lang,
            'user' => [
                'name' => $user?->name ?? 'Guest',
                'email' => $user?->email ?? 'guest@example.com',
                'avatar' => $user?->avatar_img ?? null,
                'initials' => $this->getUserInitials($user?->name ?? 'U'),
            ],
            'menu' => [
                [
                    'key' => 'profile',
                    'label' => 'Profile',
                    'icon' => 'UserOutlined',
                    'link' => '/admin/user',
                ],
                [
                    'key' => 'divider',
                    'type' => 'divider',
                ],
                [
                    'key' => 'documentation',
                    'label' => 'Documentation',
                    'icon' => 'FileTextOutlined',
                    'link' => '/docs',
                ],
                [
                    'key' => 'divider-2',
                    'type' => 'divider',
                ],
                [
                    'key' => 'logout',
                    'label' => 'Logout',
                    'icon' => 'LogoutOutlined',
                    'action' => 'logout',
                    'danger' => true,
                ],
            ],
            'notifications' => [
                'count' => 0,
                'hasNew' => false,
            ]
        ];
    }

    /**
     * Get user initials for avatar fallback display
     *
     * @param string $name User full name
     * @return string Two-character initials
     */
    private function getUserInitials($name)
    {
        $names = explode(' ', trim($name));
        $initials = '';

        foreach ($names as $n) {
            if (strlen($n) > 0) {
                $initials .= strtoupper($n[0]);
            }
            if (strlen($initials) >= 2) break;
        }

        return $initials ?: 'U';
    }
}
