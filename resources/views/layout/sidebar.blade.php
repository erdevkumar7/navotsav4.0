<div id="scrollbar">
    <div class="container-fluid">


        <div id="two-column-menu">
        </div>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title d-flex justify-content-center"><span data-key="t-menu"><img
                        src="{{ asset('assets/images/logo.png') }}" alt="" width="137px" height="116px"></span>
            </li>
            <li class="nav-item">
                <a href="{{ route(routePrefix() . 'dashboard') }}"
                    class="nav-link @if (request()->routeIs('dashboard')) active @endif ">
                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                </a>
            </li> <!-- end Dashboard Menu -->

                        @if (auth()->user()->hasAnyPermission(['view tickets']))
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#ticketManagment" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-ticket-line"></i> <span data-key="t-apps">Tickets</span>
                    </a>
                    <div class="collapse menu-dropdown  @if (request()->routeIs([routePrefix() . 'ticket.list', routePrefix() . 'winner.list'])) show @endif "
                        id="ticketManagment">
                        <ul class="nav nav-sm flex-column">
                            @can('view tickets')
                                <li class="nav-item">
                                    <a href="{{ route(routePrefix() . 'ticket.list') }}"
                                        class="nav-link  @if (request()->routeIs(routePrefix() . 'ticket.list')) active @endif">Tickets
                                    </a>
                                </li>
                            @endcan


                            {{-- <li class="nav-item">
                                <a href="{{ route(routePrefix() . 'winner.list') }}"
                                    class="nav-link  @if (request()->routeIs(routePrefix() . 'winner.list')) active @endif">Winners
                                </a>
                            </li> --}}


                        </ul>
                    </div>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['view events', 'create events']))
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#eventManagment" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-apps-2-line"></i> <span data-key="t-apps">Event Management</span>
                    </a>

                    <div class="collapse menu-dropdown @if (request()->routeIs([routePrefix() . 'event.create', routePrefix() . 'event.index'])) show @endif"
                        id="eventManagment">
                        <ul class="nav nav-sm flex-column">
                            @can('view events')
                                <li class="nav-item">
                                    <a href="{{ route(routePrefix() . 'event.index') }}"
                                        class="nav-link @if (request()->routeIs(routePrefix() . 'event.index')) active @endif ">Event list
                                    </a>
                                </li>
                            @endcan
                            @can('create events')
                                <li class="nav-item">
                                    <a href="{{ route(routePrefix() . 'event.create') }}"
                                        class="nav-link @if (request()->routeIs(routePrefix() . 'event.create')) active @endif">Create Event
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endif

            

            {{-- @if (auth()->user()->hasAnyPermission(['view categories', 'add category']))
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#categoryApps" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApps">
                            <i class="ri-apps-2-line"></i> <span data-key="t-apps">Category Management</span>
                        </a>
                        <div class="collapse menu-dropdown @if (request()->routeIs([routePrefix() . 'category.create', routePrefix() . 'category.index'])) show @endif
    "
                            id="categoryApps">
                            <ul class="nav nav-sm flex-column">
                                @can('view categories')
                                    <li class="nav-item">
                                        <a href="{{ route(routePrefix() . 'category.index') }}"
                                            class="nav-link  @if (request()->routeIs(routePrefix() . 'category.index')) active @endif">Categories
                                        </a>
                                    </li>
                                @endcan
                                @can('add category')
                                    <li class="nav-item">
                                        <a href="{{ route(routePrefix() . 'category.create') }}"
                                            class="nav-link @if (request()->routeIs(routePrefix() . 'category.create')) active @endif ">Create Category
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endif --}}
            @if (auth()->user()->hasRole(['super-admin', 'event-organizer']))
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#rolesApps" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="rolesApps">
                        <i class="ri-apps-2-line"></i> <span data-key="t-apps">Roles & Permissions</span>
                    </a>
                    <div class="collapse menu-dropdown @if (request()->routeIs([routePrefix() . 'roles.index', routePrefix() . 'roles.edit'])) show @endif
