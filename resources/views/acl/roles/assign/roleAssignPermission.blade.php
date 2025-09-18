@extends('layouts.master')
@extends('layouts.sidemenu')

@section('title') Manage User Permissions @endsection
@section('content')

<!-- /.row -->

@if($role)
<form method="POST" action="{{ route('acl.role.assign.permissions', $role->id) }}" id="idForm">
    @else
    <form method="POST" action="{{ route('acl.user.assign.permissions', $user->id) }}" id="idForm">
        @endif
        @csrf
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="nav-align-left">
                            <ul class="nav nav-tabs" role="tablist">
                                @php ($inc =0)
                                @foreach($permission_group as $group)
                                <li class="nav-item">

                                    <button type="button" class="nav-link @if ($inc == 0) active @endif" role="tab"
                                        data-bs-toggle="tab" data-bs-target="#navs-{{ $group->id }}"
                                        aria-controls="navs-{{ $group->id }}" aria-selected="true">
                                        {{ $group -> name }}
                                    </button>
                                </li>
                                @php ($inc++)
                                @forelse($group->permission as $perm)
                                @php ($my_perms[$perm->id]='' )
                                @endforeach
                                @foreach($assigned_permissions as $rp)
                                @php ($my_perms[$rp->id]=$rp->name )
                                @endforeach
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @php ($inc =0)
                                @foreach($permission_group as $group)
                                <div class="tab-pane fade show @if ($inc == 0) active @endif"
                                    id="navs-{{ $group ->id }}">
                                    @php ($inc++)
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="thead-">
                                                <tr>
                                                    <th>Name</th>
                                                </tr>
                                            </thead>
                                            @forelse($group->permission as $perm)
                                            <tbody class="table-border-bottom-0">
                                                <tr>
                                                    <td>
                                                        <input id="success-{{ $perm->id }}" type="checkbox"
                                                            name="permissn[]" value="{{ $perm->id }}" {{
                                                            ($my_perms[$perm->id]
                                                        !='' ) ? 'checked=checked' :
                                                        '' }} />
                                                        <label for="success-{{ $perm->id }}">&nbsp;
                                                            &nbsp; {{
                                                            $perm->display_name }}</label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            @empty
                                            <tr>
                                                <td colspan="5">No data in the list... for now!</td>
                                            </tr>
                                            @endforelse
                                        </table>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>

                </div>

            </div>
            <!--End row-->
            <br>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="saveBtn">Save</button>

                {{-- <button type="submit" class="btn btn-crm"><i class="fa fa-check-square"></i>
                    SAVE</button> --}}
            </div>

        </div>
        <!--End Row-->
    </form>

    @endsection

    @section('js')

    <script type="text/javascript">
        $(document).on('click', 'a[data-async="true"]', function (e) {
            e.preventDefault();
            var self = $(this),
            url = self.data('endpoint'),
            target = self.data('target'),
            cache = self.data('cache');

            $.ajax({
                url: url,
                cache : cache,  
                success: function(data){ 
                    if (target !== 'undefined'){
                        $('.modal-content').html( data );
                        $('#'+target).modal('show');
                        // $('#'+target).modal();
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                }else{
                    getData(page);
                }
            }
        });
    
        $(document).ready(function()
        {
            $(document).on('click', '.pagination a',function(event)
            {
                event.preventDefault();

                $('li').removeClass('active');
                $(this).parent('li').addClass('active');

                var myurl = $(this).attr('href');
                var page=$(this).attr('href').split('page=')[1];

                getData(page);
            });
        });

        function getData(page){
            $.ajax(
            {
                url: '?page=' + page,
                type: "get",
                datatype: "html"
            }).done(function(data){
                $("#tag_container").empty().html(data);
                location.hash = page;
            }).fail(function(jqXHR, ajaxOptions, thrownError){
                Lobibox.notify('error', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'fa fa-times-circle',
                    msg: 'An error occured. Please try again. '
                });
                setTimeout(function() {
                    location.reload();
                }, 3000);
            });
        }
    </script>
    @endsection