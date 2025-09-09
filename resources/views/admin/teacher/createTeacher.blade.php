<!DOCTYPE html>
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
    data-template="vertical-menu-template-free"
    >
    <head>
        <title>Create Teacher</title>

        @include('admin.includes.formcss')
        
    </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->

                @include('admin.includes.menu')
                <!-- / Menu -->
                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Navbar -->
                    @include('admin.includes.nav')

                    <!-- / Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->

                        <div class="container-xxl flex-grow-1 container-p-y">
                            <h4 class="fw-bold py-1 mb-1">Teacher</h4>
                            <hr class="my-2" />
                            <!-- Basic Layout & Basic with Icons -->
                            <div class="row">

                                <!-- Basic with Icons -->
                                <div class="col-xxl">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">Create New Teacher</h5>

                                        </div>
                                        <div class="card-body">
                                            <form method="post" action="{{url('/save-teacher')}}">
                                                @csrf
                                                @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Teacher Name</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group input-group-merge">
                                                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                                                  ><i class="bx bx-user"></i
                                                                ></span>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="basic-icon-default-fullname"
                                                                name="teacher_name"
                                                                required
                                                                value="{{old('teacher_name')}}"
                                                                placeholder="Enter teacher name "
                                                                aria-label="Enter teacher name "
                                                                aria-describedby="basic-icon-default-fullname2"
                                                                autofocus="true"
                                                                />
                                                        </div>
                                                        @error('teacher_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Qualification</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group input-group-merge">
                                                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                                                  ><i class="bx bx-user"></i
                                                                ></span>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="basic-icon-default-fullname"
                                                                name="qualification"
                                                                required
                                                                value="{{old('qualification')}}"
                                                                placeholder="Enter qualification"
                                                                aria-label="Enter qualification"
                                                                aria-describedby="basic-icon-default-fullname2"
                                                                autofocus="true"
                                                                />
                                                        </div>
                                                        @error('father_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>                                                
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Teacher Email</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group input-group-merge">
                                                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                                                  ><i class="bx bx-mail-send"></i
                                                                ></span>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="basic-icon-default-fullname"
                                                                name="teacher_email"
                                                                required
                                                                value="{{old('teacher_email')}}"
                                                                placeholder="Enter teacher email"
                                                                aria-label="Enter teacher email"
                                                                aria-describedby="basic-icon-default-fullname2"
                                                                autofocus="true"
                                                                />
                                                        </div>
                                                        @error('teacher_email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Password</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group input-group-merge">
                                                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                                                  ><i class="bx bx-mail-send"></i
                                                                ></span>
                                                            <input
                                                                type="password"
                                                                class="form-control"
                                                                id="basic-icon-default-fullname"
                                                                name="password"
                                                                required
                                                                placeholder="Enter password"
                                                                aria-label="Enter password"
                                                                aria-describedby="basic-icon-default-fullname2"
                                                                />
                                                            @error('password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror

                                                        </div>

                                                    </div>
                                                </div>

                                                <div id="assignment-rows">
                                                    <div class="row mb-3 assignment-row">

                                                        <!-- Class -->
                                                        <div class="col-md-2">
                                                            <label class="form-label">Class</label>
                                                            <select name="class_ids[]" class="form-select" required>
                                                                <option value="">-- Select Class --</option>
                                                                @foreach($classes as $class)
                                                                <option value="{{ $class->standard }}">{{ $class->standard }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>

                                                        <!-- Group / Short Name -->
                                                        <div class="col-md-2">
                                                            <label class="form-label">Group Name</label>
                                                            <select name="shortname_ids[]" class="form-select">
                                                                <option value="">-- Select Group --</option>
                                                                @foreach($groups as $group)
                                                                    <option value="{{ $group->id }}">{{ $group->group_short_name }}</option>
                                                                @endforeach
                                                            </select>


                                                        </div>

                                                        <!-- Subject -->
                                                        <div class="col-md-2">
                                                            <label class="form-label">Subjects</label>
                                                            <select class="form-select" name="subject_ids[]" required>
                                                                <option value="">-- Select Subject --</option>
                                                                @foreach($subjects as $subject)
                                                                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Section -->
                                                        <div class="col-md-2">
                                                            <label class="form-label">Section</label>
                                                            <select name="sections[]" class="form-select" required>
                                                                <option value="">-- Select Section --</option>
                                                                <option value="NoSection">No Section</option>
                                                                @foreach(range('A', 'G') as $section)
                                                                <option value="{{ $section }}">{{ $section }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Teacher Type -->
                                                        <div class="col-md-2">
                                                            <label class="form-label">Teacher Type</label>
                                                            <select name="teacher_types[]" class="form-select" required>
                                                                <option value="">-- Select Type --</option>
                                                                <option value="CT">Class Teacher</option>
                                                                <option value="ST">Subject Teacher</option>
                                                            </select>
                                                        </div>

                                                        <!-- Academic Year -->
                                                        <div class="col-md-2">
                                                            <label class="form-label">Academic Year</label>
                                                            <input type="text" id="year-range" class="form-control" name="academic_years[]" required />
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Add Row Button -->
                                                <div class="mb-3">
                                                    <button type="button" class="btn btn-sm btn-success" id="add-assignment-row">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Designation</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group input-group-merge">
                                                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                                                  ><i class="bx bx-user"></i
                                                                ></span>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="basic-icon-default-fullname"
                                                                name="designation"
                                                                required
                                                                value="{{old('designation')}}"
                                                                placeholder="Enter designation"
                                                                aria-label="Enter designation"
                                                                aria-describedby="basic-icon-default-fullname2"
                                                                autofocus="true"
                                                                />
                                                        </div>
                                                        @error('designation')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror

                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Experience</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group input-group-merge">
                                                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                                                  ><i class="bx bx-user"></i
                                                                ></span>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="basic-icon-default-fullname"
                                                                name="experience"
                                                                required
                                                                value="{{old('experience')}}"
                                                                placeholder="Enter experience"
                                                                aria-label="Enter experience"
                                                                aria-describedby="basic-icon-default-fullname2"
                                                                autofocus="true"
                                                                />
                                                        </div>
                                                        @error('experience')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Previous Work Station</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group input-group-merge">
                                                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                                                  ><i class="bx bx-user"></i
                                                                ></span>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="basic-icon-default-fullname"
                                                                name="previous_work_station"
                                                                required
                                                                value="{{old('previous_work_station')}}"
                                                                placeholder="Enter previous work station"
                                                                aria-label="Enter previous work station"
                                                                aria-describedby="basic-icon-default-fullname2"
                                                                autofocus="true"
                                                                />
                                                        </div>
                                                        @error('previous_work_station')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">mobile</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group input-group-merge">
                                                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                                                  ><i class="bx bx-mobile-alt"></i
                                                                ></span>
                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                id="mobileno"
                                                                name="mobile"
                                                                onkeyup="return numberValidation(this, 10)"
                                                                value="{{old('mobile')}}"
                                                                required=""
                                                                placeholder="Enter mobile"
                                                                aria-label="Enter mobile"
                                                                aria-describedby="basic-icon-default-fullname2"
                                                                autofocus="true"
                                                                />
                                                        </div>
                                                        @error('mobile')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>                                                                                                 

                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Join Date</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group input-group-merge">
                                                            <span id="basic-icon-default-fullname2" class="input-group-text"
                                                                  ><i class="bx bx-calendar"></i
                                                                ></span>
                                                            <input
                                                                type="date"
                                                                class="form-control"
                                                                id="basic-icon-default-fullname"
                                                                value="{{old('join_date')}}"
                                                                name="join_date"
                                                                required
                                                                placeholder="dd-mm-yyyy"
                                                                aria-label="Enter Join Date"
                                                                aria-describedby="basic-icon-default-fullname2"                                                               
                                                                />
                                                        </div>
                                                        @error('join_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div> 

                                                <div class="row justify-content-end">
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-info">Save</button>
                                                        <a href="{{url('/teacher-list')}}" class="btn btn-primary">Show list</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / Content -->

                        <!-- Footer -->
                        @include('admin.includes.footer')
                        <!-- / Footer -->

                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- / Layout wrapper -->

        @include('admin.includes.floatmsg')

        <!-- Core JS -->
        @include('admin.includes.formjs')
        <script>
            $(document).ready(function () {
                $('#multiple-select-custom-field').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: $('#multiple-select-custom-field').data('placeholder'),
                    closeOnSelect: false,
                    tags: false
                });
            });
        </script>
        <script>
            let rowIndex = 1;

            $('#add-assignment-row').on('click', function () {
                let newRow = $('.assignment-row:first').clone();

                // Update the subject_ids name with new index
                //newRow.find('select[name^="subject_ids"]').attr('name', `subject_ids[]`);

                // Clear selected values
                newRow.find('select').val('');
                newRow.find('input').val('');
                // Add remove button only if it doesn’t exist
                if (newRow.find('.remove-row').length === 0) {
                    newRow.append(`
                         <div class="col-md-1 d-flex align-items-end">
                             <button type="button" class="btn btn-sm btn-danger remove-row">
                                 <i class="fa-solid fa-xmark"></i> 
                             </button>
                         </div>
                     `);
                }
                $('#assignment-rows').append(newRow);

            });

            // Remove row if "Remove" is clicked and more than 1 row exists
            $(document).on('click', '.remove-row', function () {
                if ($('.assignment-row').length > 1) {
                    $(this).closest('.assignment-row').remove();
                }
            });
            document.addEventListener('change', function (e) {
                if (e.target.matches('select[name="class_ids[]"]')) {
                    const row = e.target.closest('.assignment-row');
                    const classValue = parseInt(e.target.value);
                    const shortnameSelect = row.querySelector('select[name="shortname_ids[]"]');

                    if (classValue >= 1 && classValue <= 10) {
                        shortnameSelect.value = '';
                        shortnameSelect.disabled = true;
                    } else {
                        shortnameSelect.disabled = false;
                    }
                }
            });
        </script>

    </body>
</html>

