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
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
            />

        <title>Class List</title>

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
                        <div class="card">
                            <h5 class="card-header">Class List</h5>
                            
                            <div class="card-body">
                               
                                <div class="table-responsive text-nowrap">
                             
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><a href="classadd" target="_blank" class="btn btn-success ">Add New</a></th>
                                                <th>Id</th>
                                                <th>Class</th>
                                                

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($result as $r)
                                            <tr>
                                                <td>
                                                    <a href="{{url('/classedit/'.$r->id)}}"><i class="bx bx-pencil" aria-hidden="true"></i> |</a>
                                                    <a href="{{url('/classdelete/'.$r->id)}}" onclick="return confirm('Are you sure to delete')"><i class="bx bx-trash" aria-hidden="true"></i> </a>                

                                                </td>
                                                <td>{{$r->id}}</td>
                                                <td>{{$r->class}}</td>
                                                
                                                

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
@include('admin.includes.footer')
        @include('admin.includes.floatmsg')

        <!-- Core JS -->
        @include('admin.includes.formjs')
</body>
</html>
