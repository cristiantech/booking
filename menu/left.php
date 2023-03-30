<div class="left-side-menu">
    <div class="h-100" data-simplebar> 
        
        <!-- User box -->
        <div class="user-box text-center"> <img src="/assets/images/users/user-1.jpg" alt="user-img" title="Mat Helme" class="rounded-circle img-thumbnail avatar-md">
            <p class="text-muted left-user-info"><? echo substr($row_login['fullname'], 0, strpos($row_login['fullname'], " ")); ?></p>
        </div>
        
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li class="menu-title">Navegación</li>
                <!-- <li>
                    <a href="/index.php">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> Dashboard </span>
                    </a>
                </li> -->
                <li>
                    <a href="/docs/index.php">
                        <i class="mdi mdi-account-search"></i>
                        <span> Mis Documentos </span>
                    </a>
                </li>
                <li>
                    <a href="#reservaciones" data-bs-toggle="collapse">
                        <i class="mdi mdi-account-search"></i>
                        <span> Reservar </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="reservaciones">
                        <ul class="nav-second-level">
                            <li><a href="/booking/index.php">Nueva Reserva</a></li>
                            <li><a href="/booking/list.php">Mis Reservas</a></li>                       
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="/pago/index.php">
                        <i class="mdi mdi-account-search"></i>
                        <span> Gestión de Pago </span>
                    </a>
                </li>
                <li>
                    <a href="/pacientes/index.php">
                        <i class="mdi mdi-account-search"></i>
                        <span> Citas de Telemedicina </span>
                    </a>
                </li>
                <li>
                    <a href="/pacientes/index.php">
                        <i class="mdi mdi-account-search"></i>
                        <span> Contáctanos </span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- End Sidebar -->
        
        <div class="clearfix"></div>
    </div>
    
    <!-- Sidebar -left --> 
</div>
