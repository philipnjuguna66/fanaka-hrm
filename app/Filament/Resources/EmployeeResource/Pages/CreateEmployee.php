<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Enums\EmploymentTerms;
use App\Filament\Resources\EmployeeResource;
use App\Models\BusinessUnit;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\JobGrade;
use App\Models\JobTitle;
use App\Models\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\DB;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;


class CreateEmployee extends CreateRecord
{

    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = EmployeeResource::class;

    public function hasSkippableSteps(): bool
    {
        return false;
    }


    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            DB::beginTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            /** @internal Read the DocBlock above the following method. */
            $this->createRecordAndCallHooks($data);


            DB::commit();

            Notification::make()
                ->success()
                ->body("Employee recorded successfully")
                ->send();

        } catch (Halt $exception) {

            Notification::make()
                ->success()
                ->body($exception->getMessage())
                ->send();

            DB::rollBack();
            return;
        }

        /** @internal Read the DocBlock above the following method. */
        $this->sendCreatedNotificationAndRedirect(shouldCreateAnotherInsteadOfRedirecting: $another);
    }
   protected function getSteps() :array
   {

       return [

           Forms\Components\Wizard\Step::make('Personal Details')->schema(self::personalDetails()),
           Forms\Components\Wizard\Step::make('Salary Details')->schema(self::salaryDetails()),
           Forms\Components\Wizard\Step::make('Hr Details')->schema(self::hrDetails()),
           Forms\Components\Wizard\Step::make('Contact Details')->schema(self::contactDetailsForm()),
       ];

   }

    public static function salaryDetails(): array
    {
        return [
            Forms\Components\Fieldset::make('Salary Details')->relationship('salaryDetail')->schema([
                Forms\Components\Select::make('terms_of_employment')
                    ->options(EmploymentTerms::getKeyValueOptions())
                    ->label('Employee Type')
                    ->required(),
                Forms\Components\TextInput::make('basic_salary')
                    ->required()
                    ->numeric(),

                Forms\Components\Toggle::make('has_disability')->required()
                    ->live(),
                Forms\Components\Fieldset::make('Persons With Disability')->schema([
                    Forms\Components\TextInput::make('disability_exemption_amount')
                        ->numeric()->required(fn(Forms\Get $get) => $get('has_disability')),
                    Forms\Components\TextInput::make('exemption_certificate_no')
                        ->required(fn(Forms\Get $get) => $get('has_disability'))
                        ->alphaNum()->nullable(),
                ])->visible(fn(Forms\Get $get) => $get('has_disability')),
            ])->columnSpan(1),
        ];

    }

    public static function personalDetails(): array
    {
        return [

            Forms\Components\Section::make()->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('photo'),
                    Forms\Components\TextInput::make('first_name')
                        ->required(),
                    Forms\Components\TextInput::make('middle_name'),
                    Forms\Components\TextInput::make('last_name')->nullable(),
                    Forms\Components\Select::make('gender')
                        ->options([
                            'm' => "Male",
                            'f' => "Female",
                        ])->nullable(),
                    Forms\Components\Select::make('marital_status')
                        ->options([
                            'married' => "Married",
                            'single' => "Single",
                        ])->nullable(),
                    Forms\Components\DatePicker::make('date_of_birth')
                        ->nullable(),
                    Forms\Components\Select::make('residential_status')
                        ->options([
                            'resident' => "Resident",
                            'non-resident' => "Non-Resident",
                        ])->nullable(),
                    Forms\Components\Select::make('nationality')
                        ->options([
                            'kenyan' => "Kenyan",
                            'tanzanian' => "Tanzanian",
                        ])->nullable(),
                    Forms\Components\Select::make('legal_document_type')
                        ->options([
                            'national_id' => "National ID",
                            'passport' => "Passport",
                        ])->nullable(),
                    Forms\Components\TextInput::make('legal_document_number')
                        ->nullable(),
                    Forms\Components\TextInput::make('kra_pin_no')
                        ->label('KRA PIN')
                        ->nullable(),
                    Forms\Components\TextInput::make('nssf_no')
                        ->label('NSSF NUMBER')
                        ->nullable(),
                    Forms\Components\TextInput::make('nhif_no')
                        ->label('NHIF NUMBER')
                        ->nullable(),
                    Forms\Components\Toggle::make('should_pay_payee')
                        ->required()
                        ->live(),
                ])
            ]),
        ];
    }


    public static function hrDetails(): array
    {

        return [
            Forms\Components\Grid::make(4)->schema([
                Forms\Components\Fieldset::make('Hr Details')->relationship('hrDetail')->schema([
                    Forms\Components\TextInput::make('staff_number')
                        ->numeric()
                        ->default("" . Employee::count() +1 )
                        ->readOnlyOn('edit')
                        ->required(),
                    Forms\Components\DatePicker::make('date_of_employment')
                        ->closeOnDateSelection()
                        ->required(),
                    Forms\Components\DatePicker::make('contract_start')
                        ->closeOnDateSelection()
                        ->required(),
                    Forms\Components\DatePicker::make('contract_end')
                        ->closeOnDateSelection()
                        ->required(),
                    Forms\Components\Select::make('job_grade_id')
                        ->relationship('jobGrade','title')
                        ->label('Job Grade')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->unique(table: (new JobGrade())->getTable(),ignoreRecord: true),
                        ])
                        ->required(),
                    Forms\Components\Select::make('job_title_id')
                        ->relationship('jobTitle','label')
                        ->label('Job Title')
                        ->createOptionForm([
                            TextInput::make('label')
                                ->required()
                                ->unique(table: (new JobTitle())->getTable(),ignoreRecord: true),
                        ])
                        ->required(),
                    Forms\Components\Select::make('department_id')
                        ->relationship('department','title')
                        ->label('Department')
                        ->createOptionForm([
                            TextInput::make('title')
                                ->required()
                                ->unique(table: (new Department())->getTable(),ignoreRecord: true),
                            TextInput::make('code')
                                ->required()
                                ->unique(table: (new Department())->getTable(),ignoreRecord: true),
                        ])
                        ->required(),
                    Forms\Components\Select::make('reports_to_job_title_id')
                        ->label('Reports to')
                        ->options(JobTitle::all()->pluck('label', 'id'))
                        ->required(),
                    Forms\Components\Select::make('region_id')
                        ->relationship('region','title')
                        ->label('Region')
                        ->createOptionForm([
                            TextInput::make('title')
                                ->required()
                                ->unique(table: (new Region())->getTable(),ignoreRecord: true),
                        ])
                        ->required(),
                    Forms\Components\Select::make('business_unit_id')
                        ->relationship('businessUnit','title')
                        ->label('Business Unit')
                        ->createOptionForm([
                            TextInput::make('title')
                                ->required()
                                ->unique(table: (new BusinessUnit())->getTable(),ignoreRecord: true),
                        ])
                        ->required(),
                    Forms\Components\Toggle::make('board_director')
                        ->default(false)
                        ->required(),
                ]),

            ])
        ];

    }

    public static function contactDetailsForm(): array
    {
        return [

            Forms\Components\Grid::make(2)->schema([

                Forms\Components\Fieldset::make('Contact Details')->relationship('hrContact')->schema([
                    Forms\Components\Grid::make(3)->schema([
                        TextInput::make('official_email')
                            ->email()
                            ->nullable(),
                        TextInput::make('personal_email')
                            ->email()
                            ->required(),
                        PhoneInput::make('personal_phone_number')
                            ->required(),
                        PhoneInput::make('office_phone_number')
                            ->nullable(),
                        TextInput::make('office_phone_extension')
                            ->numeric()
                            ->nullable(),
                        Select::make('country')->options([
                            'ke' => "Kenya"
                        ])->required(),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('city')->required(),
                        Forms\Components\TextInput::make('county')->required(),
                        Forms\Components\TextInput::make('postal_code')->required(),
                        Forms\Components\Textarea::make('address')->required(),
                    ]),
                    Forms\Components\Repeater::make('next_of_kin')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            TextInput::make('name'),
                            TextInput::make('relation'),
                            PhoneInput::make('phone'),
                            TextInput::make('email')->email(),
                        ])

                    ]),
                    Forms\Components\Repeater::make('social_links')->schema([
                        TextInput::make('platform'),
                        TextInput::make('url')->url(),
                    ])
                ]),


            ])
        ];
    }
}
