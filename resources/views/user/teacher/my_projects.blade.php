@extends('layouts.app')

@section('content')
    <div class="container">


        <div class="col-sm-12">

            @if(\Session::has('message'))
                <div class="alert alert-info">
                    {{\Session::get('message')}}
                </div>
            @endif

            @if (count($projects) > 0)

                            <h3><i class="fa fa-pencil"></i> {{trans('project.my_projects')}}</h3>



                            <div class="table-responsive">
                            <table class="table table-responsive table-striped project-table">
                                <thead>
                                <th>{{trans('project.project')}}</th>
                                <th>{{trans('project.publishing_status')}}</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>{{trans('project.status')}}</th>
                                <th>Tudengite vastused</th>
                                <!--
                                <th>&nbsp;</th>
                                -->
                                </thead>
                                <tbody>
                                @foreach ($projects as $project)
                                    <tr>
                                        <td class="table-text"><div>{{ $project->name }}</div></td>

                                            @if($project->publishing_status == 1)
                                                <td class="table-text green"><div><i class="fa fa-eye"></i> {{trans('project.published')}}</div></td>
                                            @else
                                                <td class="table-text red"><div><i class="fa fa-eye-slash"></i> {{trans('project.hidden')}}</div></td>

                                            @endif

                                        <td>

                                            <form action="{{ url('project/'.$project->id.'/edit') }}" method="GET">
                                                {{ csrf_field() }}
                                                {{--{{ method_field('PATCH') }}--}}

                                                <button type="submit" class="btn btn-warning pull-right btn-sm">
                                                    <i class="fa fa-btn fa-pencil"></i>{{trans('project.edit')}}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            @if(projectHasUsers($project))
                                                @if (projectHasGroupsWithMembers($project))
                                                    <div class="col-lg-12 text-center">
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-primary" href="{{ url('project/'.$project->id.'/calculate-load') }}"><i class="fa fa-btn fa-calculator"></i> {{trans('project.calc_load')}}</a>
                                                        </div>
                                                    </div>

                                                @else
                                                    <div class="col-lg-12 text-center">
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-primary disabled" href="#"><i class="fa fa-btn fa-calculator"></i> {{trans('project.calc_load')}}</a>
                                                        </div>
                                                    </div>


                                                @endif
                                            @else

                                                <div class="col-lg-12 text-center">
                                                    <div class="btn-group">
                                                        <a class="btn btn-sm btn-primary disabled" href="#"><i class="fa fa-btn fa-calculator"></i> {{trans('project.calc_load')}}</a>
                                                    </div>
                                                </div>

                                            @endif
                                        </td>
                                        <td>
                                            @if(projectHasUsers($project))
                                                @if (projectHasGroupsWithMembers($project))

                                                    <div class="col-lg-12 text-center">
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-primary not-empty my-projects-view" id="groups-finish-button" href="{{ url('project/'.$project->id.'/finish') }}"><i class="fa fa-btn fa-flag-checkered"></i>{{trans('project.finish_project_button')}}</a>
                                                        </div>
                                                    </div>

                                                @else
                                                    <div class="col-lg-12 text-center">
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-primary my-projects-view" project_id="{{$project->id}}" id="groups-finish-button" href="{{ url('project/'.$project->id.'/finish') }}"><i class="fa fa-btn fa-flag-checkered"></i>{{trans('project.finish_project_button')}}</a>
                                                        </div>
                                                    </div>


                                                @endif
                                            @else

                                                <div class="col-lg-12 text-center">
                                                    <div class="btn-group">
                                                        <a class="btn btn-sm btn-primary disabled my-projects-view" id="groups-finish-button" href="{{ url('project/'.$project->id.'/finish') }}"><i class="fa fa-btn fa-flag-checkered"></i>{{trans('project.finish_project_button')}}</a>
                                                    </div>
                                                </div>

                                            @endif
                                        </td>
                                        <td>
                                            @if(projectHasGroupsWithMembers($project) && $project->status == 0)
                                                @if(isProjectResultsFilledIn($project))
                                                    <span class="label label-success">{{trans('project.summary_completed_status')}}</span>
                                                @else
                                                    <span class="label label-danger">{{trans('project.summary_not_completed_status')}}</span>
                                                @endif

                                            @else
                                                <span class="label label-info">{{trans('project.active_status')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="col-lg-12 text-center">
                                                <div class="btn-group">
                                                    <a class="btn btn-sm btn-primary" href={{"/project/".$project->id."/student-answers"}}><i class="fa fa-btn"></i>Vaata</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </div>
                            {{ $projects->links() }}

            @else

                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans('project.no_project_found')}}</h3>
                    </div>
                    <div class="panel-body">
                        @if (!Auth::guest())
                            {{trans('project.no_project_found_desc_logged')}}
                        @else
                            {{trans('project.no_project_found_desc_not_logged')}}
                        @endif
                    </div>
                </div>


            @endif

        </div>
    </div>
@endsection
