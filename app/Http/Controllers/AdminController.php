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
        if ($user && $user->password ===  sha1($password)) { // Ideally, use Hash::check for hashed passwords
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
    public function create() {
       
    }
    public function classlist(Request $req) {
        $result['result'] = DB::table('class')->get();

        return response()->view('admin/class/classlist', $result);
    }
        public function classupdate(Request $req) {
        extract($req->post());
        $data = [
            'class' => $class
        ];
        $result = DB::table('class')
                ->where('id', $id)
                ->update($data);
        if ($result) {
            return redirect('classlist');
        }
    }
        public function classedit($id) {
        $result['result'] = DB::table('class')
                ->where('id', $id)
                ->get();
        return view('admin/class/classedit', $result);
    }
    public function classdelete($id) {

        DB::table('class')->where('id', $id)->delete();
        return redirect('classlist');
    }
    public function classinsert(Request $req) {
        $class = $req->input('class');
        $data = [
                'class' => $class
            ];
        $result = DB::table('class')->insert($data);
        if ($result) {
            return redirect('classadd');
        } else {
            echo 'not insert';
        }
    }
    public function subjectForm() {
        $result['group'] = DB::table('groups')->get();
        return view('admin/subject/createSubject', $result);
    }

    public function createSubject(Request $req) {
        extract($_REQUEST);
        $validatedData = $req->validate([
            'subject_name' => 'required',
            'standard' => 'required'
                ], [
            'student_name.required' => 'Student name is required.',
        ]);

        $data = [
            'subject_name' => $subject_name,
            'standard' => $standard,
            'group_id' => $group_id
        ];
        $result1 = DB::table('subjects')->where('subject_name', $data['subject_name'])->first();
        ;
        if ($result1) {
            return redirect()->back()->withErrors(['error' => 'This subject already exists in same group.'])->withInput();
        } else {
            $result = DB::table('subjects')->insert($data);
            if ($result) {
                return redirect('/createSubject');
            } else {
                return redirect()->back()->withErrors(['error' => 'Something went wrong while inserting.'])->withInput();
            }
        }
    }

    public function retriveSubject() {
        $result['subject'] = DB::table('subjects')
                ->join('groups', 'subjects.group_id', '=', 'groups.id')
                ->select('subjects.*', 'groups.group_short_name')
                ->get();
        return view('admin/subject/retriveSubject', $result);
    }

    public function editSubject($id) {
        $result['group'] = DB::table('groups')->get();
        $result['subject'] = DB::table('subjects')->where('id', $id)->get();
        return view('admin/subject/editSubject', $result);
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
            return redirect('/subjectList')->with('success', 'Subject updated successfully.');
        } elseif ($result === 0) {
            // This means the update didn't affect any rows, possibly because the values are the same
            //echo json_encode(['status' => 0, 'message' => 'No rows were updated. The data might be identical.']);
            return redirect('/subjectList');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to update Subject. Please try again.']);
        }
    }

    //department Request $req
    public function createGroup(Request $req) {
        $req->validate([
            'group_name' => 'required',
            'group_short_name' => 'required'
        ]);
        extract($_REQUEST);
        $data = [
            'group_name' => $group_name,
            'group_short_name' => $group_short_name
        ];
        $result1 = DB::table('groups')->where($data)->get();
        if (count($result1) > 0) {
            return redirect()->back()->withErrors(['error' => 'This Group is already exist.'])->withInput();
            //return back()->withErrors(['error' => 'This Department is Already Exists.']);
        } else {
            $result = DB::table('groups')->insert($data);
            if ($result) {
                //echo json_encode(['status' => 1, 'message' => 'Successfully saved']);
                return redirect('/createGroup');
            } else {
                echo json_encode(['status' => 0, 'message' => 'Somthing went wrong while inserting']);
            }
        }
    }

    public function retriveGroup() {
        $result['groups'] = DB::table('groups')->get(); //->where('id',1)->get()
        return view('admin/group/retrivegroup', $result);
    }

    public function editGroup($id) {
        $result['groups'] = DB::table('groups')->where('id', $id)->get();
        return view('admin/group/editgroup', $result);
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
            return redirect('/groupList');
        } elseif ($result === 0) {
            // This means the update didn't affect any rows, possibly because the values are the same
            //echo json_encode(['status' => 0, 'message' => 'No rows were updated. The data might be identical.']);
            return redirect('/groupList');
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
        $result['group'] = DB::table('groups')->get();
        return view('admin/student/createStudent', $result);
    }

    public function createStudent(Request $req) {
        extract($_REQUEST);

        $validatedData = $req->validate([
            'register_number' => 'required|numeric',
            'student_name' => 'required|string|min:3|max:50',
            'mobile' => 'required|numeric|digits:10',
            'dob' => 'required|date|before:today',
            'academic_year' => 'required|numeric'
                ], [
            // Custom error messages
            'register_number.required' => 'Register number is required.',
            'register_number.numeric' => 'Register number must be numeric.',
            'register_number.unique' => 'This register number is already in use.',
            'student_name.required' => 'Student name is required.',
            'student_name.min' => 'Student name must be at least 3 characters.',
            'student_name.max' => 'Student name cannot exceed 255 characters.',
            'department_id.required' => 'Department is required.',
            //'department_id.exists' => 'Selected department is invalid.',
            'mobile.required' => 'Mobile number is required.',
            'mobile.numeric' => 'Mobile number must be numeric.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',
            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Invalid date format.',
            'dob.before' => 'Date of birth must be before today.',
        ]);
        $data = [
            'register_number' => $register_number,
            'name' => strtoupper($student_name),
            'father_name' => strtoupper($father_name),
            'mother_name' => strtoupper($mother_name),
            'group_id' => $group_id,
            'mobile' => $mobile,
            'previous_school' => $previous_school,
            'aadhar_number' => $aadhar_number,
            'emies_number' => $emies_number,
            'communication_address' => $communication_address,
            'dob' => $dob,
            'standard' => $standard,
            'section' => $section,
            'gender' => $gender,
            'join_date' => $joined_date,
            'email' => $student_email,
            'academic_year' => $academic_year
        ];

        $Studentexist = DB::table('students')->where('register_number', $data['register_number'])->first();
        if ($Studentexist) {
            return redirect()->back()->withErrors(['error' => 'This student already exists in same register number.'])->withInput();
        } else {
            $result = DB::table('students')->insert($data);
            if ($result) {
                return redirect('/createStudent');
            } else {
                return redirect()->back()->withErrors(['error' => 'Something went wrong while inserting.'])->withInput();
            }
        }
    }

    public function retriveStudent() {
        $result['student'] = DB::table('students')
                ->join('groups', 'students.group_id', '=', 'groups.id')
                ->select('students.*', 'groups.group_short_name')
                ->get();
        return view('admin/student/retriveStudent', $result);
    }

    public function editStudent($id) {
        $result['group'] = DB::table('groups')->get();
        $result['student'] = DB::table('students')->where('id', $id)->get();
        return view('admin/student/editStudent', $result);
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
            'joined_at.date' => 'Invalid date format.',
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
            return redirect('/studentList')->with('success', 'Student updated successfully.');
        } elseif ($result === 0) {
            // This means the update didn't affect any rows, possibly because the values are the same
            //echo json_encode(['status' => 0, 'message' => 'No rows were updated. The data might be identical.']);
            return redirect('/studentList');
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
         // Load all subjects
        $subjects = DB::table('subjects')->get();

        // Get distinct shortnames with their IDs
        $groups = DB::table('groups')->get();
        
        return view('admin/teacher/createTeacher',compact('subjects','groups'));
    }

   public function saveTeacher(Request $req)
{
    $validatedData = $req->validate([
        'teacher_name' => 'required|string|min:3|max:255',
        'experience' => 'required|string|min:1|max:50',
        'previous_work_station' => 'required|string|min:3|max:50',
        'qualification' => 'required|string|min:1|max:50',
        'designation' => 'required|string|min:1|max:50',
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
        'designation' => $req->designation,
        'join_date' => $req->join_date,
        'email' => $req->teacher_email,
        'password' => sha1($req->password), // ✅ secure hashing
    ]);

    // Insert allowed standards into subject_allotments

if (!empty($teacher_id)) {
    $class_ids      = $req->class_ids;
    $shortname_ids  = $req->shortname_ids;
    $subject_ids    = $req->subject_ids;
    $sections       = $req->sections;
    $teacher_types  = $req->teacher_types;
    $academic_years = $req->academic_years;

    for ($i = 0; $i < count($class_ids); $i++) {
        $class_id     = (int) $class_ids[$i];
        $shortname_id = $class_id > 10 ? ($shortname_ids[$i] ?? null) : null;// ✅ only if > 10
        $subject_id   = $subject_ids[$i] ?? null;
        $section      = $sections[$i] ;
        $teacher_type = $teacher_types[$i] ;
        $academic_year= $academic_years[$i];

        $assignmentData = [
            'teacher_id'    => $teacher_id,
            'standard'      => $class_id,
            'group_name_id'  => $shortname_id,
            'subject_id'    => $subject_id,
            'section'       => $section,
            'teacher_type'  => $teacher_type,
            'academic_year' => $academic_year,
        ];

        DB::table('subject_allotments')->insert($assignmentData);
    }
}


    return redirect('/createTeacher')->with('success', 'Teacher created successfully.');
}

    public function retriveTeacher() {
        $result['teacher'] = DB::table('teachers')
                ->get();
        return view('admin/teacher/retriveTeacher', $result);
    }

