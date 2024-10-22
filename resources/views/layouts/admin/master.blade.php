<!DOCTYPE html>
<html lang="en">

<head>
@include('layouts/admin/_meta')
@include('layouts/admin/_style')



</head>

<body id="page-top">

    <div id="wrapper">

        @include('layouts/admin/_sidebar')
            <div id="content-wrapper" class="d-flex flex-column">

                    <div id="content">

                        @include('layouts/admin/_header')

                        <div class="container-fluid">

                        

                        @yield('header')
                        @yield('content')
                        </div>
                    </div>

                    @include('layouts/admin/_footer')

            </div>
            
    </div>
    @include('layouts/admin/_logout')
    @include('layouts/admin/_script')
</body>

</html>


 
