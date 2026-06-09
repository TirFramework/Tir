<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SampleModel;
use App\Models\MinimalExample;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * DashboardController - Main admin panel dashboard
 *
 * Provides statistics, quick links, and feature overview
 * for the Tir CRUD demo project.
 */
class DashboardController extends Controller
{
    /**
     * Get dashboard data with statistics and quick links
     *
     * @return array Dashboard configuration
     */
    public function index()
    {
        return [
            'welcome' => 'Welcome to Tir CRUD Demo',
            'subtitle' => 'A comprehensive demonstration of the Tir/CRUD framework',
            'message' => 'This dashboard showcases all features of the CRUD framework with demo modules for learning and experimentation.',

            // Statistics
            'stats' => [
                [
                    'title' => 'Total Users',
                    'count' => User::count(),
                    'icon' => 'UserOutlined',
                    'color' => '#1890ff',
                    'link' => '/admin/user'
                ],
                [
                    'title' => 'Categories',
                    'count' => Category::count(),
                    'icon' => 'FolderOutlined',
                    'color' => '#52c41a',
                    'link' => '/admin/category'
                ],
                [
                    'title' => 'Sample Models',
                    'count' => SampleModel::count(),
                    'icon' => 'FileTextOutlined',
                    'color' => '#faad14',
                    'link' => '/admin/sample-model'
                ],
                [
                    'title' => 'Examples',
                    'count' => MinimalExample::count(),
                    'icon' => 'BorderOutlined',
                    'color' => '#eb2f96',
                    'link' => '/admin/minimal-example'
                ],
            ],

            // Quick Links
            'quick_links' => [
                [
                    'title' => 'Users Management',
                    'description' => 'Create, edit, and manage user records',
                    'url' => '/admin/user',
                    'icon' => 'UserOutlined',
                    'color' => '#1890ff'
                ],
                [
                    'title' => 'Categories',
                    'description' => 'Organize content with categories',
                    'url' => '/admin/category',
                    'icon' => 'FolderOutlined',
                    'color' => '#52c41a'
                ],
                [
                    'title' => 'Sample Models',
                    'description' => 'Example CRUD operations',
                    'url' => '/admin/sample-model',
                    'icon' => 'FileTextOutlined',
                    'color' => '#faad14'
                ],
                [
                    'title' => 'Minimal Examples',
                    'description' => 'Simplified CRUD implementation',
                    'url' => '/admin/minimal-example',
                    'icon' => 'BorderOutlined',
                    'color' => '#eb2f96'
                ],
                [
                    'title' => 'Flexible Fields',
                    'description' => 'Explore different field types',
                    'url' => '/admin/flexible-fields',
                    'icon' => 'FormOutlined',
                    'color' => '#722ed1'
                ],
            ],

            // Core Features
            'features' => [
                [
                    'title' => 'CRUD Operations',
                    'description' => 'Complete Create, Read, Update, Delete operations with automatic validation',
                    'icon' => 'AppstoreOutlined'
                ],
                [
                    'title' => 'Field Types',
                    'description' => '15+ field types including text, relations, files, dates, and more',
                    'icon' => 'FormOutlined'
                ],
                [
                    'title' => 'Data Relations',
                    'description' => 'Support for belongsTo, hasMany, and belongsToMany relationships',
                    'icon' => 'LinkOutlined'
                ],
                [
                    'title' => 'Access Control',
                    'description' => 'Built-in scope-based and method-level permission system',
                    'icon' => 'LockOutlined'
                ],
                [
                    'title' => 'Hook System',
                    'description' => 'Extensible pre/post action callbacks for customization',
                    'icon' => 'CodeOutlined'
                ],
                [
                    'title' => 'Automatic Validation',
                    'description' => 'Field-based validation rules with automatic error handling',
                    'icon' => 'CheckCircleOutlined'
                ],
            ],

            // Documentation Links
            'documentation' => [
                [
                    'title' => 'Getting Started',
                    'url' => '/docs/getting-started',
                    'description' => 'Quick setup and first CRUD module'
                ],
                [
                    'title' => 'API Reference',
                    'url' => '/docs/api-reference',
                    'description' => 'Complete API documentation'
                ],
                [
                    'title' => 'Scaffolder Guide',
                    'url' => '/docs/scaffolder',
                    'description' => 'Learn how to create scaffolders'
                ],
                [
                    'title' => 'Field Types',
                    'url' => '/docs/fields',
                    'description' => 'All available field types'
                ],
                [
                    'title' => 'Hooks & Callbacks',
                    'url' => '/docs/hooks',
                    'description' => 'Customize CRUD behavior'
                ],
            ],

            // Demo Information
            'demo_info' => [
                'project' => 'Tir CRUD Framework',
                'version' => '1.0.0',
                'framework' => 'Laravel 12.0+',
                'database' => 'MySQL/SQLite',
                'status' => 'Demo Ready'
            ]
        ];
    }
}