public function deleteTeacher($id) {
    $result = DB::table('teachers')->where('id', $id)->delete();

    if ($result) {
        return response()->json(['status' => 1, 'message' => 'Teacher deleted successfully']);
    } else {
        return response()->json(['status' => 0, 'message' => 'Failed to delete teacher']);
    }
}


    public function editTeacher($id) {
        $result['teacher'] = DB::table('teachers')->where('id', $id)->get();
        return view('admin/teacher/editTeacher', $result);
    }

    public function updateTeacher(Request $req) {
        extract($_REQUEST);

        $validatedData = $req->validate([
            'experience' => 'required|string|min:1|max:50',
            'previous_work_station' => 'required|string|min:3|max:50',
            'qualification' => 'required|string|min:1|max:50',
            'designation' => 'required|string|min:3|max:50',
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
            'designation' => $designation,
            'join_date' => $join_date,
            'email' => $teacher_email,
            

            
        ];
        $result = DB::table('teachers')->where('id', $id)->update($data);

        if ($result > 0) {
            return redirect('/teacherList')->with('success', 'Teacher updated successfully.');
        } elseif ($result === 0) {
            // This means the update didn't affect any rows, possibly because the values are the same
            //echo json_encode(['status' => 0, 'message' => 'No rows were updated. The data might be identical.']);
            return redirect('/teacherList');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to update Subject. Please try again.']);
        }
    }
    
    public function subjectAllotmentList()
{
    $allotments = DB::table('subject_allotments as sa')
        ->join('teachers as t', 'sa.teacher_id', '=', 't.id')
        ->join('subjects as s', 'sa.subject_id', '=', 's.id')
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
        ->orderBy('t.name')
        ->get();
//dd($allotments);
    return view('admin.teacher.subjectAllotmentList', compact('allotments'));
}

// Controller
public function subjectAllotmentEdit($teacherId)
{
    $teacher = DB::table('teachers')->where('id', $teacherId)->first();

    // Load existing allotments for this teacher
    $allotments = DB::table('subject_allotments')
        ->where('teacher_id', $teacherId)
        ->get();

    // Get dropdown data
    $subjects = DB::table('subjects')->get();
    $groups   = DB::table('groups')->get(); // for group_name_id

    return view('admin.teacher.subjectAllotmentEdit', compact('teacher', 'allotments', 'subjects', 'groups'));
}

public function subjectAllotmentUpdate(Request $req) {
    try {
        $teacherId = $req->teacher_id;
        $allotment_ids   = $req->allotment_ids ?? [];
        $class_ids       = $req->class_ids;
        $shortname_ids   = $req->shortname_ids ?? [];
        $subject_ids     = $req->subject_ids;
        $sections        = $req->sections;
        $teacher_types   = $req->teacher_types;
        $academic_years  = $req->academic_years;

        for ($i = 0; $i < count($class_ids); $i++) {
            $class_id = (int) $class_ids[$i];
            $shortname_id = ($class_id > 10 && isset($shortname_ids[$i])) ? $shortname_ids[$i] : null;
            $allotment_id = $allotment_ids[$i] ?? null;

            if ($allotment_id) {
                // Update by allotment ID, so group name will also be updated correctly
                DB::table('subject_allotments')
                    ->where('id', $allotment_id)
                    ->update([
                        'standard'       => $class_id,
                        'subject_id'     => $subject_ids[$i],
                        'group_name_id'  => $shortname_id,   // Correctly updated regardless of class
                        'section'        => $sections[$i],
                        'teacher_type'   => $teacher_types[$i],
                        'academic_year'  => $academic_years[$i],
                    ]);
            } else {
                // Insert new allotment
                DB::table('subject_allotments')->insert([
                    'teacher_id'     => $teacherId,
                    'standard'       => $class_id,
                    'subject_id'     => $subject_ids[$i],
                    'group_name_id'  => $shortname_id,
                    'section'        => $sections[$i],
                    'teacher_type'   => $teacher_types[$i],
                    'academic_year'  => $academic_years[$i],
                ]);
            }
        }

        if ($req->ajax() || $req->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Subject allotments updated successfully.',
                'redirect' => url('/subjectAllotmentList')
            ]);
        }

        return redirect('/subjectAllotmentList')->with('success', 'Subject allotments updated successfully.');
    } catch (\Exception $e) {
        if ($req->ajax() || $req->wantsJson()) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
        return redirect()->back()->with('error', 'An error occurred.');
    }
}

public function subjectAllotmentDelete($id)
{
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

