@extends('layouts.app')

@section('content')
<!-- Pealkiri -->
<div class="col-log-12 col-lg-offset-2">
    <h2 class="h2 class-uppercase"><b>{{trans('project.add')}}</b></h2>
</div>

<div class="container">

    <div class="row">
    <div class=col-lg-6>
        <!-- Description about project language selection -->
        <p>
            Siin on mingi kirjeldus selle kohta, et tee projekti keelevalik ja kui soovid,
            siis saad sama projekti kohe kahes erinevas keeles luua.
        </p>

        <!-- Project language selection -->
        <div class="col-lg-12 panel">
            <p>
                Projekti töökeel(t)e valik
            </p>
            <span id="project_in_estonian"><input type="checkbox" checked><span>Eesti keel</span></span>
            <span id="project_in_english"><input type="checkbox" checked><span>Inglise keel</span></span>
        </div>

        <!-- Reminder about fujlfilling learning outcomes -->
        <p>
            <span id="open_learning_outcomes" style="display:none;" class="glyphicon glyphicon-triangle-right"></span><span id="close_learning_outcomes" class="glyphicon glyphicon-triangle-bottom"></span>ELU projekti juhtides tuleb tagada õppeaine õpiväljundid:
        </p>

        <!-- Course's learning outcomes  -->
        <div id="learning_outcomes"><p>Üliõpilane:</p>
        <p>
            <ul>
            <li>kasutab meeskonnaliikmetega koostöös loodud ELU kavandis korrektselt projektijuhtimise ja interdistsiplinaarse lähenemise põhimõisteid;</li>
            <li>loob ELU planeerimisel distsipliinidevahelisi seoseid ja analüüsib teiste erialade perspektiivi lähtudes püstitatud projekti lahendusvõimalustest;</li>
            <li>kasutab ELU algatamisel ja elluviimisel probleemi äratundmise ja lahendamise ning ajajuhtimise oskuseid;</li>
            <li>kasutab ELU elluviimise käigus omandatud uusi erialaseid teadmisi tuues ELU kaitsmisel välja nende valdkondadevahelised rakendamisvõimalused;</li>
            <li>osaleb tulemuslikult meeskonnatöös ELU projekti planeerimisel, elluviimisel ja kaitsmisel;</li>
            </ul>
        </p>
        </div>

    </div>
    <div class=col-lg-6>
        <!-- General comments about the project from LIFE coordinators -->
        <p>
            Siin on kommentaarid, mis ei käi ühegi konkreetse
            välja kohta, aga mida on muudatuste tegemisel hea arvesse võtta
        </p>
    </div>
    </div>

    <!-- Start of the first block -->
    <div class="col-lg-12 panel">


        <!-- PROJECT NAME -->
        <div class="col-lg-12">

            <!-- Project name in Estonian -->
            <div class="col-lg-6 form_estonian">

                <!-- Project name input in Estonian -->
                <div class="form-group">
                    <p><label for="project_name_et">Projekti nimi *</label></p>
                    <input class="form-control" type="text" name="project_name_et">
                </div>

                <!-- Comment for name in Estonian -->
                <div id="comment_name_et"></div>

            </div>

            <!-- Project name in English -->
            <div class="col-lg-6 form_english">

                <!-- Project name input in English -->
                <div class="form-group">
                    <p><label for="project_name_en">Project name *</label></p>
                    <input class="form-control" type="text" name="project_name_en">
                </div>

                <!-- Comment for name in English -->
                <div id="comment_name_en"></div>

            </div>
        </div>


        <!-- DESCRIPTION -->
        <div class="col-lg-12">

            <!-- Project description in Estonian -->
            <div class="col-lg-6 form_estonian">

                <!-- Project description input in Estonian -->
                <div class="form-group">
                    <p><label for="description_et">
                        Kirjeldus *
                        <i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
                    </label></p>
                    <textarea class="mceSimple" name="description_et"></textarea>
                </div>

                <!-- Comment for description in Estonian -->
                <div id="comment_description_et"></div>

            </div>

            <!-- Project description in English -->
            <div class="col-lg-6 form_english">

                <!-- Project description input in English -->
                <div class="form-group">
                    <p><label for="description_en">
                        Description *
                        <i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
                    </label></p>
                    <textarea class="mceSimple" name="description_en"></textarea>
                </div>

                <!-- Comment for description in English -->
                <div id="comment_description_en"></div>

            </div>
        </div>


        <!-- PROJECT OUTCOME -->
        <div class="col-lg-12">

            <!-- Project outcome in Estonian -->
            <div class="col-lg-6 form_estonian">

                <!-- Project outcome input in Estonian -->
                <div class="form-group">
                    <p><label for="project_outcome_et">
                        Oodatavad väljundid *
                        <i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
                    </label></p>
                    <textarea class="mceSimple" name="project_outcome_et"></textarea>
                </div>

                <!-- Comment for outcome in Estonian -->
                <div id="comment_project_outcome_et"></div>

            </div>

            <!-- Project outcome in English -->
            <div class="col-lg-6 form_english">

                <!-- Project outcome input in English -->
                <div class="form-group">
                    <p><label for="project_outcome_en">
                        Project outcome *
                        <i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
                    </label></p>
                    <textarea class="mceSimple" name="project_outcome_en"></textarea>
                </div>

                <!-- Comment for outcome in English -->
                <div id="comment_project_outcome_en"></div>

            </div>
        </div>


        <!-- INTERDISCIPLINARY APPROACH -->
        <div class="col-lg-12">

            <!-- Interdisciplinary approach in Estonian -->
            <div class="col-lg-6 form_estonian">

                <!-- Interdisciplinary approach input in Estonian -->
                <div class="form-group">
                    <p><label for="interdisciplinary_approach_et">
                        Interdistsiplinaarsus *
                        <i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
                    </label></p>
                    <textarea class="mceSimple" name="interdisciplinary_approach_et"></textarea>
                </div>

                <!-- Comment for interdisciplinary approach in Estonian -->
                <div id="comment_interdisciplinary_approach_et"></div>

            </div>

            <!-- Interdisciplinary approach in English -->
            <div class="col-lg-6 form_english">

                <!-- Interdisciplinary approach input in English -->
                <div class="form-group">
                    <p><label for="interdisciplinary_approach_en">
                        Interdisciplinary approach *
                        <i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
                    </label></p>
                    <textarea class="mceSimple" name="interdisciplinary_approach_en"></textarea>
                </div>

                <!-- Comment for interdisciplinary approach in English -->
                <div id="comment_interdisciplinary_approach_en"></div>

            </div>
        </div>


        <!-- TAGS -->
        <div class="col-lg-12">

            <!-- Tags in Estonian -->
            <div class="col-lg-6 form_estonian">

                <!-- Tags input in Estonian -->
                <div class="form-group">
                    <p><label for="tags_et">Märksõnad *</label></p>
                    <input class="form-control" type="text" name="tags_et">
                </div>

                <!-- Div to show the selected tags_et to the user -->
                <div id="tags_et_output" class="form-group"></div>

                <!-- Comment for tags in Estonian -->
                <div id="comment_tags_et"></div>

            </div>

            <!-- Tags in English -->
            <div class="col-lg-6 form_english">

                <!-- Tags input in English -->
                <div class="form-group">
                    <p><label for="tags_en">Keywords *</label></p>
                    <input class="form-control" type="text" name="tags_en">
                </div>

                <!-- Div to show the selected tags_en to the user -->
                <div id="tags_en_output" class="form-group row"></div>

                <!-- Comment for tags in English -->
                <div id="comment_tags_en"></div>

            </div>
        </div>


        <!-- ADDITIONAL INFORMATION -->
        <div class="col-lg-12">

            <!-- Additional information in Estonian -->
                <div class="col-lg-6 form_estonian">

                <!-- Additional information input in Estonian -->
                <div class="form-group">
                    <p><label for="additional_information_et">Lisainfo</label></p>
                    <textarea class="mceSimple" name="additional_information_et" ></textarea>
                </div>

                <!-- Comment for additional information in Estonian -->
                <div id="comment_additional_information_et"></div>

            </div>

            <!-- Additional information in English -->
                <div class="col-lg-6 form_english">

                <!-- Additional information input in English -->
                <div class="form-group">
                    <p><label for="additional_information_en">Additional information</label></p>
                    <textarea class="mceSimple" name="additional_information_en"></textarea>
                </div>

                <!-- Comment for additional information in English -->
                <div id="comment_additional_information_en"></div>

            </div>
        </div>


        <!-- COMMENT FOR LIFE COORDINATORS -->
        <div class="col-lg-12">

            <!-- Comment for LIFE coordinators in Estonian -->
            <div class="col-lg-6 form_estonian">

                <!-- Comment for LIFE coordinators input in Estonian -->
                <div class="form-group">
                    <p><label for="comment_for_LIFE_cordinators_et">Märkus ELU kordinaatoritele</label></p>
                    <textarea class="mceSimple" name="comment_for_LIFE_cordinators_et"></textarea>
                </div>

                <!-- Comment for comment for LIFE coordinators in Estonian -->
                <div id="comment_comment_for_LIFE_cordinators_et"></div>

            </div>

            <!-- Comment for LIFE coordinators in English -->
            <div class="col-lg-6 form_english">

                <!-- Comment for LIFE coordinators input in English -->
                <div class="form-group">
                    <p><label for="comment_for_LIFE_cordinators_en">Comment for LIFE coordinators</label></p>
                    <textarea class="mceSimple" name="comment_for_LIFE_coordinators_en"></textarea>
                </div>

                <!-- Comment for comment for LIFE coordinators in  -->
                <div id="comment_comment_for_LIFE_cordinators_en"></div>

            </div>
        </div>


        <!-- PARTNERS -->
        <div class="col-lg-12">

            <!-- Partner(s) in Estonian -->
            <div class="col-lg-6 form_estonian">

                <!-- Partner(s) input in Estonian -->
                <div class="form-group">
                    <p><label for="partners_et">Koostööpartneri või ettevõtte nimi</label></p>
                    <input class="form-control" type="text" name="partners_et">
                </div>

                <!-- Comment for Partner(s) in Estonian -->
                <div id="comment_partners_et"></div>

            </div>

            <!-- Partner(s) in English -->
            <div class="col-lg-6 form_english">

                <!-- Partner(s) input in English -->
                <div class="form-group">
                    <p><label for="partners_en">Name of associate/associate company</label></p>
                    <input class="form-control" type="text" name="partners_en">
                </div>

                <!-- Comment for Partner(s) in English -->
                <div id="comment_partners_en"></div>

            </div>
        </div>


        <!-- End of the first block -->
        </div>


        <!-- Start of the second block -->
        <div class="col-lg-12 panel">

            <!-- Project semester(s) -->
            <label for="project_duration">
                Projekti kestvus semestrites
                <i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
            </label>
            <div class="col-lg-12" id="project_duration">
                <div class="col-lg-6">
                    <button type="button" class="btn btn-info btn-lg btn-block">Sügissemester</button>
                    <button type="button" class="btn btn-default btn-lg btn-block">Sügis-Kevad</button>
                </div>

                <div class="col-lg-6">
                    <button type="button" class="btn btn-default btn-lg btn-block">Kevadsemester</button>
                    <button type="button" class="btn btn-default btn-lg btn-block">Kevad-Sügis</button>
                </div>
            </div>

        <!-- End of the second block -->
        </div>


        <!-- Start of the third block -->
        <div class="col-lg-12 panel">

            <!-- Meetings in Estonian start -->
            <div class="col-lg-6 form_estonian">

                <!-- Additional info about the meetings in Estonian -->
                <div class="col-lg-12">
                    <label for="meetings_info_et">
                        Kohtumiste täiendav info *
                    </label>
                    <textarea rows="5" name="meetings_info_et" style="width: 100%;"></textarea>
                </div>


                <!-- Meetings info in Estonian -->
                <div class="col-lg-12">
                    <label for="first_meeting_et">
                        Esimene kohtumine *
                        <i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
                    </label>
                    <div id='first_meeting_et'>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="col-lg-12"><span class="glyphicon glyphicon-calendar" style="font-size:75px;"></span></div>
                                <div class="col-lg-12">dd/mm</div>
                            </div>
                            <div class="col-lg-8">
                                <textarea rows="5" name="meetings_et" style="width: 100%;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div id='other_meetings_et'>
                    </div>
                </div>

                <!-- Icons for adding/removing another Estonian meeting input field -->
                <div class="col-lg-12">
                    <div class="pull-right">
                        <span id="remove_meeting_et" class="glyphicon glyphicon-trash"></span>
                        <span id="add_meeting_et" class="glyphicon glyphicon-plus"></span>
                    </div>
                </div>

            <!-- Meetings in Estonian end -->
            </div>


            <!-- Meetings in English start -->
            <div class="col-lg-6 form_english">

                <!-- Additional info about the meetings in English -->
                <div class="col-lg-12">
                    <label for="meetings_info_en">
                        Info about the meetings *
                    </label>
                    <textarea rows="5" name="meetings_info_en" style="width: 100%;"></textarea>
                </div>


                <!-- Meetings info in English -->
                <div class="col-lg-12">
                    <label for="first_meeting_en">
                        The first meeting *
                        <i class="fa fa-question-circle" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
                    </label>
                    <div id='first_meeting_en'>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="col-lg-12"><span class="glyphicon glyphicon-calendar" style="font-size:75px;"></span></div>
                                <div class="col-lg-12">dd/mm</div>
                            </div>
                            <div class="col-lg-8">
                                <textarea rows="5" name="meetings_en" style="width: 100%;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div id='other_meetings_en'>
                    </div>
                </div>

                <!-- Icons for adding/removing another English meeting input field -->
                <div class="col-lg-12">
                    <div class="pull-right">
                        <span id="remove_meeting_en" class="glyphicon glyphicon-trash"></span>
                        <span id="add_meeting_en" class="glyphicon glyphicon-plus"></span>
                    </div>
                </div>

            <!-- Meetings in English end -->
            </div>

        <!-- End of the third block -->
        </div>


        <!-- Start of the fourth block -->
        <div class="col-lg-12 panel">
            <div class="col-lg-6">

            <!-- Featured video link -->
            <p><label for="featured_video_link">Tutvustav video</label></p>
            <input class="form-control" type="text" name="featured_video_link">

            <!-- Featured image -->
            <p><label for="thumbnail">Tutvustav kaanepilt</label></p>
