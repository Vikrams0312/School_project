
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
        <title>Edit Teacher</title>
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
                            <h4 class="fw-bold py-1 mb-1"><span class="text-muted fw-light">Form/</span>Class</h4>
                            <hr class="my-2" />
                            <!-- Basic Layout & Basic with Icons -->
                            <div class="row">

                                <!-- Basic with Icons -->
                                <div class="col-xxl">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">Edit Class</h5>

                                        </div>
                                        <div class="card-body">
                                           <form onsubmit="return validate(this);" action="{{url('/classupdate')}}" method="post">
                                    @csrf
                                    <input type="hidden" value="{{$result[0]->id}}" name="id">
                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                            <h5 class="card-header">Enter class</h5>
                                            <div class="card-body">
                                                <div>
                                                    
                                                    <input type="text" name="class" id="class" data-oldvalue="{{$result[0]->class}}" class="form-control"  placeholder="Subject Name"   value="{{$result[0]->class}}" required>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <a href="{{url('/classlist')}}" class="btn btn-dark">Show data</a>
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
        <!-- Add JS to activate Choices.js -->

    </body>
</html>

