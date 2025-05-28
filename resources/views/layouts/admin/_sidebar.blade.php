
        <!-- Sidebar -->
        <ul class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #3cb371;">
<!-- in above inline css colour is added by me-->

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="{{route('animal.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Animals</span></a>
            </li>
            

            <li class="nav-item">
                <a class="nav-link" href="{{route('animal_calvings.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Animal Calvings</span></a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="{{route('production_milk.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Milk Records</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('dispose_milk.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Dispose Milk</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('milk_allocated_for_manufacturing.index')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Milk Records for Manufacturing</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('manufacture_product.index')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Manufacture Products</span></a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="{{route('milk_product.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Milk Products</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('dispose_milk_product.index')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Dispose Milk Products</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('feed_vaccine.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Feed Details</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('vaccine.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Vaccine Details</span></a>
            </li>

            <!--supplier feed vaccine details -->
            <li class="nav-item">
                <a class="nav-link" href="{{route('supply_feed_vaccine.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Supplier Details</span></a>
            </li>



            <li class="nav-item">
                <a class="nav-link" href="{{route('purchase_feed_items.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Purchase Feed Items</span></a>
            </li>
            
         
            <li class="nav-item">
                <a class="nav-link" href="{{route('dispose_feed_items.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Dispose Feed Items</span></a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="{{route('purchase_vaccine_items.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Purchase Vaccine Items</span></a>
            </li>
            

            <li class="nav-item">
                <a class="nav-link" href="{{route('dispose_vaccine_items.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Dispose Vaccine Items</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('feed_consume_items.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Feed Consumption</span></a>
            </li>

            
            <li class="nav-item">
                <a class="nav-link" href="{{route('vaccine_consume_items.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Vaccine Consumption</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('purchase_feed_payments.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Purchase Feed Payments</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('retailor_order_items.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Retailor Orders</span></a>
            </li>


          

            <li class="nav-item">
                <a class="nav-link" href="{{route('appointment.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Appointment</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('tasks.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Tasks</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('tasks_assignment.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Tasks Assignment</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('tasks_execution.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Tasks Execution</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('salary.list')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Salary</span></a>
            </li>
           
             <li class="nav-item">
                <a class="nav-link" href="{{route('monthly_salary_payment.create')}}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Monthly Salary</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.list') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Users</span></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>AnimalBreedings</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="{{route('animal_breedings.list')}}">breeding_event</a>
                        <a class="collapse-item" href="{{route('animal_pregnancies.list')}}">Pregnancies</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li>
            

            
            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
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
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div>

        </ul>
        <!-- End of Sidebar -->