" id="rolesApps">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route(routePrefix() . 'roles.index') }}"
                                    class="nav-link  @if (request()->routeIs(routePrefix() . 'roles.index')) active @endif">Roles
                                </a>
                            </li>
                        </ul>
                    </div>

                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['view users', 'view organizers', 'view buyers']))
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#userApps" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="bx bx-group"></i> <span data-key="t-apps">User Management
                        </span>
                    </a>
                    <div class="collapse menu-dropdown @if (request()->routeIs([routePrefix() . 'organizers', routePrefix() . 'buyers', routePrefix() . 'users.index'])) show @endif " id="userApps">
                        <ul class="nav nav-sm flex-column">
                            {{-- @can('view users')
                                <li class="nav-item">
                                    <a href="{{ route(routePrefix() . 'users.index') }}"
                                        class="nav-link  @if (request()->routeIs(routePrefix() . 'users.index')) active @endif">Users
                                    </a>
                                </li>
                            @endcan --}}
                            @if (auth()->user()->hasRole('super-admin'))
                                <li class="nav-item">
                                    <a href="{{ route(routePrefix() . 'organizers') }}"
                                        class="nav-link  @if (request()->routeIs(routePrefix() . 'organizers')) active @endif">Organizers
                                    </a>
                                </li>
                            @endif
                            @can('view buyers')
                                <li class="nav-item">
                                    <a href="{{ route(routePrefix() . 'buyers') }}"
                                        class="nav-link  @if (request()->routeIs(routePrefix() . 'buyers')) active @endif">Buyers
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endif


            {{-- <li class="nav-item">
                <a class="nav-link menu-link" href="#blogManagment" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarApps">
                    <i class="ri-article-line"></i> <span data-key="t-apps">Blogs Management</span>
                </a>
                <div class="collapse menu-dropdown  @if (request()->routeIs([routePrefix() . 'blog.list', routePrefix() . 'blog.list'])) show @endif "
                    id="blogManagment">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(routePrefix() . 'blog.list') }}"
                                class="nav-link  @if (request()->routeIs(routePrefix() . 'blog.list')) active @endif">Blogs
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route(routePrefix() . 'blog.create') }}"
                                class="nav-link  @if (request()->routeIs(routePrefix() . 'blog.create')) active @endif">Create
                            </a>
                        </li>


                    </ul>
                </div>
            </li> --}}

            {{-- <li class="nav-item">
                <a class="nav-link menu-link" href="#salesTransaction" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarApps">
                     <i class="bx bxs-bar-chart-alt-2"></i> <span data-key="t-apps">Sales Transactions</span>
                </a>
                <div class="collapse menu-dropdown  @if (request()->routeIs([routePrefix() . 'pos.sales.list', routePrefix() . 'online.sales.list'])) show @endif "
                    id="salesTransaction">
                    <ul class="nav nav-sm flex-column">

                        <li class="nav-item">
                            <a href="{{ route(routePrefix() . 'pos.sales.list') }}"
                                class="nav-link  @if (request()->routeIs(routePrefix() . 'pos.sales.list')) active @endif">Pos Sales
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route(routePrefix() . 'online.sales.list') }}"
                                class="nav-link  @if (request()->routeIs(routePrefix() . 'online.sales.list')) active @endif">Online Sales
                            </a>
                        </li>


                    </ul>
                </div>
            </li> --}}


            {{-- <li class="nav-item">
                <a href="{{ route(routePrefix() . 'claim.requests') }}"
                    class="nav-link @if (request()->routeIs(routePrefix() . 'claim.requests')) active @endif ">
                    <i class="bx bx-group"></i> <span data-key="t-apps">Claim Requests</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route(routePrefix() . 'contact.list') }}"
                    class="nav-link @if (request()->routeIs(routePrefix() . 'contact.list')) active @endif ">
                    <i class="bx bx-group"></i> <span data-key="t-apps">Contact Leads</span>
                </a>
            </li> --}}

            <li class="nav-item">
                <a href="{{ route(routePrefix() . 'settings') }}"
                    class="nav-link @if (request()->routeIs(routePrefix() . 'settings')) active @endif ">
                    <i class="ri-settings-line"></i> <span data-key="t-apps">Admin Settings</span>
                </a>
            </li>

        </ul>
    </div>
    <!-- Sidebar -->
</div>

<div class="sidebar-background"></div>
