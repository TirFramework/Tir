<?php

/**
 * MODEL DATA ACCESS IN TIR SCAFFOLDERS - Complete Guide
 *
 * This guide explains how to access current model data in your scaffolders,
 * similar to how you use $this->property in Laravel models.
 */

namespace App\Scaffolders\Examples;

use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\Select;

class ModelDataAccessGuide extends BaseScaffolder
{
    protected function setModel(): string
    {
        return \App\Models\MinimalExample::class;
    }

    protected function setModuleName(): string
    {
        return 'model-data-examples';
    }

    /**
     * 🔥 COMPLETE GUIDE: Accessing Model Data in Scaffolders
     */
    public function setFields(): array
    {
        return [
            // ========================================
            // 1. MAGIC METHOD ACCESS (Laravel-like)
            // ========================================

            Text::make('title')
                ->display('Title')
                // ✅ Direct property access (like Laravel models)
                ->placeholder(isset($this->title) ? "Current: {$this->title}" : 'Enter title')
                ->rules('required'),

            // ========================================
            // 2. HELPER METHODS (Recommended & Safe)
            // ========================================

            Text::make('description')
                ->display('Description')
                // ✅ Safe way to check if property exists
                ->showOnEditing($this->hasValue('description'))
                // ✅ Safe way to get value with default
                ->placeholder($this->getValue('description', 'Enter description'))
                ->rules('nullable'),

            // ========================================
            // 3. CONDITIONAL FIELD DISPLAY
            // ========================================

            Text::make('slug')
                ->display('URL Slug')
                // ✅ Only show when editing (ID exists)
                ->showOnEditing(isset($this->id))
                // ✅ Dynamic validation based on edit mode
                ->rules($this->hasValue('id') ? ['nullable'] : ['required']),

            // ========================================
            // 4. DYNAMIC FIELD TYPES
            // ========================================

            // This method returns different field types based on model state
            $this->getDynamicStatusField(),

            // ========================================
            // 5. RELATIONSHIP-AWARE FIELDS
            // ========================================

            Select::make('category_id')
                ->display('Category')
                // ✅ Show current category name when editing
                ->placeholder($this->getCurrentCategoryName())
                ->rules('required'),

            // ========================================
            // 6. FULL MODEL ACCESS
            // ========================================

            Text::make('metadata')
                ->display('Metadata')
                ->default($this->getModelMetadata())
                ->onlyOnDetail(),
        ];
    }

    /**
     * 📘 EXAMPLE: Dynamic field based on model state
     */
    private function getDynamicStatusField()
    {
        // Check if we're editing an existing model
        if ($this->currentModel() && $this->hasValue('id')) {
            // Editing mode - show dropdown with current status
            return Select::make('status')
                ->display('Status (Editing Mode)')
                ->data([
                    ['value' => 'draft', 'label' => 'Draft'],
                    ['value' => 'published', 'label' => 'Published'],
                    ['value' => 'archived', 'label' => 'Archived'],
                ])
                ->default($this->getValue('status', 'draft'));
        } else {
            // Creating mode - simple text field
            return Text::make('status')
                ->display('Status (Creation Mode)')
                ->default('draft')
                ->readonly();
        }
    }

    /**
     * 📘 EXAMPLE: Using relationships
     */
    private function getCurrentCategoryName(): string
    {
        // Method 1: Direct property access
        if (isset($this->category_name)) {
            return "Current: {$this->category_name}";
        }

        // Method 2: Using full model access
        if ($this->currentModel() && $this->currentModel()->relationLoaded('category')) {
            return "Current: " . $this->currentModel()->category->name;
        }

        return 'Select category';
    }

    /**
     * 📘 EXAMPLE: Complex model data processing
     */
    private function getModelMetadata(): string
    {
        if (!$this->currentModel()) {
            return 'New record';
        }

        $model = $this->currentModel();
        $metadata = [];

        // Basic info
        $metadata[] = "ID: {$model->getKey()}";
        $metadata[] = "Class: " . get_class($model);

        // Timestamps (if available)
        if ($this->hasValue('created_at')) {
            $metadata[] = "Created: {$this->created_at}";
        }

        if ($this->hasValue('updated_at')) {
            $metadata[] = "Updated: {$this->updated_at}";
        }

        // Custom properties
        if ($this->hasValue('status')) {
            $metadata[] = "Status: {$this->getValue('status')}";
        }

        // Relationship counts
        if ($model->relationLoaded('users')) {
            $metadata[] = "Users: {$model->users->count()}";
        }

        return implode(' | ', $metadata);
    }

    /**
     * 📘 AVAILABLE HELPER METHODS:
     *
     * $this->hasValue($property)           - Check if property exists and is not null
     * $this->getValue($property, $default) - Get property value with optional default
     * $this->currentModel()                - Get full model instance
     * isset($this->property)               - Magic method check
     * $this->property                      - Magic method access
     */

    /**
     * 📘 PRACTICAL USE CASES:
     *
     * 1. Dynamic Placeholders:
     *    ->placeholder($this->getValue('name', 'Enter name'))
     *
     * 2. Conditional Visibility:
     *    ->showOnEditing($this->hasValue('id'))
     *
     * 3. State-based Validation:
     *    ->rules($this->getValue('type') === 'premium' ? ['required'] : ['nullable'])
     *
     * 4. Relationship Display:
     *    ->display("Edit " . $this->getValue('title', 'New Item'))
     *
     * 5. Default Values:
     *    ->default($this->getValue('status', 'active'))
     *
     * 6. Custom Logic:
     *    if ($this->currentModel() && $this->currentModel()->isPublished()) { ... }
     */

    /**
     * 🚨 IMPORTANT NOTES:
     *
     * 1. Model data is only available during EDIT/SHOW operations
     * 2. During CREATE, $this->currentModel() returns null
     * 3. Always check if model exists before accessing properties
     * 4. Use helper methods for safer access
     * 5. Magic methods work but are less IDE-friendly
     */
}
