
        <!-- Sidebar -->
        <ul class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #3cb371;">
<!-- in above inline css colour is added by me-->

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
               <div class="sidebar-brand-text mx-3">
   <!--     <img src="{{ asset('images/cow-2-logo.jpg') }}" alt="Logo" style="height: 40px; width: auto;"> -->
    </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                  
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
         <!--   <div class="sidebar-heading">
                Interface
            </div> -->


            <!-- Nav Item - Pages Collapse Menu -->
            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 6)
            <li class="nav-item">
                <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseUtilities1"
                    aria-expanded="true" aria-controls="collapseUtilities1">
               <i class="fa-solid fa-cow"></i>
                    <span>Animals</span>
                </a>
                <div id="collapseUtilities1" class="collapse" aria-labelledby="headingUtilities1"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="{{route('animal.list')}}">Animal Details</a>
                        <a class="collapse-item" href="{{route('animal_calvings.list')}}">Animal Calvings</a>
                        <a class="collapse-item" href="{{route('animal_breedings.list')  }}">Breeding Events</a>
                        <a class="collapse-item" href="{{route('animal_pregnancies.list')  }}">Pregnancies</a>
                      
                    </div>
                </div>
            </li>
            @endif

            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6 || Auth::user()->role_id == 5)

             <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseUtilities2"
                    aria-expanded="true" aria-controls="collapseUtilities2">
                   <i class="fa-solid fa-glass-water"></i>
                    <span>Milk</span>
                </a>
                <div id="collapseUtilities2" class="collapse" aria-labelledby="headingUtilities2"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="{{route('production_milk.list')}}">Milk Records</a>
                        <a class="collapse-item" href="{{route('dispose_milk.list')}}">Dispose Milk</a>
                        <a class="collapse-item" href="{{route('milk_allocated_for_manufacturing.index')  }}">Milk Consumption</a>
                        <a class="collapse-item" href="{{route('milk_product.list')  }}">Milk Products</a>
                        <a class="collapse-item" href="{{route('dispose_milk_product.index')  }}">Dispose Milk Products</a>
                        <a class="collapse-item" href="{{route('manufacture_product.index')  }}">Manufacture Products</a>
                    </div>
                </div>
            </li>
            @endif


            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6 || Auth::user()->role_id == 5)

            <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseUtilities3"
                    aria-expanded="true" aria-controls="collapseUtilities3">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Feed</span>
                </a>
                <div id="collapseUtilities3" class="collapse" aria-labelledby="headingUtilities3"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6)
                        <a class="collapse-item" href="{{route('feed_vaccine.list')}}">Feed Details</a>
                        @endif

                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6)
                        <a class="collapse-item" href="{{route('purchase_feed_items.list')}}">Purchase Feed Items</a>
                        @endif

                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6  || Auth::user()->role_id == 5)
                        <a class="collapse-item" href="{{route('dispose_feed_items.list')  }}">Dispose Feed Items</a>
                        @endif

                         @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6  || Auth::user()->role_id == 5)
                        <a class="collapse-item" href="{{route('feed_consume_items.list')  }}">Feed Consumption</a>
                        @endif

                      
                    </div>
                </div>
            </li>
             @endif

              @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 6)
            <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseUtilities4"
                    aria-expanded="true" aria-controls="collapseUtilities4">
                    <i class="fa-solid fa-syringe"></i>
                    <span>Vaccine</span>
                </a>
                <div id="collapseUtilities4" class="collapse" aria-labelledby="headingUtilities4"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="{{route('vaccine.list')}}">Vaccine Details</a>
                         @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6)
                        <a class="collapse-item" href="{{route('purchase_vaccine_items.list')}}">Purchase Vaccine Items</a>
                        @endif
                        <a class="collapse-item" href="{{route('dispose_vaccine_items.list')  }}">Dispose Vaccine Items</a>
                        <a class="collapse-item" href="{{route('vaccine_consume_items.list')  }}">Vaccine Consumption</a>
                       
                      
                    </div>
                </div>
            </li>
            @endif

              @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 7)

            <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseUtilities5"
                    aria-expanded="true" aria-controls="collapseUtilities5">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Payment</span>
                </a>
                <div id="collapseUtilities5" class="collapse" aria-labelledby="headingUtilities5"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="{{route('purchase_vaccine_payments.create')}}">Purchase Vaccine Payment</a>
                        <a class="collapse-item" href="{{route('purchase_feed_payments.list')}}">Purchase Feed Payment</a>
                
                       
                      
                    </div>
                </div>
            </li>
             @endif

            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 7 ||  Auth::user()->role_id == 5 || Auth::user()->role_id == 3 )
            <li class="nav-item">
                <a class="nav-link" href="{{route('retailor_order_items.list')}}">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span>Retailor Orders</span></a>
            </li>
            @endif

            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6)
            <li class="nav-item">
                <a class="nav-link" href="{{route('appointment.list')}}">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Appointment</span></a>
            </li>
             @endif


            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6 ||  Auth::user()->role_id == 5)
            <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseUtilities6"
                    aria-expanded="true" aria-controls="collapseUtilities6">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Tasks</span>
                </a>
                <div id="collapseUtilities6" class="collapse" aria-labelledby="headingUtilities6"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>

                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6)
                        <a class="collapse-item" href="{{ route('tasks.list') }}">Task</a>
                        @endif

                         @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 6)
                        <a class="collapse-item" href="{{ route('tasks_assignment.list') }}">Task Assignment</a>
                        @endif


                        <a class="collapse-item" href="{{ route('tasks_execution.list')}}">Task Execution</a>
                            
                    </div>
                </div>
            </li>
                @endif

            @if (Auth::user()->role_id == 1)
            <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseUtilities7"
                    aria-expanded="true" aria-controls="collapseUtilities7">
                    <i class="fa-solid fa-user"></i>
                    <span>Users</span>
                </a>


                <div id="collapseUtilities7" class="collapse" aria-labelledby="headingUtilities7"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="{{ route('main_user_details.list') }}">User Details</a>
                         <a class="collapse-item" href="{{ route('users.list') }}">Role based User Details</a>
                        <a class="collapse-item" href="{{ route('supply_feed_vaccine.list') }}">Supplier Details</a>
                       
                            
                    </div>
                </div>
            </li>
            @endif

             @if (Auth::user()->role_id == 1)
            <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseUtilities8"
                    aria-expanded="true" aria-controls="collapseUtilities8">
                    <i class="fa-solid fa-dollar-sign"></i>
                    <span>Salary</span>
                </a>
                <div id="collapseUtilities8" class="collapse" aria-labelledby="headingUtilities8"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="">Salary Details</a>
                        <a class="collapse-item" href="">Monthly Salary</a>
                       
                            
                    </div>
                </div>
            </li>
             @endif
           
          

           
          <li class="nav-item">
                <a class="nav-link" href="{{ route('milk.production.report') }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Report1 </span></a>
            </li>
           

             <li class="nav-item">
                <a class="nav-link" href="{{ route('milk.production.report_for_animal') }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Report2 </span></a>
            </li>


             <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.allocated_milk_for_each_product') }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Report 3 </span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('report.purchase_feed') }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Report 4 </span></a>
            </li>
          
             <li class="nav-item">
                <a class="nav-link" href="{{ route('report.animal_birth') }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Report 5 </span></a>
            </li>
           
          

                
        

          

         


          

          

            

          
            <!-- Divider -->
      <!--      <hr class="sidebar-divider">-->

            <!-- Heading -->
     <!--       <div class="sidebar-heading">
                Addons
            </div> -->

            <!-- Nav Item - Pages Collapse Menu -->
     <!--       <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="blank.html">Blank Page</a>
                    </div>
                </div>
            </li> -->

            <!-- Nav Item - Charts -->
       <!--     <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li> -->

            <!-- Nav Item - Tables -->
    <!--        <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>-->

            <!-- Divider -->
      <!--      <hr class="sidebar-divider d-none d-md-block"> -->

            <!-- Sidebar Toggler (Sidebar) -->
       <!--     <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div> -->

            <!-- Sidebar Message -->
      <!--      <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div> -->

        </ul>
        <!-- End of Sidebar -->


         

        