<!--            <input class="form-control" type="text" name="thumbnail">-->
            <input type="file" name="featured_image" id="featured_image" class="form-control" value="{{ old('featured_image') }}">

            </div>

            <div class="col-lg-6">
            <!-- Supervisor -->
            <p><label for="supervisor">Vastutav juhendaja *</label></p>
            <input class="form-control" type="text" name="supervisor" required>

            <!-- Supervising student -->
            <p><label for="supervising_student">Juhendav üliõpilane</label></p>
            <input class="form-control" type="text" name="supervising_student">

            <!-- Cosupervisor -->
            <p><label for="co_supervisor">Kaasjuhendaja</label></p>
            <!-- div created to have something to append the inputs to, if user wants to add more than one cosupervisor -->
            <div id="co_supervisor_div">
                <input class="form-control" type="text" name="co_supervisor">
            </div>

            <!-- Icons for adding/removing cosupervisor input -->
            <div class="pull-right">
                <span id="remove_cosupervisor" class="glyphicon glyphicon-trash"></span>
                <span id="add_cosupervisor" class="glyphicon glyphicon-plus"></span>
            </div>

            </div>
        <!-- End of the fourth block -->
        </div>

    <!-- Ankeet lõpp -->
    <!--</div>-->

    <!-- Submit options -->
    <div class="col-lg-12">
        <div class="col-lg-4"><buttontype="button" class="btn btn-default btn-lg btn-block">Salvesta</button></div>
        <div class="col-lg-4"><buttontype="button" class="btn btn-default btn-lg btn-block">
            <span class="glyphicon glyphicon-share-alt" style="font-size:15px;" aria-hidden="true"></span> Jaga teistega
        </button></div>
        <div class="col-lg-4"><buttontype="button" class="btn btn-info btn-lg btn-block">Esita</button></div>
    </div>



        <div class="col-lg-12">
        <div class="col-lg-6">

        </div>
        <div class="col-lg-6">
        </div>
            <div class="panel panel-default">
