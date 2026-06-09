<?php

namespace App\Scaffolders;

use Tir\Crud\Support\Enums\ActionType;
use Tir\Crud\Support\Scaffold\Actions;
use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\TextArea;
use Tir\Crud\Support\Scaffold\Fields\Number;
use Tir\Crud\Support\Scaffold\Fields\Select;
use Tir\Crud\Support\Scaffold\Fields\CheckBox;
use Tir\Crud\Support\Scaffold\Fields\DatePicker;
use Tir\Crud\Support\Scaffold\Fields\FileUploader;
use Tir\Crud\Support\Scaffold\Fields\Additional;
use Tir\Crud\Support\Scaffold\Fields\Password;
use Tir\Crud\Support\Scaffold\Fields\SwitchBox;
use App\Models\SampleModel;
use App\Models\User;

/**
 * SampleModelScaffolder - Comprehensive example showcasing all field types
 *
 * This scaffolder demonstrates every available field type in the Tir CRUD framework
 * and serves as a reference implementation for developers.
 *
 * Features demonstrated:
 * - All field types (Text, Email, Number, Select, Boolean, Date, File, etc.)
 * - Conditional field display using magic methods and helpers
 * - Validation rules and custom messages
 * - Relationships and data sources
 * - File uploads and image handling
 * - JSON/Additional fields
 * - Advanced field configurations
 *
 * @package App\Scaffolders
 */
class SampleModelScaffolder extends BaseScaffolder
{
    /**
     * Define the Eloquent model this scaffolder works with
     *
     * @return string Full class name of the model
     */
    protected function setModel(): string
    {
        return SampleModel::class;
    }

    /**
     * Set the module name for routing and permissions
     *
     * @return string Module identifier used in routes and ACL
     */
    public function setModuleName(): string
    {
        return 'sample-model';
    }

    /**
     * Set module title for UI display
     *
     * @return string Human-readable module title
     */
    protected function setModuleTitle(): string
    {
        return 'Sample Models';
    }

