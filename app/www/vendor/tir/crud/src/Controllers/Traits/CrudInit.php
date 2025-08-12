<?php

namespace Tir\Crud\Controllers\Traits;

use Tir\Crud\Support\Response\CrudResponse;
use Tir\Crud\Support\Actions\AccessControlService;

trait CrudInit
{

    private mixed $model;
    private mixed $scaffolder;
    private ?AccessControlService $accessControl = null;

    protected abstract function setScaffolder(): string;

    public function __construct()
    {
        $this->scaffolderInit();
        $this->initAccessControl();

        // Auto setup crud hooks if method exists
        if (method_exists($this, 'setup')) {
            $this->setup();
        }
    }

    protected final function model()
    {
        return $this->model;
    }

    protected final function scaffolder()
    {
        return $this->scaffolder;
    }
    protected function setRequest(): string
    {
        return '';
    }

    protected function setResponse(): string
    {
        return CrudResponse::class;
    }


    protected final function response(){
        $response = $this->setResponse();
        return new $response;
    }


    private function scaffolderInit(): void
    {
        $s = $this->setScaffolder();
        $this->scaffolder = new $s;

        $m = $this->scaffolder->model();
        $this->model = new $m;
    }

    /**
     * Initialize access control service
     */
    private function initAccessControl(): void
    {
        $this->accessControl = AccessControlService::fromScaffolder($this->scaffolder);
        
        // Apply middleware if access control is enabled
        if ($this->accessControl->isEnabled()) {
            $this->middleware($this->accessControl->getMiddleware());
        }
    }

    /**
     * Get access control service
     */
    protected function accessControl(): AccessControlService
    {
        return $this->accessControl;
    }

    /**
     * Check if user can perform action (programmatic check)
     */
    protected function canPerform(string $action): bool
    {
        return $this->accessControl->canPerform($action);
    }

    /**
     * Enforce access control or abort with 403
     */
    protected function enforceAccess(string $action): void
    {
        $this->accessControl->enforce($action);
    }

    private function checkAccess(): void
    {
        // This method is now deprecated in favor of the new AccessControlService
        // The new system automatically applies middleware in initAccessControl()
    }


}
