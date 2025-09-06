<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


Route::view('/myform','admin/payment/myform');
Route::view('/', 'admin/login/login');
Route::view('/login', 'admin/login/login')->name('AdminLogin');
Route::get('/logout', [AdminController::class, 'logout']);
Route::post('/loginauth', [AdminController::class, 'loginAuthentication']);
Route::get('/dashboard', [AdminController::class, 'dashboard']);

Route::get('/createTeacher', [AdminController::class, 'create']);

Route::view('/classadd', 'admin/class/classadd');
Route::get('/classlist', [AdminController::class, 'classlist']);
Route::get('/classdelete/{id}', [AdminController::class, 'classdelete']);
Route::post('/classinsert', [AdminController::class, 'classinsert']);
Route::get('/classedit/{id}', [AdminController::class, 'classedit']); 
Route::post('/classupdate', [AdminController::class, 'classupdate']);

Route::view('/createGroup', 'admin/group/creategroup');
Route::post('/saveGroup', [AdminController::class, 'createGroup']);
Route::post('/updateGroup', [AdminController::class, 'updateGroup']);
Route::get('/groupList', [AdminController::class, 'retriveGroup']);
Route::get('/editGroup/{id}', [AdminController::class, 'editGroup']);
Route::get('/deleteGroup/{id}', [AdminController::class, 'deleteGroup']);

Route::get('/createStudent', [AdminController::class, 'studentForm']);
Route::post('/saveStudent', [AdminController::class, 'createStudent']);
Route::get('/studentList', [AdminController::class, 'retriveStudent']);
Route::get('/editStudent/{id}', [AdminController::class, 'editStudent']);
Route::post('/updateStudent', [AdminController::class, 'updateStudent']);
Route::get('/deleteStudent/{id}', [AdminController::class, 'deleteStudent']);

Route::get('/createSubject', [AdminController::class, 'subjectForm']);
Route::post('/saveSubject', [AdminController::class, 'createSubject']);
Route::get('/subjectList', [AdminController::class, 'retriveSubject']);
Route::get('/editSubject/{id}', [AdminController::class, 'editSubject']);
Route::post('/updateSubject', [AdminController::class, 'updateSubject']);
Route::get('/deleteSubject/{id}', [AdminController::class, 'deleteSubject']);

Route::get('/createTeacher', [AdminController::class, 'createTeacher']);
Route::post('/saveTeacher', [AdminController::class, 'saveTeacher']);
Route::post('/updateTeacher', [AdminController::class, 'updateTeacher']);
Route::get('/teacherList', [AdminController::class, 'retriveTeacher']);
Route::get('/editTeacher/{id}', [AdminController::class, 'editTeacher']);
Route::get('/deleteTeacher/{id}', [AdminController::class, 'deleteTeacher']);

//teacher subject allotment
Route::get('/subjectAllotmentList', [AdminController::class, 'subjectAllotmentList']);
Route::get('/subjectAllotmentEdit/{teacherId}', [AdminController::class, 'subjectAllotmentEdit']);
Route::post('/subjectAllotmentUpdate', [AdminController::class, 'subjectAllotmentUpdate']);
Route::view('/popup','popup');


//Route::get('/createStudentFees', [AdminController::class, 'studentfeesForm']);
//Route::post('/saveStudentFees', [AdminController::class, 'createStudentFees']);
//Route::get('/studentFees', [AdminController::class, 'retriveStudentFees']);
//Route::post('/assignStudentFees', [AdminController::class, 'assignStudentFees']);
//Route::get('/editStudentFees/{register_number}', [AdminController::class, 'editStudentFees']);
//Route::post('/updateStudentFees', [AdminController::class, 'updateStudentFees']);
//Route::get('/deleteStudentFees/{id}', [AdminController::class, 'deleteStudentFees']);
//Route::get('/getFeesType/{stypeid}/{student_fees_id}', [Admincontroller::class, 'getFeesType']);
//Route::get('/getStudentList/{stdbatch}/{stdyear}/{stypeid}/{departmentid}', [Admincontroller::class, 'getStudentList']);
//
//Route::get('/createPayment', [AdminController::class, 'createPaymentForm']);
//Route::post('/savePayment', [AdminController::class, 'savePayment']);
//Route::get('/getPaymentFeesType/{stypeid}/{register_number}', [Admincontroller::class, 'getPaymentFeesType']);
//Route::get('/getStudentDetails/{regno}',[AdminController::class,'getStudentDetails']);
//Route::get('/getFeesDetails/{register_number}/{department_id}/{student_type_id}/{fees_academic_year}/{study_year}',[AdminController::class,'getFeesDetails']);
//Route::view('/paymentSuccess','admin/payment/paymentSuccess');
//Route::view('/paymentHistory','admin/payment/paymentHistory');
//Route::get('/getStudentDetailsForPaymentHistory/{regno}', [AdminController::class, 'getStudentDetailsForPaymentHistory']);
//Route::get('/getPaymentHistory/{register_number}/{fees_academic_year}',[AdminController::class,'getPaymentHistory']);



