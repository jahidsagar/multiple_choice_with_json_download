<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="{{url('/')}}"><i class="fa fa-dashboard fa-fw"></i> Packages</a>
                        </li>
                        {{-- <li>
                            <a href="{{ url('/dashboard/app')}}" style="display: none;"><i class="fa fa-braille  fa-fw"></i>Add PackageName</a>
                        </li> --}}
                        {{-- <li>
                            <a href="{{ url('/dashboard/category')}}" style="display: none;"><i class="fa fa-braille  fa-fw"></i>Categories</a>
                        </li> --}}
                        @if($roleforview == 'admin')
                        <li>
                            <a href="{{ url('/dashboard/setrole')}}"><i class="fa fa-users fa-fw"></i> Set Roles</a>
                        </li>
                        @endif
                        {{-- <li style="display: none;">
                            <a href="#"><i class="fa fa-wrench fa-fw" ></i> Questions<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{ url('/dashboard/questions')}}"><i class="fa fa-braille  fa-fw"></i> Add New Question</a>
                                </li>
                                <li>
                                    <a href="{{ url('/dashboard/questions/seeallquestion')}}"><i class="fa fa-braille  fa-fw"></i> see all questions</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>       --}}      
                        
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>