    /**
     * Define the fields for CRUD operations
     *
     * This method showcases every available field type with practical examples
     * and demonstrates different approaches for conditional logic.
     *
     * @return array Array of field definitions
     */
    public function setFields(): array
    {
        return [
            // =================================================================
            // TEXT FIELDS
            // =================================================================

            Text::make('title')
                ->display('Title')
                ->placeholder('Enter a descriptive title')
                ->rules(['required', 'string', 'max:255'])
                ->searchable()
                ->sortable()
                ->col(12), // Half width

            Text::make('slug')
                ->display('URL Slug')
                ->placeholder('auto-generated-from-title')
                ->rules(['nullable', 'string', 'max:255', 'unique:sample_models,slug'])
                ->hideWhenCreating() // Auto-generated, so hide on create
                ->showOnEditing($this->hasValue('slug'))
                ->col(12), // Half width

            TextArea::make('description')
                ->display('Short Description')
                ->placeholder('Brief description of the item...')
                ->rules(['nullable', 'string', 'max:500'])
                ->hideFromIndex()
                ->col(24), // Full width

            TextArea::make('content')
                ->display('Full Content')
                ->placeholder('Detailed content goes here...')
                ->rules(['nullable', 'string'])
                ->onlyOnEditing()
                ->onlyOnCreating()
                ->col(24),

            // =================================================================
            // EMAIL FIELD (using Text with email validation)
            // =================================================================

            Text::make('email')
                ->display('Contact Email')
                ->placeholder('contact@example.com')
                ->rules(['nullable', 'email', 'max:255'])
                ->showOnEditing($this->hasValue('email'))
                ->searchable()
                ->col(12),

            Text::make('website_url')
                ->display('Website URL')
                ->placeholder('https://example.com')
                ->rules(['nullable', 'url', 'max:255'])
                ->col(12),

            // =================================================================
            // NUMBER FIELDS
            // =================================================================

            Number::make('priority')
                ->display('Priority')
                ->placeholder('0')
                ->rules(['nullable', 'integer', 'min:0', 'max:100'])
                ->default(0)
                ->sortable()
                ->col(8),

            Number::make('price')
                ->display('Price ($)')
                ->placeholder('0.00')
                ->rules(['nullable', 'numeric', 'min:0'])
                ->col(8),

            Number::make('rating')
                ->display('Rating (1-5)')
                ->placeholder('0.0')
                ->rules(['nullable', 'numeric', 'min:0', 'max:5'])
                ->default(0)
                ->col(8),

            // =================================================================
            // BOOLEAN FIELDS (using CheckBox and SwitchBox)
            // =================================================================

            CheckBox::make('is_active')
                ->display('Active Status')
                ->default(true)
                ->col(8),

            SwitchBox::make('is_featured')
                ->display('Featured Item')
                ->default(false)
                ->showOnEditing($this->getValue('is_active', true))
                ->col(8),

            CheckBox::make('is_published')
                ->display('Published')
                ->default(false)
                ->col(8),

            // =================================================================
            // DATE FIELDS
            // =================================================================

            DatePicker::make('publish_date')
                ->display('Publish Date')
                ->rules(['nullable', 'date'])
                ->showOnEditing($this->getValue('is_published', false))
                ->col(12),

            DatePicker::make('start_datetime')
                ->display('Start Date')
                ->rules(['nullable', 'date'])
                ->col(12),

            DatePicker::make('end_datetime')
                ->display('End Date')
                ->rules(['nullable', 'date', 'after:start_datetime'])
                ->showOnEditing($this->hasValue('start_datetime'))
                ->col(12),

            Text::make('open_time')
                ->display('Opening Time (HH:MM)')
                ->placeholder('09:00')
                ->rules(['nullable', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'])
                ->col(12),

            // =================================================================
            // SELECT FIELDS
            // =================================================================

            Select::make('status')
                ->display('Status')
                ->rules(['required', 'in:draft,published,archived,deleted'])
                ->data([
                    ['value' => 'draft', 'label' => 'Draft'],
                    ['value' => 'published', 'label' => 'Published'],
                    ['value' => 'archived', 'label' => 'Archived'],
                    ['value' => 'deleted', 'label' => 'Deleted'],
                ])
                ->default('draft')
                ->filter() // Enable filtering
                ->col(12),

            Select::make('type')
                ->display('Type')
                ->rules(['required', 'in:basic,premium,enterprise'])
                ->data([
                    ['value' => 'basic', 'label' => 'Basic'],
                    ['value' => 'premium', 'label' => 'Premium'],
                    ['value' => 'enterprise', 'label' => 'Enterprise'],
                ])
                ->default('basic')
                ->filter() // Enable filtering
                ->col(12),

            Text::make('category')
                ->display('Category')
                ->placeholder('Enter category name')
                ->rules(['nullable', 'string', 'max:100'])
                ->searchable()
                ->filter() // Enable filtering
                ->col(12),

            // =================================================================
            // RELATIONSHIP FIELDS (using Select with manual data)
            // =================================================================

            Select::make('user_id')
                ->display('Author')
                ->rules(['nullable', 'exists:users,id'])
                ->data($this->getUserOptions())
                ->searchable()
                ->col(12),

            // Many-to-many relationship with users (authors)
            Select::make('authors')
                ->display('Authors')
                ->rules(['nullable', 'array'])
                ->data($this->getUserOptions())
                ->filter()
                ->relation('authors','name')
                ->multiple()
                ->searchable()
                ->col(12),

            Select::make('parent_id')
                ->display('Parent Item')
                ->rules(['nullable', 'exists:sample_models,id'])
                ->data($this->getParentOptions())
                ->hideWhenCreating() // Usually set after creation
                ->col(12),

            // =================================================================
            // FILE UPLOAD FIELDS
            // =================================================================

            // FileUploader::make('avatar')
            //     ->display('Avatar Image')
            //     ->rules(['nullable', 'image', 'max:2048']) // 2MB max
            //     ->hideFromIndex()
            //     ->col(12),

            // FileUploader::make('cover_image')
            //     ->display('Cover Image')
            //     ->rules(['nullable', 'image', 'max:5120']) // 5MB max
            //     ->hideFromIndex()
            //     ->col(12),

            // FileUploader::make('document_file')
            //     ->display('Document File')
            //     ->rules(['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240']) // 10MB max
            //     ->hideFromIndex()
            //     ->hideFromDetail()
            //     ->col(24),

            // =================================================================
            // JSON/ADDITIONAL FIELDS
            // =================================================================

            Additional::make('metadata')
                ->display('Metadata')
                ->children(
                    Text::make('seo_title')->display('SEO Title')->rules(['nullable', 'string', 'max:60']),
                    Text::make('seo_description')->display('SEO Description')->rules(['nullable', 'string', 'max:160']),
                    Text::make('keywords')->display('Keywords')->rules(['nullable', 'string']),
                )
                ->hideFromIndex()
                ->col(24),

            Additional::make('settings')
                ->display('Settings')
                ->children(
                    CheckBox::make('allow_comments')->display('Allow Comments')->default(true),
                    CheckBox::make('send_notifications')->display('Send Notifications')->default(false),
                    Select::make('visibility')->display('Visibility')
                        ->data([
                            ['value' => 'public', 'label' => 'Public'],
                            ['value' => 'private', 'label' => 'Private'],
                            ['value' => 'restricted', 'label' => 'Restricted'],
                        ])
                        ->default('public'),
                )
                ->hideFromIndex()
                ->onlyOnEditing()
                ->col(24),

            Additional::make('social_links')
                ->display('Social Media Links')
                ->children(
                    Text::make('facebook')->display('Facebook URL')->rules(['nullable', 'url']),
                    Text::make('twitter')->display('Twitter URL')->rules(['nullable', 'url']),
                    Text::make('linkedin')->display('LinkedIn URL')->rules(['nullable', 'url']),
                    Text::make('instagram')->display('Instagram URL')->rules(['nullable', 'url']),
                )
                ->hideFromIndex()
                ->hideFromDetail()
                ->onlyOnEditing()
                ->col(24),

            // =================================================================
            // ARRAY/TAGS FIELD (stored as JSON)
            // =================================================================

            Text::make('tags')
                ->display('Tags (comma separated)')
                ->placeholder('tag1,tag2,tag3')
                ->rules(['nullable', 'string'])
                ->hideFromIndex()
                ->col(24),
        ];
    }

    /**
     * Get user options for select field
     */
    private function getUserOptions(): array
    {
        return User::select('id as value', 'name as label')->get()->toArray();
    }

    /**
     * Get parent options for select field
     */
    private function getParentOptions(): array
    {
        return SampleModel::select('id as value', 'title as label')
            ->where('id', '!=', $this->currentModel()->id ?? 0)
            ->get()->toArray();
    }

    /**
     * Set custom validation messages
     *
     * @return array Custom validation messages
     */
    protected function getValidationMessages(): array
    {
        return [
            'title.required' => 'The title field is required and cannot be empty.',
            'slug.unique' => 'This URL slug is already taken. Please choose a different one.',
            'email.email' => 'Please enter a valid email address.',
            'website_url.url' => 'Please enter a valid URL starting with http:// or https://',
            'price.numeric' => 'Price must be a valid number.',
            'rating.max' => 'Rating cannot be more than 5.',
            'end_datetime.after' => 'End date must be after the start date.',
            'user_id.exists' => 'Selected author does not exist.',
            'avatar.image' => 'Avatar must be a valid image file.',
            'avatar.max' => 'Avatar image cannot be larger than 2MB.',
            'document_file.mimes' => 'Document must be a PDF or Word document.',
        ];
    }

    /**
     * Configure available actions
     *
     * @return array Actions configuration
     */
    protected function setActions(): array
    {
        return Actions::except(
            ActionType::FORCE_DELETE // Prevent permanent deletion
        );
    }

}
