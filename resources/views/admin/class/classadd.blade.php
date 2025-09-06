<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free"
    >
    <head>
        <title>Class Registration</title>
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
                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Class Registration</h5>

                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                <div class="alert alert-danger mt-2">
                                    @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                                @endif
                                <form action="{{url('/classinsert')}}" method="post">
                                    @csrf

                                    <div class="col-md-6">
                                        <div class="card mb-4">
                                           
                                            <div class="card-body">
                                                <div>
                                                    <label for="defaultFormControlInput" class="p-1" >Enter Class:</label>
                                                    <input type="number" min="1" max="12" name="class" class="form-control" id="defaultFormControlInput" placeholder="Class" aria-describedby="defaultFormControlHelp" required>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                             

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{url('/classlist')}}" class="btn btn-dark">Show data</a>
                                </form>
                            </div>
                        </div>

                        <!-- / Content -->
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


    </body>
</html>
  
