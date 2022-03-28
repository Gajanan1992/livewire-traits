<?php

namespace App\traits;

trait HasPlaceholders
{
    private $excludeModels = [
        'AllOrOwn', 'CallAssenze', 'CallContribution', 'CallDimension', 'CallDocument',
        'CallMode', 'CallRegime', 'CallThematic', 'CompanyAdministrator', 'CallUcsReport',
        'CompanyAidScheme', 'CompanyDocument', 'CompanyReferent', 'CompanySector', 'Category',
        'CompanyStudent', 'ContractType', 'ContractTypeCcnl', 'Message', 'DelegationDocument',
        'ModCertification', 'OrganizationAdministrator', 'participants', 'Permission', 'Dimension',
        'PermissionTemplate', 'Placeholder', 'PlaceholderFunction', 'PlaceholderGroup', 'EditionRequest',
        'PlanReviewer', 'ProfessionalDocument', 'ProjectType', 'EditionStatus', 'ProjectCategory',
        'ProjectDocument', 'Qualification', 'ReferentProfessional', 'Region', 'EditionDocument',
        'Mod', 'ProjectRequest', 'RequestDocument', 'RequestModStudent', 'RequestThematicHour',
        'StudentDocument', 'PlanTemplate', 'Regime', 'Role', 'RequestStatus', 'StudyTitle', 'Template',
        'Thematic', 'threads', 'CommunicationTemplate', 'Country', 'Province', 'City', 'UserDashboard',
        'TrainingTiming', 'Typology', 'UserGuide', 'OrganizationDocument', 'PermissionUser',
        'PlanDocument', 'TicketSupport', 'EditionDelegation', 'User',
    ];

    private $excludeColumns = [
        'slug', 'password', 'exclude_group_email', 'region', 'province', 'city', 'company_id',
        'logo', 'first_name', 'last_name', 'email_verified_at', 'created_by', 'updated_by',
        'no_editions', 'project_id', 'plan_id', 'user_id', 'activity_id', 'region_id', 'province_id', 'subjects', 'referent_id',
    ];

    private $addColumns = [
        'created_by', 'updated_by',
    ];
    private $modelsFolderName = 'Models';

    public function getModelsList($exclude = true): array
    {
        $modelsPath = app_path($this->modelsFolderName);
        $modelsCollection = Collect(\File::files($modelsPath));
        $modelsNameList = Collect($modelsCollection->map(function ($sqlInfo) {
            return str_replace('.' . $sqlInfo->getExtension(), '', $sqlInfo->getBasename());
        }))->toArray();

        if ($exclude) {
            return array_diff($modelsNameList, $this->excludeModels);
        }

        return $modelsNameList;
    }

    public function getColumnList($modelName, $additionalColumns = false, $excludeColumns = []): array
    {
        $model = 'App\\Models\\' . $modelName;
        $model = new $model();

        $this->updateExcludedColumnList($excludeColumns);

        $columns = collect(array_diff($model->getFillable(), $this->excludeColumns))->map(function ($item, $key) use ($model) {
            return $model->getTable() . '.' . $item;
        });
        if ($additionalColumns) {
            foreach ($this->addColumns as $column) {
                $columns->push($model->getTable() . '.' . $column);
            }
        }

        return $columns->toArray(); //array_merge($columns, $this->addColumns);
    }

    private function updateExcludedColumnList($excludeColumns)
    {
        if (! empty($excludeColumns)) {
            $this->excludeColumns = array_merge($this->excludeColumns, $excludeColumns);
        }
    }

    public function getTranslatedColumnList($modelName, $excludeColumns = [], $additionalColumns = false): array
    {
        $model = 'App\\Models\\' . $modelName;
        $model = new $model();
        $columns = collect(array_diff($model->getFillable(), $excludeColumns))->map(function ($item, $key) use ($model, $modelName) {
            return [$model->getTable() . '.' . $item => $modelName . '.' . $item];
        });
        if ($additionalColumns) {
            foreach ($this->addColumns as $column) {
                $columns->push([$model->getTable() . '.' . $column => $modelName . '.' . $column]);
            }
        }

        return $columns->toArray(); //array_merge($columns, $this->addColumns);
    }

    public function verifyModel($modelName)
    {
        return $modelName;
    }
}
