<?php

namespace Tir\Crud\Controllers;

use App\Http\Controllers\Controller;

/**
 * CRUD Controller Facade
 * 
 * This facade provides a stable interface for users, regardless of internal structure changes.
 * Users can extend this class and get all CRUD functionality without worrying about internal organization.
 * 
 * Usage:
 * class UserController extends CrudController
 * {
 *     protected function setScaffolder(): string
 *     {
 *         return UserScaffolder::class;
 *     }
 * }
 */
abstract class CrudController extends Controller
{
    // Use the main CRUD trait which includes all functionality
    use Traits\Crud;
}
