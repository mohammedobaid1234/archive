<?php

namespace Modules\Employees\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmployeesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات الموظفين";
    private $model = \Modules\Employees\Entities\Employee::class;

    public function manage()
    {

        \Auth::user()->authorize('employees_module_employees_manage', 'view');

        $data['activePage'] = ['employees' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('employees::employees', $data);
    }

    public function datatable(Request $request)
    {
        \Auth::user()->authorize('employees_module_employees_manage');

        $eloquent = $this->model::with(['user.roles', 'profile','department']);

        if ((int) $request->filters_status) {
            if (trim($request->full_name) !== "") {
                $eloquent->whereFullNameLike($request->full_name);
            }
            if (trim($request->employment_id) !== "") {
                $eloquent->where('employment_id', "LIKE", '%' . $request->employment_id . '%');
            }
            if (trim($request->roles) !== "") {
                $eloquent->whereHas('user', function ($query) use ($request) {
                    foreach (explode(',', trim($request->roles)) as $role) {
                        $query->role($role);
                    }
                });
            }
        }

        $filters = [
            ['title' => 'الرقم الوظيفي', 'type' => 'input', 'name' => 'employment_id'],
            ['title' => 'الاسم', 'type' => 'input', 'name' => 'full_name'],
            ['title' => 'الدور / المسمى الوظيفي', 'type' => 'select', 'name' => 'roles', 'multiple' => true, 'data' => ['options_source' => 'roles']],
            ['title' => 'بدء العمل', 'type' => 'input', 'started_work' => 'started_work', 'date_range' => true]
        ];

        $columns = [
            ['title' => 'الرقم الوظيفي', 'column' => 'employment_id'],
            ['title' => 'رقم الهوية', 'column' => 'profile.national_id'],
            ['title' => 'الاسم', 'column' => 'full_name'],
            ['title' => 'العنوان ', 'column' => 'profile.address'],
            ['title' => 'القسم ', 'column' => 'department.label'],
            ['title' => 'المسمى الوظيفي / الأدوار', 'column' => 'user.roles.name', 'merge' => true, 'formatter' => 'roles'],
            ['title' => 'رقم الجوال', 'column' => 'mobile_no'],
            ['title' => 'الراتب ', 'column' => 'salary'],
            ['title' => 'رقم البصمة ', 'column' => 'profile.fingerprint_number'],
            ['title' => 'تاريخ بدء العمل', 'column' => 'profile.started_work'],
            ['title' => 'تاريخ الميلاد ', 'column' => 'birthdate'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function create()
    {
        return [
            "title" => "اضافة موظف جديد",
            "inputs" => [
                [
                    'title' => 'الرقم الوظيفي',
                    'input' => 'input',
                    'name' => 'employment_id',
                    'required' => true,
                    'operations' => [
                        'show' => ['text' => 'employment_id']
                    ]
                ],
               
                [
                    [
                        'title' => 'الاسم الاول',
                        'input' => 'input',
                        'name' => 'first_name',
                        'required' => true,
                        'operations' => [
                            'show' => ['text' => 'first_name']
                        ]
                    ],
                    [
                        'title' => 'الاسم الثاني',
                        'input' => 'input',
                        'name' => 'father_name',
                        'required' => true,
                        'operations' => [
                            'show' => ['text' => 'father_name']
                        ]
                    ]
                ],
                [
                    [
                        'title' => 'إسم الجد',
                        'input' => 'input',
                        'name' => 'grandfather_name',
                        'required' => true,
                        'operations' => [
                            'show' => ['text' => 'grandfather_name']
                        ]
                    ],
                    [
                        'title' => 'إسم العائلة',
                        'input' => 'input',
                        'name' => 'last_name',
                        'required' => true,
                        'operations' => [
                            'show' => ['text' => 'last_name']
                        ]
                    ]
                ],
               [
                [
                    'title' => 'رقم الجوال',
                    'input' => 'input',
                    'name' => 'mobile_no',
                    'maxlength' => 10,
                    'operations' => [
                        'show' => ['text' => 'mobile_no']
                    ]
                ],
                [
                    'title' => 'رقم البصمة',
                    'input' => 'input',
                    'name' => 'fingerprint_number',
                    'maxlength' => 10,
                    'operations' => [
                        'show' => ['text' => 'profile.fingerprint_number']
                    ]
                ],
               ],
               [
                    'title' => ' العنوان',
                    'input' => 'input',
                    'name' => 'address',
                    'maxlength' => 10,
                    'operations' => [
                        'show' => ['text' => 'profile.address']
                    ]
               ],
                [
                    'title' => 'رقم الهوية',
                    'input' => 'input',
                    'name' => 'national_id',
                    'maxlength' => 10,
                    'operations' => [
                        'show' => ['text' => 'profile.national_id']
                    ]
                ],
                [
                    [
                        'title' => 'تاريخ الميلاد ',
                        'input' => 'input',
                        'name' => 'birthdate',
                        'classes' => ['numeric'], 
                        'date' => true,
                        'operations' => ['show' => ['text' => 'birthdate']]
                    ],
                    [
                        'title' => 'تاريخ بداية العمل ',
                        'input' => 'input',
                        'name' => 'started_work',
                        'classes' => ['numeric'], 
                        'date' => true,
                        'operations' => ['show' => ['text' => 'profile.started_work']]
                    ],
                ],
                [
                    [

                        'title' => 'الجنس',
                        'input' => 'select',
                        'name' => 'gender',
                        'required' => true,
                        'classes' => ['select2'],
                        'data' => [
                            'options_source' => 'gender'
                        ],
                        'operations' => [
                            'show' => ['text' => 'gender']
                        ]
                    ],
                    [

                        'title' => 'القسم',
                        'input' => 'select',
                        'name' => 'department_id',
                        'required' => true,
                        'classes' => ['select2'],
                        'data' => [
                            'options_source' => 'departments'
                        ],
                        'operations' => [
                            'show' => ['text' => 'department.label', 'id' => 'department_id']

                        ]
                    ],
                    [
                        'title' => 'الراتب ',
                        'input' => 'input',
                        'name' => 'salary',
                        'maxlength' => 10,
                        'operations' => [
                            'show' => ['text' => 'salary']
                        ]
                    ],
                    [
                        'title' => 'الأدوار',
                        'input' => 'select',
                        'name' => 'roles',
                        'classes' => ['select2'],
                        'required' => true,
                        'multiple' => true,
                        'data' => [
                            'options_source' => 'employees_roles'
                        ],
                        'operations' => [
                            'show' => ['text' => 'user.roles.label', 'id' => 'user.roles.name']

                        ]
                    ],
                ],

                [
                    'title' => 'كلمة المرور',
                    'input' => 'input',
                    'name' => 'password',
                    'required' => true,
                    'operations' => [
                        'show' => ['active' => false],
                        'update' => ['active' => false]
                    ]
                ],
                [
                    'title' => 'الصورة الشخصية',
                    'input' => 'input',
                    'type' => 'file',
                    'name' => 'image',
                    'operations' => [
                        'show' => ['text' => 'image'],
                        'update' => ['text' => 'image'],
                    ]
                ]

            ],
        ];
    }
    public function store(Request $request)
    {
        \Auth::user()->authorize('employees_module_employees_store');

        $request->validate([
            'employment_id' => 'required',
            'roles' => 'required',
            'first_name' => 'required',
            'father_name' => 'required',
            'grandfather_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'salary' => 'required',
            'mobile_no' => 'required',
            'started_work' => 'required',
            'department_id' => 'required',
        ]);

        if (\Modules\Employees\Entities\Employee::where('employment_id', trim($request->employment_id))->count()) {
            return response()->json(['message' => "لا يمكن تكرار الرقم الوظيفي"], 403);
        }


        if (trim($request->mobile_no) !== "") {
            if (strlen(trim($request->mobile_no)) !== 10) {
                return response()->json(['message' => "يرجى التحقق من صحة رقم الجوال."], 403);
            }

            if (\Modules\Employees\Entities\Employee::where('mobile_no', trim($request->mobile_no))->count()) {
                return response()->json(['message' => "لا يمكن تكرار رقم الجوال"], 403);
            }
        }

        \DB::beginTransaction();
        try {
            $employee = new $this->model;
            $employee->employment_id = trim($request->employment_id);
            $employee->first_name = $request->first_name;
            $employee->father_name = $request->father_name;
            $employee->grandfather_name = $request->grandfather_name;
            $employee->last_name = $request->last_name;
            $employee->full_name = $request->first_name . " " .$request->father_name . " " .$request->grandfather_name. " " . $request->last_name;
            $employee->gender = $request->gender;
            $employee->salary = $request->salary;
            $employee->birthdate = $request->birthdate;
            $employee->department_id = $request->department_id;
            $employee->mobile_no = (trim($request->mobile_no) !== "" ? trim($request->mobile_no) : NULL);
            $employee->created_by = \Auth::user()->id;
            $employee->save();

            $profile = new \Modules\Employees\Entities\Profile;
            $profile->started_work = $request->started_work;
            $profile->employee_id  = $employee->id;
            $profile->save();

            $user = new \Modules\Users\Entities\User;
            $user->userable_id = $employee->id;
            $user->userable_type = "Modules\Employees\Entities\Employee";
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);


            $user->email = $employee->mobile_no;
            $user->save();

            $user->syncRoles(explode(',', str_replace(" ", "", $request->roles)));

            if ($request->hasFile('image') && $request->file('image')[0]->isValid()) {
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "personal-image";

                $employee->addMediaFromRequest('image[0]')
                    ->usingFileName($media_new_name)
                    ->usingName($request->file('image')[0]->getClientOriginalName())
                    ->toMediaCollection($collection);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }

    public function show($id)
    {
        return $this->model::with(['user.roles', 'profile','department'])->whereId($id)->first();
    }

    public function update(Request $request, $employee_id)
    {
        \Auth::user()->authorize('employees_module_employees_update');

        $request->validate([
            'employment_id' => 'required',
            'roles' => 'required',
            'first_name' => 'required',
            'father_name' => 'required',
            'grandfather_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'salary' => 'required',
            'mobile_no' => 'required',
            'started_work' => 'required',
            'department_id' => 'required',
        ]);

        if (\Modules\Employees\Entities\Employee::where('id', '<>', $employee_id)->where('employment_id', trim($request->employment_id))->count()) {
            return response()->json(['message' => "لا يمكن تكرار الرقم الوظيفي"], 403);
        }

        if (trim($request->mobile_no) !== "") {
            if (strlen(trim($request->mobile_no)) !== 10) {
                return response()->json(['message' => "يرجى التحقق من صحة رقم الجوال."], 403);
            }

            if (\Modules\Employees\Entities\Employee::where('id', '<>', $employee_id)->where('mobile_no', trim($request->mobile_no))->count()) {
                return response()->json(['message' => "لا يمكن تكرار رقم الجوال"], 403);
            }
        }

        \DB::beginTransaction();
        try {
            $employee = $this->model::whereId($employee_id)->first();
            $employee->employment_id = trim($request->employment_id);
            $employee->first_name = $request->first_name;
            $employee->father_name = $request->father_name;
            $employee->grandfather_name = $request->grandfather_name;
            $employee->last_name = $request->last_name;
            $employee->gender = $request->gender;
            $employee->salary = $request->salary;
            $employee->birthdate = $request->birthdate;
            $employee->department_id = $request->department_id;
            $employee->mobile_no = (trim($request->mobile_no) !== "" ? trim($request->mobile_no) : NULL);
            $employee->created_by = \Auth::user()->id;
            $employee->save();

            $profile = \Modules\Employees\Entities\Profile::where('employee_id', $employee_id)->first();
            $profile->started_work = $request->started_work;
            $profile->national_id = $request->national_id;
            $profile->fingerprint_number = $request->fingerprint_number;
            $profile->address = $request->address;
            $profile->save();

            $user = \Modules\Users\Entities\User::whereId($employee->user->id)->first();
            $user->email = $employee->mobile_no;
            $user->save();

            $user->syncRoles(explode(',', str_replace(" ", "", $request->roles)));

            if ($request->hasFile('image') && $request->file('image')[0]->isValid()) {
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "personal-image";

                $employee->clearMediaCollection($collection);

                $employee->addMediaFromRequest('image[0]')
                    ->usingFileName($media_new_name)
                    ->usingName($request->file('image')[0]->getClientOriginalName())
                    ->toMediaCollection($collection);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }

    public function reset_password($id)
    {
        \Auth::user()->authorize('employees_module_employees_reset_password');

        $employee = \Modules\Employees\Entities\Employee::whereId($id)->select(['id', 'mobile_no', 'employment_id'])->first();

        if (!$employee->mobile_no) {
            return response()->json(['message' => "لا يملك الموظف رقم جوال لارسال كلمة مرور البوابة الجديدة."], 403);
        }

        if (!$employee->user) {
            return response()->json(['message' => "لا يملك الموظف حساب للوصول إلى البوابة."], 403);
        }

        $password = rand(1000000, 9999999);

        $user = $employee->user;
        $user->password = \Illuminate\Support\Facades\Hash::make($password);
        $user->save();

        /**
         * SEND THE PASSWORD
         */
        $url = url('/');

        $sms = new \Modules\SMS\Entities\SMS;
        $sms->smsable_id = $user->id;
        $sms->smsable_type = "Modules\Users\Entities\User";
        $sms->mobile_no    = $employee->mobile_no;
        $sms->message = "رابط البوابة: " . $url . "\nالرقم الوظيفي: $employee->employment_id\nكلمة المرور: $password";
        $sms->save();
        // $sms->send();

        // (new \Modules\Users\Http\Controllers\SessionsController)->destroy($user->id);

        return response()->json(['message' => 'ok']);
    }
}
