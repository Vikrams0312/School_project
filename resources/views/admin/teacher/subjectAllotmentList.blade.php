<!DOCTYPE html>
<!-- beautify ignore:start -->
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
    data-template="vertical-menu-template-free"
    >
    <head>
        <title>Teacher List</title
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
                            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Table /</span> Teacher List </h4>

                            <hr class="my-5" />

                            <!-- Bordered Table -->
                            <div class="card">
                                <h5 class="card-header">Teacher list</h5>
                                <div class="card-body">
                                    <div class="table-responsive text-nowrap">
                                         <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Action</th>
                <th>Teacher</th>
                <th>Class</th>
                <th>Group</th>
                <th>Subject</th>
                <th>Section</th>
                <th>Teacher Type</th>
                <th>Academic Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allotments as $row)
            <tr>
                <td>
                    <a href="{{ url('/subjectAllotmentEdit/'.$row->teacher_id) }}" class="btn btn-sm btn-primary">
                        Edit
                    </a>
                </td>
                <td>{{ $row->teacher_name }}</td>
                <td>{{ $row->standard }}</td>
                <td>{{ $row->group_short_name}}</td>
                <td>{{ $row->subject_name }}</td>
                <td>{{ $row->section }}</td>
                <td>{{ $row->teacher_type == 'CT' ? 'Class Teacher' : 'Subject Teacher' }}</td>
                <td>{{ $row->academic_year }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
                                    </div>
                                </div>
                            </div>
                            <!--/ Bordered Table -->


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
    </body>
</html>


