 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{Helper::AppLogoImage()}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{Helper::AppName()}}</span>
    </a>
    <?php 
    $role = Session::get('AdminRole');
    // echo $role;die;
    // echo Request::segment(2); die;
    ?>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item menu-open ">
            <a href="{{ url('admin/home')}}" class="nav-link {{ Request::segment(2) == 'home'? 'active':'' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-cogs"></i>
              <p>
              User Role
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('admin/user')}}" class="nav-link {{ Request::segment(2) == 'user'? 'active':'' }}">
                  <i class="fa fa-gear nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/userngo')}}" class="nav-link {{ Request::segment(2) == 'userngo'? 'active':'' }}">
                  <i class="fa fa-file-o nav-icon"></i>
                  <p>NGO</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/userblood')}}" class="nav-link {{ Request::segment(2) == 'userblood'? 'active':'' }}">
                  <i class="fa fa-file-o nav-icon"></i>
                  <p>Blood Bank</p>
                </a>
              </li>
            </ul>
          </li>  

          <!-- <li class="nav-item ">
            <a href="{{ url('admin/user')}}" class="nav-link {{ Request::segment(2) == 'user'? 'active':'' }}">
              <i class="nav-icon  fa fa-user"></i>
              <p>Users</p>
            </a>
          </li>    -->
          <!-- <li class="nav-item">
            <a href="{{ url('admin/subadmin')}}" class="nav-link {{ Request::segment(2) == 'subadmin'? 'active':'' }}">
              <i class="nav-icon  fa fa-users"></i>
              <p>Sub Admin</p>
            </a>
          </li>    -->
          <li class="nav-item">
            <a href="{{ url('admin/photos')}}" class="nav-link {{ Request::segment(2) == 'photos'? 'active':'' }}">
            <i class="nav-icon fa fa-image"></i> 
              <p>Photos</p>
            </a>
          </li> 

          <li class="nav-item">
            <a href="{{ url('admin/videos')}}" class="nav-link {{ Request::segment(2) == 'videos'? 'active':'' }}">
            <i class="nav-icon fa fa-image"></i> 
              <p>Videos</p>
            </a>
          </li> 

          <li class="nav-item">
            <a href="{{ url('admin/banner')}}" class="nav-link {{ Request::segment(2) == 'banner'? 'active':'' }}">
            <i class="nav-icon fa fa-image"></i> 
              <p>Banners</p>
            </a>
          </li> 

          <li class="nav-item">
            <a href="{{ url('admin/category')}}" class="nav-link {{ Request::segment(2) == 'category'? 'active':'' }}">
            <i class="nav-icon  fa fa-sitemap"></i> 
              <p>Category</p>
            </a>
          </li> 
          
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-cogs"></i>
              <p>
              Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('admin/generalsetting')}}" class="nav-link {{ Request::segment(2) == 'generalsetting'? 'active':'' }}">
                  <i class="fa fa-gear nav-icon"></i>
                  <p>General Settings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/setting')}}" class="nav-link {{ Request::segment(2) == 'setting'? 'active':'' }}">
                  <i class="fa fa-file-o nav-icon"></i>
                  <p>Page Settings</p>
                </a>
              </li>
            </ul>
          </li>  

        </ul>
      </nav>
      <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
  </aside>