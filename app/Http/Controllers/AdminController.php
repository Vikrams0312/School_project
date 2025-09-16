<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {

    //  login
    public function logout(Request $request) {
        $request->session()->flush();
        return redirect('/login');
    }

    public function loginAuthentication(Request $req) {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $req->input('email');
        $password = $req->input('password');
        $where = [
            'email' => $email,
            'password' => sha1($password)
        ];
        // Retrieve the user data
        $user = DB::table('teachers')->where($where)->first();

        // Check if the user exists and verify the password
        if ($user && $user->password === sha1($password)) { // Ideally, use Hash::check for hashed passwords
            $data = [
                'user_email' => $user->email,
                'user_name' => $user->name
            ];
            $req->session()->put($data);
            return redirect('/dashboard');
        } else {
            //return back()->withErrors(['error' => 'This department is already exist.']);
            return back()->withErrors(['error' => 'Email or password is incorrect.'])->withInput();
        }
    }

    public function dashboard() {
        return view('admin/login/dashboard');
    }

    public function createDesignation() {
        return view('admin/designation/create-designation');
    }

    public function saveDesignation(Request $request) {
        $request->validate([
            'designation_name' => 'required|string|max:255|unique:designations,designation'
        ]);

        DB::table('designations')->insert([
            'designation' => $request->input('designation_name')
        ]);

        return back()->with('success', 'Designation added successfully!');
    }

    public function designationList(Request $request) {
        // Fetch designation list
        $designations = DB::table('designations')->get();
        return view('admin.designation.designation-list', compact('designations'));
    }

    public function deleteDesignation($id) {
        $deleted = DB::table('designations')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['status' => 1, 'message' => 'Success']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Failed']);
        }
    }

    public function editDesignation($id) {
        $designations = DB::table('designations')->where('id', $id)->first();
        return view('admin/designation/edit-designation', compact('designations'));
    }

    public function updateDesignation(Request $request, $id) {

        $request->validate([
            'designation_name' => 'required|string|max:255',
        ]);

        DB::table('designations')
                ->where('id', $id)
                ->update([
                    'designation' => $request->input('designation_name'),
        ]);

        return redirect('/designation-list')->with('success', 'Designation updated successfully!');
    }

    public function subjectForm() {
        // Get distinct standards with the smallest id for each
        $standards = DB::table('groups')
                ->select(DB::raw('MIN(id) as id'), 'standard')
                ->groupBy('standard')
                ->orderBy('standard')
                ->get();

        // Get distinct group short names with the smallest id for each
        $groupShortNames = DB::table('groups')
                ->select(DB::raw('MIN(id) as id'), 'group_short_name')
                ->whereNotNull('group_short_name')
                ->groupBy('group_short_name')
                ->orderBy('group_short_name')
                ->get();

        return view('admin.subject.create-subject', compact('standards', 'groupShortNames'));
    }

    public function createSubject(Request $req) {
        // Validate inputs, allow group_id to be nullable
        $validatedData = $req->validate([
            'subject_name' => 'required|string|max:255',
            'standard' => 'required|integer',
            'group_id' => 'nullable|integer'
        ]);

        // Extract data safely from the request
        $subject_name = $req->input('subject_name');
        $standard = $req->input('standard');
        $group_id = $req->input('group_id');

        // Prepare data, set group_id to null if empty
        $data = [
            'subject_name' => $subject_name,
            'standard' => $standard,
            'group_id' => !empty($group_id) ? $group_id : null
        ];

        // Check if the subject already exists in the same group
        $result1 = DB::table('subjects')
                ->where('standard', $data['standard'])
                ->where('subject_name', $data['subject_name'])
                ->where('group_id', $data['group_id'])
                ->first();

        if ($result1) {
            return redirect()->back()
                            ->withErrors(['error' => 'This subject already exists in the same group.'])
                            ->withInput();
        } else {
            $result = DB::table('subjects')->insert($data);

            if ($result) {
                return redirect('/create-subject')->with('success', 'Subject created successfully.');
            } else {
                return redirect()->back()
                                ->withErrors(['error' => 'Something went wrong while inserting.'])
                                ->withInput();
            }
        }
    }

    public function retriveSubject() {
        $result['subject'] = DB::table('subjects')
                ->leftJoin('groups', 'subjects.group_id', '=', 'groups.id')
                ->select('subjects.*', 'groups.group_short_name')
                ->orderBy('standard')
                ->get();

        return view('admin.subject.retrive-subject', $result);
    }

    public function editSubject($id) {
        $result['group'] = DB::table('groups')->get();
        $result['subject'] = DB::table('subjects')->where('id', $id)->get();
        return view('admin/subject/edit-subject', $result);
    }

    public function deleteSubject($id) {
        $result = DB::table('subjects')->where('id', $id)->delete();
        if ($result) {
            echo json_encode(['status' => 1, 'message' => 'Success']);
        } else {
            echo json_encode(['status' => 0, 'message' => 'failed']);
        }
    }

    public function updateSubject(Request $req) {
        extract($_REQUEST);
        $validatedData = $req->validate([
            'subject_name' => 'required',
            'standard' => 'required'
                ], [
            'student_name.required' => 'Student name is required.',
        ]);

        $id = $req->input('id');
        $data = [
            'subject_name' => $subject_name,
            'standard' => $standard,
            'group_id' => $group_id
        ];
        $result = DB::table('subjects')->where('id', $id)->update($data);

        if ($result > 0) {
            return redirect('/subject-list')->with('success', 'Subject updated successfully.');
        } elseif ($result === 0) {
            // This means the update didn't affect any rows, possibly because the values are the same
            //echo json_encode(['status' => 0, 'message' => 'No rows were updated. The data might be identical.']);
            return redirect('/subject-list');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to update Subject. Please try again.']);
        }
    }

    //department Request $req
    public function createGroup(Request $req) {
        // Validate standard first
        $req->validate([
            'standard' => 'required|integer|min:1|max:12'
        ]);

        $standard = $req->standard;

        // Conditional validation
        if ($standard == 11 || $standard == 12) {
            $req->validate([
                'group_name' => 'required|string|max:255',
                'group_short_name' => 'required|string|max:50'
            ]);
            $data = [
                'standard' => $standard,
                'group_name' => $req->group_name,
                'group_short_name' => $req->group_short_name
            ];
        } else {
            // For standards 1 to 10, group_name and group_short_name can be null or empty
            $data = [
                'standard' => $standard,
                'group_name' => null,
                'group_short_name' => null
            ];
        }

        // Check if group already exists for this standard
        $result1 = DB::table('groups')->where([
                    'standard' => $data['standard'],
                    'group_name' => $data['group_name'],
                    'group_short_name' => $data['group_short_name']
                ])->get();

        if (count($result1) > 0) {
            return redirect()->back()->withErrors(['error' => 'This Group already exists.'])->withInput();
        } else {
            $result = DB::table('groups')->insert($data);
            if ($result) {
                return redirect('/create-group')->with('success', 'Group created successfully.');
            } else {
                return redirect()->back()->withErrors(['error' => 'Something went wrong while inserting'])->withInput();
            }
        }
    }

    public function retriveGroup() {
        $result['groups'] = DB::table('groups')
                ->orderBy('standard')
                ->get(); //->where('id',1)->get()
        return view('admin/group/retrive-group', $result);
    }

    public function editGroup($id) {
        $result['groups'] = DB::table('groups')->where('id', $id)->get();
        return view('admin/group/edit-group', $result);
    }

    public function updateGroup(Request $req) {
        extract($_REQUEST);
        $id = $req->input('id');
        $data = [
            'group_name' => $group_name,
            'group_short_name' => $group_short_name
        ];
        $result = DB::table('groups')->where('id', $id)->update($data);
        if ($result > 0) {
            return redirect('/group-list');
        } elseif ($result === 0) {
            // This means the update didn't affect any rows, possibly because the values are the same
            //echo json_encode(['status' => 0, 'message' => 'No rows were updated. The data might be identical.']);
            return redirect('/group-list');
        } else {
            echo json_encode(['status' => 0, 'message' => 'failed']);
        }
    }

    public function deleteGroup($id) {
        $result = DB::table('groups')->where('id', $id)->delete();
        if ($result) {
            echo json_encode(['status' => 1, 'message' => 'Success']);
        } else {
            echo json_encode(['status' => 0, 'message' => 'failed']);
        }
    }

    //studentform
    public function studentForm() {
        // Get distinct standards with the smallest id for each
        $standards = DB::table('groups')
                ->select(DB::raw('MIN(id) as id'), 'standard')
                ->groupBy('standard')
                ->orderBy('standard')
                ->get();

        // Get distinct group short names with the smallest id for each
        $groupShortNames = DB::table('groups')
                ->select(DB::raw('MIN(id) as id'), 'group_short_name')
                ->whereNotNull('group_short_name')
                ->groupBy('group_short_name')
                ->orderBy('group_short_name')
                ->get();

        return view('admin/student/create-student', compact('standards', 'groupShortNames'));
    }

    public function createStudent(Request $req) {
        $validatedData = $req->validate([
            'register_number' => 'required|numeric',
            'student_name' => 'required|string|min:3|max:50',
            'mobile' => 'required|numeric|digits:10',
            'dob' => 'required|date|before:today',
            'academic_year' => 'required|numeric',
            'gender' => 'required|string'
                ], [
            'register_number.required' => 'Register number is required.',
            'register_number.numeric' => 'Register number must be numeric.',
            'student_name.required' => 'Student name is required.',
            'student_name.min' => 'Student name must be at least 3 characters.',
            'student_name.max' => 'Student name cannot exceed 50 characters.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.numeric' => 'Mobile number must be numeric.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',
            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Invalid date format.',
            'dob.before' => 'Date of birth must be before today.',
            'academic_year.required' => 'Academic year is required.',
            'academic_year.numeric' => 'Academic year must be numeric.',
            'gender.required' => 'Gender is required.'
        ]);

        $data = [
            'register_number' => $req->input('register_number'),
            'name' => strtoupper($req->input('student_name')),
            'father_name' => strtoupper($req->input('father_name')),
            'mother_name' => strtoupper($req->input('mother_name')),
            'group_id' => $req->input('group_id'),
            'mobile' => $req->input('mobile'),
            'previous_school' => $req->input('previous_school'),
            'aadhar_number' => $req->input('aadhar_number'),
            'emies_number' => $req->input('emies_number'),
            'communication_address' => $req->input('communication_address'),
            'dob' => $req->input('dob'),
            'standard' => $req->input('standard'),
            'section' => $req->input('section'),
            'gender' => $req->input('gender'),
            'join_date' => $req->input('joined_date'),
            'email' => $req->input('student_email'),
            'academic_year' => $req->input('academic_year')
        ];

        $Studentexist = DB::table('students')->where('register_number', $data['register_number'])->first();
        if ($Studentexist) {
            return redirect()->back()->withErrors(['error' => 'This student already exists with the same register number.'])->withInput();
        } else {
            $result = DB::table('students')->insert($data);
            if ($result) {
                return redirect('/create-student');
            } else {
                return redirect()->back()->withErrors(['error' => 'Something went wrong while inserting.'])->withInput();
            }
        }
    }

    public function retriveStudent() {
        $result['student'] = DB::table('students')
                ->leftJoin('groups', 'students.group_id', '=', 'groups.id')
                ->select('students.*', 'groups.group_short_name')
                ->get();

        //dd($result);
        return view('admin/student/retrive-student', $result);
    }

    public function editStudent($id) {
        $result['group'] = DB::table('groups')->get();
        $result['student'] = DB::table('students')->where('id', $id)->get();
        return view('admin/student/edit-student', $result);
    }

    public function updateStudent(Request $req) {
        $validatedData = $req->validate([
            'register_number' => 'required|numeric',
            'student_name' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits:10',
            'dob' => 'required|date|before:today',
            'joined_at' => 'date|before:today',
            'student_email' => 'required|email', // Added email validation
                ], [
            'register_number.required' => 'Register number is required.',
            'register_number.numeric' => 'Register number must be numeric.',
            'register_number.unique' => 'This register number is already in use.',
            'student_name.required' => 'Student name is required.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.numeric' => 'Mobile number must be numeric.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',
            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Invalid date format.',
            'dob.before' => 'Date of birth must be before today.',
            'joined_at.' => 'Invalid date format.',
            'joined_at.before' => 'Date of joining must be before today.',
            'student_email.required' => 'Email is required.',
            'student_email.email' => 'Invalid email format.'
        ]);

        $id = $req->input('id');

        \Log::info('Updating student with ID: ' . $id, $req->all());

        $existingStudent = DB::table('students')
                ->where('register_number', $req->input('register_number'))
                ->where('id', '!=', $id)
                ->first();

        if ($existingStudent) {
            return redirect()->back()->withErrors(['error' => 'This register number is already in use by another student.'])->withInput();
        }

        $data = [
            'register_number' => $req->input('register_number'),
            'name' => strtoupper($req->input('student_name')),
            'father_name' => strtoupper($req->input('father_name')),
            'mother_name' => strtoupper($req->input('mother_name')),
            'group_id' => $req->input('group_id'),
            'mobile' => $req->input('mobile'),
            'previous_school' => $req->input('previous_school'),
            'aadhar_number' => $req->input('aadhar_number'),
            'emies_number' => $req->input('emies_number'),
            'communication_address' => $req->input('communication_address'),
            'dob' => $req->input('dob'),
            'standard' => $req->input('standard'),
            'section' => $req->input('section'),
            'gender' => $req->input('gender'),
            'join_date' => $req->input('join_date'), // Use joined_at from request
            'email' => $req->input('student_email'),
            'academic_year' => $req->input('academic_year')
        ];

        $result = DB::table('students')->where('id', $id)->update($data);

        //print_r($result);

        if ($result > 0) {
            return redirect('/student-list')->with('success', 'Student updated successfully.');
        } elseif ($result === 0) {
            // This means the update didn't affect any rows, possibly because the values are the same
            //echo json_encode(['status' => 0, 'message' => 'No rows were updated. The data might be identical.']);
            return redirect('/student-list');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to update Subject. Please try again.']);
        }
    }

    public function deleteStudent($id) {
        $result = DB::table('students')->where('id', $id)->delete();
        if ($result) {
            echo json_encode(['status' => 1, 'message' => 'Success']);
        } else {
            echo json_encode(['status' => 0, 'message' => 'failed']);
        }
    }

    //teacher
    public function createTeacher() {
        $designations = DB::table('designations')->get();
        return view('admin.teacher.create-teacher', ['designations' => $designations]);
    }

    public function saveTeacher(Request $req) {
        $validatedData = $req->validate([
            'teacher_name' => 'required|string|min:3|max:255',
            'experience' => 'required|string|min:1|max:50',
            'previous_work_station' => 'required|string|min:3|max:50',
            'qualification' => 'required|string|min:1|max:50',
            'designation' => 'required|int|min:1|max:50',
            'mobile' => 'required|numeric|digits:10',
            'password' => 'required|string|min:6',
            'join_date' => 'required|date',
            'teacher_email' => 'required|email'
        ]);

        // Insert into teachers table
        $teacher_id = DB::table('teachers')->insertGetId([
            'name' => strtoupper($req->teacher_name),
            'mobile' => $req->mobile,
            'experience' => $req->experience,
            'previous_work_station' => $req->previous_work_station,
            'qualification' => $req->qualification,
            'designation_id' => $req->designation,
            'join_date' => $req->join_date,
            'email' => $req->teacher_email,
            'password' => sha1($req->password), // ✅ secure hashing
        ]);

        return redirect('/create-teacher')->with('success', 'Teacher created successfully.');
    }

    public function retriveTeacher(Request $request) {
        $designation_id = $request->input('designation');

        $query = DB::table('teachers')
                ->leftJoin('designations', 'teachers.designation_id', '=', 'designations.id')
                ->select('teachers.*', 'designations.designation as designation_name');

        if ($designation_id) {
            $query->where('teachers.designation_id', $designation_id);
        }

        $teacher = $query->get();
        $designations = DB::table('designations')->get();

        return view('admin.teacher.retrive-teacher', compact('teacher', 'designations'));
    }

    public function deleteTeacher($id) {
        $result1 = DB::table('teachers')->where('id', $id)->delete();
        $result2 = DB::table('subject_allotments')->where('teacher_id', $id)->delete();
        if ($result1 && $result2) {
            return response()->json(['status' => 1, 'message' => 'Teacher deleted successfully']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Failed to delete teacher']);
        }
    }

    public function editTeacher($id) {
        $teacher = DB::table('teachers')->where('id', $id)->first();
        $designations = DB::table('designations')->get();
        return view('admin.teacher.edit-teacher', compact('teacher', 'designations'));
    }

    public function updateTeacher(Request $req) {
        extract($_REQUEST);

        $validatedData = $req->validate([
            'experience' => 'required|string|min:1|max:50',
            'previous_work_station' => 'required|string|min:3|max:50',
            'qualification' => 'required|string|min:1|max:50',
            'designation' => 'required|integer',
            'mobile' => 'required|numeric|digits:10'
                ], [
            // Custom error messages
            'name.min' => 'Teacher name must be at least 3 characters.',
            'name.max' => 'Teacher name cannot exceed 255 characters.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.numeric' => 'Mobile number must be numeric.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',
            'experience.required' => 'experience name is required.',
        ]);
        $id = $req->input('id');
        $data = [
            'name' => strtoupper($teacher_name),
            'mobile' => $mobile,
            'experience' => $experience,
            'previous_work_station' => $previous_work_station,
            'qualification' => $qualification,
            'designation_id' => $designation,
            'join_date' => $join_date,
            'email' => $teacher_email,
        ];
        $result = DB::table('teachers')->where('id', $id)->update($data);

        if ($result > 0) {
            return redirect('/teacher-list')->with('success', 'Teacher updated successfully.');
        } elseif ($result === 0) {
            // This means the update didn't affect any rows, possibly because the values are the same
            //echo json_encode(['status' => 0, 'message' => 'No rows were updated. The data might be identical.']);
            return redirect('/teacher-list');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to update Subject. Please try again.']);
        }
    }

    public function subjectAllotment() {
        // Load all subjects
        $subjects = DB::table('subjects')
                ->get();
        $groups = DB::table('groups')
                ->select('id', 'group_short_name')
                ->where('group_short_name', '!=', '')
                ->distinct()
                ->orderBy('group_short_name')
                ->get();
        // Get distinct shortnames with their IDs
        $classes = DB::table('groups')
                ->select('standard')
                ->distinct()
                ->orderBy('standard')
                ->get();
        $teachers = DB::table('teachers')->get();
        return view('admin.teacher.subject-allotment', compact('subjects', 'groups', 'classes', 'teachers'));
    }

    public function saveSubjectAllotments(Request $req) {
        $validatedData = $req->validate([
            'class_ids' => 'required|array',
            'class_ids.*' => 'required',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'required',
            'sections' => 'required|array',
            'sections.*' => 'required',
            'teacher_types' => 'required|array',
            'teacher_types.*' => 'required',
            'academic_years' => 'required|array',
            'academic_years.*' => 'required',
        ]);
        $teacher_id = $req->teacher_id;
        $class_ids = $req->class_ids;
        $shortname_ids = $req->shortname_ids;
        $subject_ids = $req->subject_ids;
        $sections = $req->sections;
        $teacher_types = $req->teacher_types;
        $academic_years = $req->academic_years;

        for ($i = 0; $i < count($class_ids); $i++) {
            $class_id = (int) $class_ids[$i];
            $shortname_id = $class_id > 10 ? ($shortname_ids[$i] ?? null) : null;
            $subject_id = $subject_ids[$i] ?? null;
            $section = $sections[$i];
            $teacher_type = $teacher_types[$i];
            $academic_year = $academic_years[$i];

            $assignmentData = [
                'teacher_id' => $teacher_id,
                'standard' => $class_id,
                'group_name_id' => $shortname_id,
                'subject_id' => $subject_id,
                'section' => $section,
                'teacher_type' => $teacher_type,
                'academic_year' => $academic_year,
            ];

            DB::table('subject_allotments')->insert($assignmentData);
        }

        return redirect('/teacher-list')->with('success', 'Subject allotments saved successfully.');
    }

    public function subjectAllotmentList($teacher_id) {
        $allotments = DB::table('subject_allotments as sa')
                ->join('teachers as t', 'sa.teacher_id', '=', 't.id')
                ->leftJoin('subjects as s', 'sa.subject_id', '=', 's.id') // join by subject_id
                ->leftJoin('groups as g', 'sa.group_name_id', '=', 'g.id')
                ->select(
                        'sa.id',
                        'sa.teacher_id',
                        't.name as teacher_name',
                        'sa.standard',
                        's.subject_name',
                        'g.group_short_name',
                        'sa.section',
                        'sa.teacher_type',
                        'sa.academic_year'
                )
                ->when($teacher_id, function ($query) use ($teacher_id) {
                    $query->where('sa.teacher_id', $teacher_id);
                })
                ->orderBy('t.name')
                ->get();

        // Debug
        // dd($allotments);

        return view('admin.teacher.list-subject-allotment', compact('allotments'));
    }

// Controller
    // Controller
    public function subjectAllotmentEdit($teacherId) {
        // Get the teacher's details
        $teacher = DB::table('teachers')->where('id', $teacherId)->first();

        // Load existing allotments for this teacher
        $allotments = DB::table('subject_allotments')
                ->where('teacher_id', $teacherId)
                ->get();

        // Get list of all subjects
        $subjects = DB::table('subjects')->get();

        // Get distinct classes (standards) for the Class dropdown
        $class_list = DB::table('groups')
                ->select('standard')
                ->distinct()
                ->orderBy('standard')
                ->get();

        // Get list of groups for the Group Name dropdown
        $groups = DB::table('groups')
                ->select('id', 'group_short_name')
                ->where('group_short_name', '!=', '')
                ->orderBy('group_short_name')
                ->get();

        return view('admin.teacher.edit-subject-allotment', compact('teacher', 'allotments', 'subjects', 'groups', 'class_list'));
    }

    public function subjectAllotmentUpdate(Request $req) {

        try {
            $teacherId = $req->teacher_id;
            $allotment_ids = $req->allotment_ids ?? [];
            $class_ids = $req->class_ids;
            $shortname_ids = $req->shortname_ids ?? [];
            $subject_ids = $req->subject_ids;
            $sections = $req->sections;
            $teacher_types = $req->teacher_types;
            $academic_years = $req->academic_years;

            for ($i = 0; $i < count($class_ids); $i++) {
                $class_id = (int) $class_ids[$i];
                $shortname_id = null;
                if ($class_id == 11 || $class_id == 12) {
                    $shortname_id = isset($shortname_ids[$i]) && $shortname_ids[$i] != '' ? $shortname_ids[$i] : null;
                }

                $allotment_id = $allotment_ids[$i] ?? null;

                if ($allotment_id) {
                    $data = [
                        'standard' => $class_id,
                        'subject_id' => $subject_ids[$i],
                        'group_name_id' => $shortname_id, // Correctly updated regardless of class
                        'section' => $sections[$i],
                        'teacher_type' => $teacher_types[$i],
                        'academic_year' => $academic_years[$i],
                        'academic_year' => $academic_years[$i],
                    ];

                    // Update by allotment ID, so group name will also be updated correctly

                    DB::table('subject_allotments')
                            ->where('id', $allotment_id)
                            ->update($data);
                } else {
                    // Insert new allotment
                    DB::table('subject_allotments')->insert([
                        'teacher_id' => $teacherId,
                        'standard' => $class_id,
                        'subject_id' => $subject_ids[$i],
                        'group_name_id' => $shortname_id,
                        'section' => $sections[$i],
                        'teacher_type' => $teacher_types[$i],
                        'academic_year' => $academic_years[$i],
                    ]);
                }
            }

            if ($req->ajax() || $req->wantsJson()) {
                return response()->json([
                            'status' => 'success',
                            'message' => 'Subject allotments updated successfully.',
                            'redirect' => url("/subject-allotment-list/{$teacherId}")
                ]);
            }

            return redirect("/subject-allotment-list/{$teacherId}")->with('success', 'Subject allotments updated successfully.');
        } catch (\Exception $e) {
            if ($req->ajax() || $req->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    public function subjectAllotmentDelete($id) {
        try {
            DB::table('subject_allotments')->where('id', $id)->delete();

            return response()->json([
                        'status' => 'success',
                        'message' => 'Allotment deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                        'status' => 'error',
                        'message' => 'An error occurred: ' . $e->getMessage()
                            ], 500);
        }
    }

}