<!--
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-plus"></i> {{trans('project.add')}}</h3>
                </div>
-->
                <div class="panel-body">

                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- New Project Form -->
                    <form action="{{ url('project/new')}}" id="project-form" method="POST" class="form-horizontal new-project" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <!-- Project Name -->
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">{{trans('project.name')}} *</label>

                            <div class="col-sm-6">
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                        </div>

                        <!-- Project Embedded media -->
                        <div class="form-group">
                            <label for="embedded" class="col-sm-3 control-label">{{trans('project.video_link')}} <p>https://youtu.be/...</p></label>

                            <div class="col-sm-6">
                                <input type="text" name="embedded" id="embedded" class="form-control" value="{{  old('embedded') }}">
                            </div>
                        </div>

                        <!-- Project Featured image -->
                        <div class="form-group">
                            <label for="featured_image" class="col-sm-3 control-label">{{trans('project.featured_image')}} <p>{{trans('project.portrait_orientation')}}</p></label>

                            <div class="col-sm-6">

                                <input type="file" name="featured_image" id="featured_image" class="form-control" value="{{ old('featured_image') }}">

                            </div>
                        </div>

                        <!-- Project description -->
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">{{trans('project.description')}} *</label>

                            <div class="col-sm-6">

                                <textarea name="description" id="description" class="form-control mceSimple">{!! old('description') !!}</textarea>
                            </div>
                        </div>


                        <!-- Project aim -->
                        <div class="form-group">
                            <label for="aim" class="col-sm-3 control-label">{{trans('project.aim')}} *</label>

                            <div class="col-sm-6">

                                <textarea name="aim" id="aim" class="form-control mceSimple">{!! old('aim') !!}</textarea>
                            </div>
                        </div>


                        <!-- Project Interdisciplinary Desc -->
                        <div class="form-group">
                            <label for="interdisciplinary_desc" class="col-sm-3 control-label">{{trans('project.interdisciplinary_desc')}}
                                <i class="fa fa-question-circle" style="cursor: pointer" data-toggle="popover" data-placement="top" data-content="{{trans('project.interdisciplinary_desc_desc')}}"></i>
                            </label>

                            <div class="col-sm-6">

                                <textarea name="interdisciplinary_desc" id="interdisciplinary_desc" class="form-control mceSimple">{!! old('interdisciplinary_desc') !!}</textarea>
                            </div>
                        </div>


                        <!-- Project Novelty Desc -->
                        <div class="form-group">
                            <label for="novelty_desc" class="col-sm-3 control-label">{{trans('project.novelty_desc')}} *</label>

                            <div class="col-sm-6">

                                <textarea name="novelty_desc" id="novelty_desc" class="form-control mceSimple">{!! old('novelty_desc') !!}</textarea>
                            </div>
                        </div>


                        <!-- Project Outcomes -->
                        <div class="form-group">
                            <label for="project_outcomes" class="col-sm-3 control-label">{{trans('project.outcomes')}} *</label>


                            <div class="col-sm-6">
                                <textarea name="project_outcomes" id="project_outcomes" class="form-control mceSimple">{!! old('project_outcomes') !!}</textarea>
                            </div>
                        </div>

                        <!-- Expectations for students -->
                        <div class="form-group">
                            <label for="student_expectations" class="col-sm-3 control-label">{{trans('project.student_expectations')}}</label>

                            <div class="col-sm-6">
                                <textarea name="student_expectations" id="student_expectations" class="form-control mceSimple">
                                    @if(empty( old('student_expectations')))
                                        <p class="mceNonEditable"><i>{{trans('project.student_expectations_desc_1')}}</i></p>
                                        <p class="mceNonEditable"><i>{{trans('project.student_expectations_desc_2')}}</i></p>
                                        <p class="mceNonEditable"><i>{{trans('project.student_expectations_desc_3')}}</i></p>
                                        <p class="mceNonEditable"><i>{{trans('project.student_expectations_desc_4')}}</i></p>
                                        <p class="mceNonEditable"><i>{{trans('project.student_expectations_desc_5')}}</i></p>
                                        <p class="mceNonEditable"><i>{{trans('project.student_expectations_desc_6')}}</i></p>
                                    @else
                                        {!! old('student_expectations') !!}
                                    @endif
                                </textarea>
                            </div>
                        </div>


                        <div class="well well-sm">

                            <!-- Project meeting info -->
                            <div class="form-group">
                                <label for="meeting_info" class="col-sm-3 control-label">{{trans('project.meeting_info')}}</label>

                                <div class="col-sm-6">
                                    <input type="text" name="meeting_info" id="meeting_info" class="form-control" value="{{  old('meeting_info') }}">
                                </div>
                            </div>

                            <!-- Dates for group meetings -->
                            <div class="form-group">
                                <label for="meetings_dates_text" class="col-sm-3 control-label">{{trans('project.meetings_dates')}}</label>

                                <div class="col-sm-6">
                                    <div class="radio">
                                        <label><input onclick="document.getElementById('meetings_dates_text').disabled = true;" type="radio" name="meetings_dates_radio" id="meetings_dates_radio" {{!empty(old('meetings_dates_radio'))? 'checked' : ''}}> {{trans('project.to_be_arranged')}}</label>
                                    </div>
                                    <div class="radio">
                                        <label><input onclick="document.getElementById('meetings_dates_text').disabled = false;" type="radio" name="meetings_dates_radio" id="meetings_dates_radio" {{!empty(old('meetings_dates_text'))? 'checked' : ''}}> {{trans('project.other')}}:</label>
                                    </div>
                                    <input type="text" name="meetings_dates_text" id="meetings_dates_text" class="form-control" value="{{  !empty(old('meetings_dates_text'))? old('meetings_dates_text') : '' }}">
                                </div>
                            </div>


                            <!-- Presentation of project results -->
                            <div class="form-group">
                                <label class="col-sm-3" style="text-align: right">{{trans('project.presentation_of_results')}}</label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="presentation_results" name="presentation_results">
                                        @if ( old('presentation_results')) == 0)
                                        <option value="0" selected>{{trans('project.presentation_of_results_december')}}</option>
                                        @else
                                            <option value="0">{{trans('project.presentation_of_results_december')}}</option>
                                        @endif

                                        @if ( old('presentation_results')) == 1)
                                        <option value="1" selected>{{trans('project.presentation_of_results_may')}}</option>
                                        @else
                                            <option value="1">{{trans('project.presentation_of_results_may')}}</option>
                                        @endif

                                    </select>
                                </div>

                            </div>

                            <!-- Interim evaluation date -->
                            <div class="form-group">
                                <label for="evaluation_date" class="col-sm-3 control-label">{{trans('project.evaluation_date')}}</label>

                                <div class="col-sm-6">

                                        <select class="form-control" id="evaluation_date" name="evaluation_date">
                                            @foreach($evaluation_dates as $evaluation_date)
                                                <option value="{{$evaluation_date->id}}" {{old('evaluation_date') == $evaluation_date->id ? 'selected' : ''}}>{{date("m/d/Y", strtotime($evaluation_date->evaluation_date))}}</option>
                                            @endforeach

                                        </select>
                                </div>
                            </div>
                        </div>


                        {{--<!-- Study area -->--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="study_areas" class="col-sm-3 control-label">{{trans('project.study_area')}}</label>--}}


                            {{--<div class="col-sm-6">--}}
                                {{--<select class="js-example-basic-multiple form-control" id="study_areas" name="study_areas[]" multiple>--}}

                                    {{--@if ($courses->count())--}}

                                        {{--@foreach($courses as $course)--}}

                                            {{--@if(!empty(old('study_areas')))--}}
                                                {{--<option {{ in_array( $course->id, old('study_areas')) ? "selected":"" }} value="{{ $course->id }}">{{ getCourseName($course) }}</option>--}}

                                            {{--@else--}}
                                                {{--<option value="{{ $course->id }}">{{ getCourseName($course) }}</option>--}}
                                            {{--@endif--}}

                                        {{--@endforeach--}}

                                    {{--@endif--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}


                        <!-- Study term -->
                        <div class="form-group">
                            <label for="study_term" class="col-sm-3 control-label">{{trans('project.duration')}} *</label>

                            <div class="col-sm-6">
                                <select class="form-control" id="study_term" name="study_term">
                                    @if ( old('study_term')) == 0)
                                        <option value="0" selected>{{trans('project.autumn_semester')}}</option>
                                    @else
                                        <option value="0">{{trans('project.autumn_semester')}}</option>
                                    @endif

                                    @if ( old('study_term')) == 1)
                                        <option value="1" selected>{{trans('project.spring_semester')}}</option>
                                    @else
                                        <option value="1">{{trans('project.spring_semester')}}</option>
                                    @endif

                                    @if ( old('study_term')) == 2)
                                        <option value="2" selected>{{trans('project.autumn_spring')}}</option>
                                    @else
                                        <option value="2">{{trans('project.autumn_spring')}}</option>
                                    @endif

                                    @if ( old('study_term')) == 3)
                                        <option value="3" selected>{{trans('project.spring_autumn')}}</option>
                                    @else
                                        <option value="3">{{trans('project.spring_autumn')}}</option>
                                    @endif

                                </select>
                            </div>
                        </div>



                        <!-- Study year -->
                        <div class="form-group">
                            <label for="study_year" class="col-sm-3 control-label">{{trans('project.study_year')}} *</label>

                            <div class="col-sm-6">
                                <select class="form-control" id="study_year" name="study_year">
                                    @if( old('study_year') == Carbon\Carbon::now()->year)
                                        <option value="{{Carbon\Carbon::now()->year}}" selected>{{Carbon\Carbon::now()->year}}/{{Carbon\Carbon::now()->year+1}}</option>
                                        <option value="{{Carbon\Carbon::now()->year-1}}">{{Carbon\Carbon::now()->year-1}}/{{Carbon\Carbon::now()->year}}</option>
                                    @elseif(old('study_year') == Carbon\Carbon::now()->year-1)
                                        <option value="{{Carbon\Carbon::now()->year-1}}" selected>{{Carbon\Carbon::now()->year-1}}/{{Carbon\Carbon::now()->year}}</option>
                                        <option value="{{Carbon\Carbon::now()->year}}">{{Carbon\Carbon::now()->year}}/{{Carbon\Carbon::now()->year+1}}</option>
                                    @else
                                        @if(Carbon\Carbon::now()->month >= 6)
                                            <option value="{{Carbon\Carbon::now()->year}}" selected>{{Carbon\Carbon::now()->year}}/{{Carbon\Carbon::now()->year+1}}</option>
                                            <option value="{{Carbon\Carbon::now()->year-1}}">{{Carbon\Carbon::now()->year-1}}/{{Carbon\Carbon::now()->year}}</option>
                                        @else
                                            <option value="{{Carbon\Carbon::now()->year-1}}" selected>{{Carbon\Carbon::now()->year-1}}/{{Carbon\Carbon::now()->year}}</option>
                                            <option value="{{Carbon\Carbon::now()->year}}">{{Carbon\Carbon::now()->year}}/{{Carbon\Carbon::now()->year+1}}</option>

                                        @endif
                                    @endif

                                </select>
                            </div>
                        </div>


                        {{--<!-- Related Courses -->--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="related_courses" class="col-sm-3 control-label">{{trans('project.related_courses')}} <p>{{trans('project.one_per_line')}}</p></label>--}}

                            {{--<div class="col-sm-6">--}}
                                {{--<textarea name="related_courses" id="related_courses" class="form-control">{{  old('related_courses') }}</textarea>--}}
                            {{--</div>--}}
                        {{--</div>--}}


                        {{--<!-- Project start -->--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="project_start" class="col-sm-3 control-label">{{trans('project.start')}}</label>--}}
                            {{--<div class='col-sm-6'>--}}
                                {{--<div class='input-group date' id='project_start'>--}}

                                    {{--<input type='text' class="form-control" name="project_start" id="project_start" value="{{ old('project_start') }}"/>--}}
                                    {{--<span class="input-group-addon">--}}
                                        {{--<span class="glyphicon glyphicon-calendar"></span>--}}
                                    {{--</span>--}}

                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}


                        {{--<!-- Project end -->--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="project_end" class="col-sm-3 control-label">{{trans('project.end')}}</label>--}}
                            {{--<div class='col-sm-6'>--}}
                                {{--<div class='input-group date' id='project_end'>--}}
                                    {{--<input type='text' class="form-control" name="project_end" id="project_end" value="{{ old('project_end') }}"/>--}}
                                    {{--<span class="input-group-addon">--}}
                                        {{--<span class="glyphicon glyphicon-calendar"></span>--}}
                                    {{--</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}


                        <!-- Institutes -->
                        {{--<div class="form-group">--}}
                            {{--<label for="institutes" class="col-sm-3 control-label">{{trans('project.institute')}}</label>--}}

                            {{--<div class="col-sm-6">--}}
                                {{--<select class="form-control" id="institutes" name="institutes">--}}

                                    {{--@if ( old('institutes')) == 0 )--}}
                                        {{--<option value="0" selected>Balti filmi, meedia, kunstide ja kommunikatsiooni instituut</option>--}}
                                    {{--@else--}}
                                        {{--<option value="0">Balti filmi, meedia, kunstide ja kommunikatsiooni instituut</option>--}}
                                    {{--@endif--}}


                                    {{--@if ( old('institutes')) == 1)--}}
                                        {{--<option value="1" selected>Digitehnoloogiate instituut</option>--}}
                                    {{--@else--}}
                                        {{--<option value="1">Digitehnoloogiate instituut</option>--}}
                                    {{--@endif--}}

                                    {{--@if ( old('institutes')) == 2)--}}
                                        {{--<option value="2" selected>Humanitaarteaduste instituut</option>--}}
                                    {{--@else--}}
                                        {{--<option value="2">Humanitaarteaduste instituut</option>--}}
                                    {{--@endif--}}


                                    {{--@if ( old('institutes')) == 3)--}}
                                        {{--<option value="3" selected>Haridusteaduste instituut</option>--}}
                                    {{--@else--}}
                                        {{--<option value="3">Haridusteaduste instituut</option>--}}
                                    {{--@endif--}}


                                    {{--@if ( old('institutes')) == 4)--}}
                                        {{--<option value="4" selected>Loodus- ja terviseteaduste instituut</option>--}}
                                    {{--@else--}}
                                        {{--<option value="4">Loodus- ja terviseteaduste instituut</option>--}}
                                    {{--@endif--}}


                                    {{--@if ( old('institutes')) == 5)--}}
                                        {{--<option value="5" selected>Rakvere kolledž</option>--}}
                                    {{--@else--}}
                                        {{--<option value="5">Rakvere kolledž</option>--}}
                                    {{--@endif--}}


                                    {{--@if ( old('institutes')) == 6)--}}
                                        {{--<option value="6" selected>Haapsalu kolledž</option>--}}
                                    {{--@else--}}
                                        {{--<option value="6">Haapsalu kolledž</option>--}}
                                    {{--@endif--}}


                                    {{--@if ( old('institutes')) == 7)--}}
                                        {{--<option value="7" selected>Ühiskonnateaduste instituut</option>--}}
                                    {{--@else--}}
                                        {{--<option value="7">Ühiskonnateaduste instituut</option>--}}
                                    {{--@endif--}}


                                {{--</select>--}}
                            {{--</div>--}}
                        {{--</div>--}}



                        <!-- Supervisors -->
                        <div class="form-group">
                            <label for="supervisors" class="col-sm-3 control-label">{{trans('project.supervisor')}} *</label>


                            <div class="col-sm-6">
                                <select class="js-example-basic-multiple form-control" id="supervisors" name="supervisors[]" multiple>
                                    @if ($teachers->count())

                                        @foreach($teachers as $teacher)

                                            @if(!empty(old('supervisors')))
                                                <option {{ in_array( $teacher->id, old('supervisors')) ? "selected":"" }} value="{{ $teacher->id }}">{{ getUserName($teacher) }}</option>
                                            @else
                                                <option value="{{ $teacher->id }}" {{ $author == $teacher->id ? 'selected="selected"' : '' }}>{{ getUserName($teacher) }}</option>
                                            @endif

                                        @endforeach

                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Co-supervisors -->
                        <div class="form-group">
                            <label for="cosupervisors" class="col-sm-3 control-label">{{trans('project.cosupervisor')}} <p>{{trans('project.one_per_line')}}</p></label>

                            <div class="col-sm-6">
                                <textarea name="cosupervisors" id="cosupervisors" class="form-control">{{ old('cosupervisors') }}</textarea>
                            </div>
                        </div>


                        <!-- Status -->
                        {{--<div class="form-group">--}}
                            {{--<label for="status" class="col-sm-3 control-label">{{trans('project.status')}}</label>--}}

                            {{--<div class="col-sm-6">--}}
                                {{--<select class="form-control" disabled id="status" name="status">--}}

                                    {{--@if ( old('status')) == 1)--}}
                                    {{--<option value="1" selected>{{trans('project.active')}}</option>--}}
                                    {{--@else--}}
                                        {{--<option value="1">{{trans('project.active')}}</option>--}}
                                    {{--@endif--}}

                                    {{--@if ( old('status')) == 0)--}}
                                        {{--<option value="0" selected>{{trans('project.finished')}}</option>--}}
                                    {{--@else--}}
                                        {{--<option value="0">{{trans('project.finished')}}</option>--}}
                                    {{--@endif--}}

                                {{--</select>--}}
                                {{--<input type="hidden" name="status" value="1" />--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <!-- Tags -->
                        <div class="form-group">
                            <label for="tags" class="col-sm-3 control-label">{{trans('project.keywords')}} *<p>{{trans('project.separated_with_commas')}}</p></label>

                            <div class="col-sm-6">
                                <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags') }}" data-role="tagsinput" />
                            </div>
                        </div>


                        <!-- Project deadline for joining -->
                        <div class="form-group">
                            <label for="join_deadline" class="col-sm-3 control-label">{{trans('project.deadline')}} *</label>
                            <div class='col-sm-6'>
                                <div class='input-group date' id='join_deadline'>

                                    <input type='text' class="form-control" name="join_deadline" id="join_deadline" value="{{ old('join_deadline') }}"/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>

                                </div>
                            </div>
                        </div>

                        <!-- Extra info -->
                        <div class="form-group">
                            <label for="extra_info" class="col-sm-3 control-label">{{trans('project.extra_info')}}</label>

                            <div class="col-sm-6">
                                <textarea name="extra_info" id="extra_info" class="form-control">{{ old('extra_info') }}</textarea>
                            </div>
                        </div>


                        <!-- Language-->
                        <div class="form-group">
                            <label for="language" class="col-sm-3 control-label">{{trans('project.language')}} *</label>

                            <div class="col-sm-6">
                                <input type="hidden" name="language" value="{{$project_language}}">
                                <select class="form-control" id="language" name="language" disabled>

                                    <option value="et" {{$project_language == 'et' ? 'selected' : ''}}>Eesti</option>

                                    <option value="en" {{$project_language == 'en' ? 'selected' : ''}}>English</option>

                                </select>
                            </div>
                        </div>


                        <!-- Status -->
                        <div class="form-group">
                            <label for="publishing_status" class="col-sm-3 control-label">{{trans('project.publishing')}} *</label>

                            <div class="col-sm-6">
                                <input type="hidden" name="publishing_status" value=0>
                                <select class="form-control" id="publishing_status" name="publishing_status" disabled>

                                    <option value="0" selected>{{trans('project.hidden')}}</option>

                                    <option value="1">{{trans('project.published')}}</option>

                                </select>

                                <!-- Tooltip letting users know why the project status is hidden at first -->
                                {{trans('project.reason_of_initial_hiddenness')}}

                            </div>
                        </div>


                        {{--<!-- Mendeley group link -->--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="group_link" class="col-sm-3 control-label">{{trans('project.mendeley_group_link')}}</label>--}}

                            {{--<div class="col-sm-6">--}}
                                {{--<input type="text" name="group_link" id="group_link" class="form-control" value="{{  old('group_link') }}">--}}
                            {{--</div>--}}
                        {{--</div>--}}


                        <!-- Get notifications -->
                        <div class="form-group">

                            <div class="col-sm-6 col-sm-offset-3">
                                <div class="checkbox">
                                    <h4>
                                        <label>
                                            <input name="get_notifications" id="get_notifications" type="checkbox"  {{ (old('get_notifications') =='on' ? 'checked' : '' )}}> {{trans('project.get_notification')}}
                                        </label>
                                    </h4>
                                </div>

                            </div>
                        </div>


                        <!-- Add Project Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button id="submit-project-button" type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-plus"></i>{{trans('project.add_button')}}
                                </button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>

            <!-- Current Projects -->
            @if (count($projects) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{trans('project.my_projects')}}
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                        <table class="table table-responsive table-striped project-table">
                            <thead>
                            <th>{{trans('project.project')}}</th>
                            <th>&nbsp;</th>
                            <!--
                            <th>&nbsp;</th>
                            -->
                            </thead>
                            <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td class="table-text"><div>{{ $project->name }}</div></td>

                                    <!-- Project Delete Button -->
                                    <td>

                                        <form action="{{ url('project/'.$project->id.'/edit') }}" method="GET">
                                            {{ csrf_field() }}
                                            {{--{{ method_field('PATCH') }}--}}

                                            <button type="submit" class="btn btn-warning pull-right">
                                                <i class="fa fa-btn fa-pencil"></i>{{trans('project.edit')}}
                                            </button>
                                        </form>
                                    </td>
                                    <!--
                                    <td>
                                        <form class="delete-project" action="{{ url('project/'.$project->id.'/delete') }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}


                                        </form>
                                        <button type="submit" id="delete" class="btn btn-danger pull-right">
                                            <i class="fa fa-btn fa-trash"></i>{{trans('project.delete')}}
                                        </button>

                                    </td>
                                    -->
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
