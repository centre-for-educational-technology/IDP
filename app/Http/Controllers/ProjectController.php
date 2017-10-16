<?php

namespace App\Http\Controllers;

use App\Course;
use App\GroupMaterial;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectByStudentRequest;

use App\Project;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use Cohensive\Embed\Facades\Embed;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Group;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests\FinishedProjectRequest;
use App\Http\Requests\AddProjectGroupRequest;
use App\Http\Requests\AttachUsersRequest;
use App\EvaluationDate;




class ProjectController extends Controller
{


  /**
   * List the published projects
   *
   */
  public function indexOpenProjects()
  {


    $projects = Project::where('publishing_status', '=', '1')->where('status', '=', '1')->where('join_deadline', '>=', Carbon::today()->format('Y-m-d'))->orderBy('name', 'asc')->paginate(20);

    if(Auth::user()){
      return view('project.search')
          ->with('projects', $projects)
          ->with('isTeacher', Auth::user()->is('oppejoud'));
    }else{
      return view('project.search')
          ->with('projects', $projects)
          ->with('isTeacher', false);
    }



  }


  /**
   * List ongoing projects
   *
   */
  public function indexOngoingProjects()
  {


    $projects = Project::where('publishing_status', '=', '1')->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->orderBy('name', 'asc')->paginate(20);

    if(Auth::user()){
      return view('project.search')
          ->with('projects', $projects)
          ->with('isTeacher', Auth::user()->is('oppejoud'));
    }else{
      return view('project.search')
          ->with('projects', $projects)
          ->with('isTeacher', false);
    }



  }


  /**
   * List finished projects
   *
   */
  public function indexFinishedProjects()
  {


    $projects = Project::where('publishing_status', '=', '1')->where('status', '=', '0')->orderBy('name', 'asc')->paginate(20);

    if(Auth::user()){
      return view('project.search')
          ->with('projects', $projects)
          ->with('isTeacher', Auth::user()->is('oppejoud'));
    }else{
      return view('project.search')
          ->with('projects', $projects)
          ->with('isTeacher', false);
    }



  }


  /**
   * Add new project form
   */
  public function add(Request $request)
  {
	  
	  $lang = $request->lang;
	  
	  $project_language = 'et';
	  if(!empty($lang)){
		  $project_language = $lang;
		  \App::setLocale($lang);
		  session(['applocale' => $lang]);
		 
	  }
	  
	  
    $projects = Project::whereHas('users', function($q)
    {
      $q->where('id', Auth::user()->id);
    })->get();




    $teachers = User::select('id','name', 'full_name')->whereHas('roles', function($q)
    {
      $q->where('name', 'oppejoud');
    })->get();
	
//
//	  if(\App::getLocale() == 'en'){
//		  $courses = Course::select('id','oppekava_eng')->get();
//	  }else{
//		  $courses = Course::select('id','oppekava_est')->get();
//	  }
	
	
	  $evaluation_dates = EvaluationDate::orderBy('id', 'desc')->take(3)->get();

  
    $author =  Auth::user()->id;

    return view('project.new', compact('teachers', 'author', 'projects', 'evaluation_dates', 'project_language'));


  }


  /**
   * Save new project
   */
  public function store(ProjectRequest $request)
  {


    $project = new Project;
    $project->name = $request->name;
    $project->description = $request->description;


    if($request->embedded != null){

      $embed = Embed::make($request->embedded)->parseUrl();

      if ($embed) {
        // Set width of the embed
        $embed->setAttribute(['width' => 600]);
      }
      $embed_html = $embed->getHtml();

      $project->embedded = $embed_html;

    }
	
	  $project->aim = $request->aim;
	
	  $project->interdisciplinary_desc = $request->interdisciplinary_desc;
	
	  $project->novelty_desc = $request->novelty_desc;
	
	  $project->project_outcomes = $request->project_outcomes;
	
	  $project->student_expectations = $request->student_expectations;
	  
	  if(!empty($request->meetings_dates_text)){
		  $project->meeting_dates = $request->meetings_dates_text;
	  }else{
		  $project->meeting_dates = 'NONE';
	  }
	
	  $project->evaluation_date_id = $request->evaluation_date;
	  
	  $project->presentation_results = $request->presentation_results;
	  

//    $project->integrated_areas = $request->integrated_areas;


    $project->meeting_info = $request->meeting_info;

    $project->study_term = $request->study_term;

    $project->study_year = $request->study_year;


    
//    $project->student_outcomes = $request->student_outcomes;
//


//    $project->courses = $request->related_courses;


//    $project->institute = $request->institutes;

//    if($request->project_start){
//      $date_start = date_create_from_format('m/d/Y', $request->project_start);
//      $project->start = date("Y-m-d", $date_start->getTimestamp());
//    }
//
//    if($request->project_end){
//      $date_end = date_create_from_format('m/d/Y', $request->project_end);
//      $project->end = date("Y-m-d", $date_end->getTimestamp());
//    }


    // Co-supervisors saved into supervisor column
    // Main supervisors linked in pivot table
    $project->supervisor = $request->cosupervisors;

    $project->status = 1;

    $project->tags = $request->tags;

//    $project->group_link = $request->group_link;


    $project->language = $request->language;

    $project->publishing_status = $request->publishing_status;


    $project->extra_info = $request->extra_info;


    $join_deadline = date_create_from_format('m/d/Y', $request->join_deadline);
    $project->join_deadline = date("Y-m-d", $join_deadline->getTimestamp());
	
	  $project->get_notifications = $request->get_notifications == "on"? true : false;


    //Need that to get id
    $project->save();

    if($request->featured_image != null){
      $project->featured_image = $this->uploadFeaturedImage($request, $project->id);
    }

    $project->save();


    //Attach study areas
//    $study_areas = $request->input('study_areas');
//    foreach ($study_areas as $study_area){
//
//      $project->getCourses()->attach($study_area);
//    }


    //Attach users with teacher role
    $supervisors = $request->input('supervisors');
    foreach ($supervisors as $supervisor){

      $project->users()->attach($supervisor, ['participation_role' => 'author']);
    }



    $projects = Project::whereHas('users', function($q)
    {
      $q->where('participation_role','LIKE','%author%')->where('id', Auth::user()->id);
    })->orderBy('created_at', 'desc')->paginate(5);
	
	
	  $this->newProjectAddedEmailNotification($project->name, Auth::user(), url('project/'.$project->id));


    return \Redirect::to('teacher/my-projects')
        ->with('message', trans('project.new_project_added_notification'))
        ->with('projects', $projects);



  }



  /**
   * Update project info
   */
  public function update(ProjectRequest $request, $id)
  {
	  

    $project = Project::find($id);
    $project->name = $request->name;
    $project->description = $request->description;


    if($request->embedded != null){
	    
      $embed = Embed::make($request->embedded)->parseUrl();

      if ($embed) {
        // Set width of the embed
        $embed->setAttribute(['width' => 600]);

      }

      $embed_html = $embed->getHtml();

      $project->embedded = $embed_html;

    }else{
	    $project->embedded = null;
    }
    
	  $project->aim = $request->aim;
	
	  $project->interdisciplinary_desc = $request->interdisciplinary_desc;
	
	  $project->novelty_desc = $request->novelty_desc;
	
	  $project->project_outcomes = $request->project_outcomes;
	
	  $project->student_expectations = $request->student_expectations;
	
	  if(!empty($request->meetings_dates_text)){
		  $project->meeting_dates = $request->meetings_dates_text;
	  }else{
		  $project->meeting_dates = 'NONE';
	  }
	
	  $project->evaluation_date_id = $request->evaluation_date;
	
	  $project->presentation_results = $request->presentation_results;
	  

    //XXX to be removed
//    $project->integrated_areas = $request->integrated_areas;



    $project->meeting_info = $request->meeting_info;

    //Attach study areas
//    $study_areas = $request->input('study_areas');
//    $project->getCourses()->sync($study_areas);


    $project->study_term = $request->study_term;

    $project->study_year = $request->study_year;



//    $project->project_outcomes = $request->project_outcomes;
//    $project->student_outcomes = $request->student_outcomes;
//

    //XXX to be removed
//    $project->courses = $request->related_courses;


//    $project->institute = $request->institutes;
//
//
//    if($request->project_start){
//      $date_start = date_create_from_format('m/d/Y', $request->project_start);
//      $project->start = date("Y-m-d", $date_start->getTimestamp());
//    }
//
//    if($request->project_end){
//      $date_end = date_create_from_format('m/d/Y', $request->project_end);
//      $project->end = date("Y-m-d", $date_end->getTimestamp());
//    }


    $project->supervisor = $request->cosupervisors;

    $project->status = $request->status;

    $project->tags = $request->tags;

//    $project->group_link = $request->group_link;

    $project->language = $request->language;

    $project->publishing_status = $request->publishing_status;


    $project->extra_info = $request->extra_info;


    $join_deadline = date_create_from_format('m/d/Y', $request->join_deadline);
    $project->join_deadline = date("Y-m-d", $join_deadline->getTimestamp());

    $project->requires_review = false;
	
	  $project->get_notifications = $request->get_notifications == "on"? true : false;



    if($request->featured_image != null){
      if($project->featured_image !=null){

        File::delete(public_path('storage/projects_featured_images/') .$project->featured_image);
      }
      $project->featured_image = $this->uploadFeaturedImage($request, $project->id);
    }

    $project->save();



    //Detaching teachers
    $teachers = $project->users()->wherePivot('participation_role', 'author')->get();

    if(count($teachers)){
      $project->users()->detach($teachers);
    }



    //Attach users with teacher role
    $supervisors = $request->input('supervisors');
    foreach ($supervisors as $supervisor){

      $project->users()->attach($supervisor, ['participation_role' => 'author']);
    }


    $projects = Project::whereHas('users', function($q)
    {
      $q->where('participation_role','LIKE','%author%')->where('id', Auth::user()->id);
    })->orderBy('created_at', 'desc')->paginate(5);


    return \Redirect::to('project/'.$project->id)
		    ->with('message', [
				    'text' => trans('project.project_changed_notification', ['name' => $project->name]),
				    'type' => 'changed'
		    ])
        ->with('projects', $projects);

  }


  /**
   * Search published projects by
   * author
   * member
   * title, description, extra info
   *
   * @param Request $request
   * @return mixed
   */
  private function searchPublishedProjects(Request $request){

    $name = $request->search;
    $param = $request->search_param;


    if($param == 'author'){

      $projects = Project::where('publishing_status', 1)
		      ->where(function ($query) use ($name) {
			      $query->whereHas('users', function($q) use ($name)
			      {
				      $q->where(function($subq) use ($name) {
					      $subq->where('name', 'LIKE', '%'.$name.'%')
							      ->orWhere('full_name', 'LIKE', '%'.$name.'%');
				      })->where('participation_role','LIKE','%author%');
			      });
			      $query->orWhere('supervisor', 'LIKE', '%'.$name.'%');
		      })
          ->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);


    }elseif ($param == 'member'){

      $projects = Project::whereHas('users', function($q) use ($name)
      {
        $q->where(function($subq) use ($name) {
          $subq->where('name', 'LIKE', '%'.$name.'%')
              ->orWhere('full_name', 'LIKE', '%'.$name.'%');
        })->where('participation_role','LIKE','%member%');
      })->where('publishing_status', 1)->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);


    }else{
      $projects = Project::where('publishing_status', 1)
          ->where(function ($query) use ($name) {
            $query->where('name', 'LIKE', '%'.$name.'%');
            $query->orWhere('tags', 'LIKE', '%'.$name.'%');
            $query->orWhere('description', 'LIKE', '%'.$name.'%');
            $query->orWhere('extra_info', 'LIKE', '%'.$name.'%');
          })->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);

    }

    return $projects;
  }


  /**
   * Search open projects by
   * author
   * member
   * title, description, extra info
   *
   * @param Request $request
   * @return mixed
   */
  private function searchOpenProjects(Request $request){

    $name = $request->search;
    $param = $request->search_param;


    if($param == 'author'){

      $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '>=', Carbon::today()->format('Y-m-d'))
		      ->where(function ($query) use ($name) {
			      $query->whereHas('users', function($q) use ($name)
			      {
				      $q->where(function($subq) use ($name) {
					      $subq->where('name', 'LIKE', '%'.$name.'%')
							      ->orWhere('full_name', 'LIKE', '%'.$name.'%');
				      })->where('participation_role','LIKE','%author%');
			      });
			      $query->orWhere('supervisor', 'LIKE', '%'.$name.'%');
		      })
          ->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);


    }elseif ($param == 'member'){

      $projects = Project::whereHas('users', function($q) use ($name)
      {
        $q->where(function($subq) use ($name) {
          $subq->where('name', 'LIKE', '%'.$name.'%')
              ->orWhere('full_name', 'LIKE', '%'.$name.'%');
        })->where('participation_role','LIKE','%member%');
      })->where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '>=', Carbon::today()->format('Y-m-d'))->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);


    }else{
      $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '>=', Carbon::today()->format('Y-m-d'))
          ->where(function ($query) use ($name) {
            $query->where('name', 'LIKE', '%'.$name.'%');
            $query->orWhere('tags', 'LIKE', '%'.$name.'%');
            $query->orWhere('description', 'LIKE', '%'.$name.'%');
            $query->orWhere('extra_info', 'LIKE', '%'.$name.'%');
          })->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);

    }

    return $projects;
  }



  /**
   * Search ongoing projects by
   * author
   * member
   * title, description, extra info
   *
   * @param Request $request
   * @return mixed
   */
  private function searchOngoingProjects(Request $request){

    $name = $request->search;
    $param = $request->search_param;


    if($param == 'author'){
	    
	    $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))
			    ->where(function ($query) use ($name) {
				    $query->whereHas('users', function($q) use ($name)
				    {
					    $q->where(function($subq) use ($name) {
						    $subq->where('name', 'LIKE', '%'.$name.'%')
								    ->orWhere('full_name', 'LIKE', '%'.$name.'%');
					    })->where('participation_role','LIKE','%author%');
				    });
				    $query->orWhere('supervisor', 'LIKE', '%'.$name.'%');
			    })
			    ->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);

    }elseif ($param == 'member'){

      $projects = Project::whereHas('users', function($q) use ($name)
      {
        $q->where(function($subq) use ($name) {
          $subq->where('name', 'LIKE', '%'.$name.'%')
              ->orWhere('full_name', 'LIKE', '%'.$name.'%');
        })->where('participation_role','LIKE','%member%');
      })->where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);


    }else{
      $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))
          ->where(function ($query) use ($name) {
            $query->where('name', 'LIKE', '%'.$name.'%');
            $query->orWhere('tags', 'LIKE', '%'.$name.'%');
            $query->orWhere('description', 'LIKE', '%'.$name.'%');
            $query->orWhere('extra_info', 'LIKE', '%'.$name.'%');
          })->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);

    }

    return $projects;
  }



  /**
   * Search finished projects by
   * author
   * member
   * title, description, extra info
   *
   * @param Request $request
   * @return mixed
   */
  private function searchFinishedProjects(Request $request){

    $name = $request->search;
    $param = $request->search_param;


    if($param == 'author'){

      $projects = Project::where('publishing_status', 1)->where('status', '=', '0')
		      ->where(function ($query) use ($name) {
			      $query->whereHas('users', function($q) use ($name)
			      {
				      $q->where(function($subq) use ($name) {
					      $subq->where('name', 'LIKE', '%'.$name.'%')
							      ->orWhere('full_name', 'LIKE', '%'.$name.'%');
				      })->where('participation_role','LIKE','%author%');
			      });
			      $query->orWhere('supervisor', 'LIKE', '%'.$name.'%');
		      })
          ->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);


    }elseif ($param == 'member'){

      $projects = Project::whereHas('users', function($q) use ($name)
      {
        $q->where(function($subq) use ($name) {
          $subq->where('name', 'LIKE', '%'.$name.'%')
              ->orWhere('full_name', 'LIKE', '%'.$name.'%');
        })->where('participation_role','LIKE','%member%');
      })->where('publishing_status', 1)->where('status', '=', '0')->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);


    }else{
      $projects = Project::where('publishing_status', 1)->where('status', '=', '0')
          ->where(function ($query) use ($name) {
            $query->where('name', 'LIKE', '%'.$name.'%');
            $query->orWhere('tags', 'LIKE', '%'.$name.'%');
            $query->orWhere('description', 'LIKE', '%'.$name.'%');
            $query->orWhere('extra_info', 'LIKE', '%'.$name.'%');
          })->orderBy('name', 'asc')->paginate(20)->appends(['search' => $name, 'search_param' => $param]);

    }

    return $projects;
  }



  /**
   * Get open projects search
   */
  public function getOpenProjectsSearch(Request $request)
  {

    $name = $request->search;
    $param = $request->search_param;


    if(Auth::user()){
      return view('project.search')
          ->with('name', $name)
          ->with('param', $param)
          ->with('projects', $this->searchOpenProjects($request))
          ->with('isTeacher', Auth::user()->is('oppejoud'));

    }else{
      return view('project.search')
          ->with('name', $name)
          ->with('param', $param)
          ->with('projects', $this->searchOpenProjects($request))
          ->with('isTeacher', false);
    }

  }


  /**
   * Get ongoing projects search
   */
  public function getOngoingProjectsSearch(Request $request)
  {

    $name = $request->search;
    $param = $request->search_param;


    if(Auth::user()){
      return view('project.search')
          ->with('name', $name)
          ->with('param', $param)
          ->with('projects', $this->searchOngoingProjects($request))
          ->with('isTeacher', Auth::user()->is('oppejoud'));

    }else{
      return view('project.search')
          ->with('name', $name)
          ->with('param', $param)
          ->with('projects', $this->searchOngoingProjects($request))
          ->with('isTeacher', false);
    }

  }


  /**
   * Get finished projects search
   */
  public function getFinishedProjectsSearch(Request $request)
  {

    $name = $request->search;
    $param = $request->search_param;


    if(Auth::user()){
      return view('project.search')
          ->with('name', $name)
          ->with('param', $param)
          ->with('projects', $this->searchFinishedProjects($request))
          ->with('isTeacher', Auth::user()->is('oppejoud'));

    }else{
      return view('project.search')
          ->with('name', $name)
          ->with('param', $param)
          ->with('projects', $this->searchFinishedProjects($request))
          ->with('isTeacher', false);
    }

  }


  /**
   * Search published and unpublished projects by
   * author
   * member
   * title, description, extra info
   *
   * @param Request $request
   * @return mixed
   */
  private function searchAllProjects(Request &$request)
  {
    $name = $request->search;
    $param = $request->search_param;

    if($param == 'author'){

      $projects = Project::where(function ($query) use ($name) {
	      $query->whereHas('users', function($q) use ($name)
	      {
		      $q->where(function($subq) use ($name) {
			      $subq->where('name', 'LIKE', '%'.$name.'%')
					      ->orWhere('full_name', 'LIKE', '%'.$name.'%');
		      })->where('participation_role','LIKE','%author%');
	      });
	      $query->orWhere('supervisor', 'LIKE', '%'.$name.'%');
      })->orderBy('name', 'asc')->paginate(10)->appends(['search' => $name, 'search_param' => $param]);


    }elseif ($param == 'member'){

      $projects = Project::whereHas('users', function($q) use ($name)
      {
        $q->where(function($subq) use ($name) {
          $subq->where('name', 'LIKE', '%'.$name.'%')
              ->orWhere('full_name', 'LIKE', '%'.$name.'%');
        })->where('participation_role','LIKE','%member%');
      })->orderBy('name', 'asc')->paginate(10)->appends(['search' => $name, 'search_param' => $param]);


    }else{
      $projects = Project::where(function ($query) use ($name) {
        $query->where('name', 'LIKE', '%'.$name.'%');
        $query->orWhere('tags', 'LIKE', '%'.$name.'%');
        $query->orWhere('description', 'LIKE', '%'.$name.'%');
        $query->orWhere('extra_info', 'LIKE', '%'.$name.'%');
      })->orderBy('name', 'asc')->paginate(10)->appends(['search' => $name, 'search_param' => $param]);
    }


    return $projects;

  }


  /**
   * Search the admin listing of projects
   */
  public function getAllProjectsSearch(Request $request)
  {

    return view('admin.all_projects')
        ->with('name', $request->search)
        ->with('param', $request->search_param)
        ->with('projects', $this->searchAllProjects($request));
  }


  /**
   * Join project team is used by user with student role
   */
  public function joinProject($id)
  {
  	
  	
    $project = Project::find($id);
	
	  if(Auth::user()->isMemberOfProject()){
		
		  return \Redirect::to('project/'.$project->id)
				  ->with('message', [
						  'text' => trans('project.already_in_team_notification', ['name' => Auth::user()->isMemberOfProject()]['name']),
						  'type' => 'already_in_project'
				  ])
				  ->with('project', $project);
		
	  }
	  
	 
    if( count($project->groups) >= 3){
    	//All groups created
	
	    //Find a place
    	foreach ($project->groups as $key=>$group){
    		if(count($group->users) <= 7){
    			if(checkIfCourseOfThisUserIsAcceptable($group, Auth::user())){
				
    				
				    $group->users()->syncWithoutDetaching([Auth::user()->id]);
				    break;
    				
			    }else{
    				if($key === count($project->groups)-1){
    					//last group
					    //cannot join
					    return \Redirect::to('project/'.$project->id)
							    ->with('message', [
									    'text' => trans('project.declined_project_join_notification_max_courses_limit').': "'.$project->name.'"',
									    'type' => 'declined'
							    ])
							    ->with('project', $project);
				    }
			    }
    		
    		
		    }else{
			    if($key === count($project->groups)-1){
			    	//last group
				    //cannot join
				    return \Redirect::to('project/'.$project->id)
						    ->with('message', [
								    'text' => trans('project.declined_project_join_notification_max_members_limit').': "'.$project->name.'"',
								    'type' => 'declined'
						    ])
						    ->with('project', $project);
			    }
		    }
	    }


  
    
    }elseif (count($project->groups) > 0 && count($project->groups) <= 2){
    	//1 or 2 groups
	
	    //Find a place
	    foreach ($project->groups as $key=>$group){
	    	if(count($group->users) <= 7){
			    if(checkIfCourseOfThisUserIsAcceptable($group, Auth::user())){
				    $group->users()->syncWithoutDetaching([Auth::user()->id]);
				    break;
			    }else{
				    if($key === count($project->groups)-1){
				    	//last group
					    //make a new one
					    $new_group = new Group;
					    $new_group->name = count($project->groups)+1;
					    $new_group->project_id = $project->id;
					    $new_group->save();
					    $new_group->users()->syncWithoutDetaching([Auth::user()->id]);
				    }
			    	
			    	
			    }
			    
	    		
		    }else{
	    		if($key === count($project->groups)-1){
				    //last group
				    //make a new one
				    $new_group = new Group;
				    $new_group->name = count($project->groups)+1;
				    $new_group->project_id = $project->id;
				    $new_group->save();
				    $new_group->users()->syncWithoutDetaching([Auth::user()->id]);
	    			
			    }
		    }
	    	
	    }
    
    
    
    } else {
    	//0 groups
	    //make group
	    $new_group = new Group;
	    $new_group->name = 1;
	    $new_group->project_id = $project->id;
	    $new_group->save();
	
	    $new_group->users()->syncWithoutDetaching([Auth::user()->id]);
    }
    
    
	  
    
    

    //Attach user with member role
    $project->users()->attach(Auth::user()->id, ['participation_role' => 'member']);
	  
	  if($project->get_notifications){
		  $data = [
				  'new_member' => getUserNameAndCourse(Auth::user()),
				  'project_name' => $project->name,
				  'project_url' => url('project/'.$project->id),
		  ];
		  $project_authors_emails = array();
		  foreach ($project->users as $user){
			
			  if($user->pivot->participation_role == 'author'){
				  array_push($project_authors_emails, getUserEmail($user));
				
			  }
		  }
		  
		  Mail::send('emails.joined_project_notification', ['data' => $data], function ($m) use ($project_authors_emails) {
			  $m->to($project_authors_emails)->subject('Uus projekti liige / New project member');
//			$m->cc($admins_emails)->subject('Uus projektiidee');
		  });
	  }
	
	 


    return \Redirect::to('project/'.$project->id)
        ->with('message', [
            'text' => trans('project.joined_project_notification').' "'.$project->name.'"',
            'type' => 'joined'
        ])
        ->with('project', $project);

  }

  private static function getUserName(User $user){

    if(!empty($user->full_name)){
      return $user->full_name;
    }else{
      return $user->name;
    }
  }


  /**
   * Attach user to project manually (used by admin)
   */
  public function attachUsersToProject($id, AttachUsersRequest $request)
  {

    $project = Project::find($id);
    $names = '';

    //Attach users with teacher role
    $users = $request->input('attached-users');
    foreach ($users as $user) {


      if ($user === end($users)){
        $names .= self::getUserName(User::find($user));
      }else{
        //Attach user with member role
        $names .= self::getUserName(User::find($user)) . ', ';
      }

      $project->users()->attach($user, ['participation_role' => 'member']);
    }



    return \Redirect::to('project/'.$project->id.'/edit')
        ->with('message', trans('project.students_attached_notification').$names);
  }


  /**
   * Creates new project group
   * @param Request $request
   * @return mixed
   */
  public function addNewProjectGroup($id, AddProjectGroupRequest $request)
  {

    $project = Project::find($id);

    $group = new Group;
    $group->name = $request->name;
    $group->project_id = $project->id;

    $group->save();





    return \Redirect::to('project/'.$project->id.'/edit')
        ->with('message', trans('project.group_created_notification', ['name' => $group->name]));
  }


  public function deleteProjectGroup($project_id, $group_id)
  {
    $group = Group::findOrFail($group_id);

    $name = $group->name;
    $group->delete();

    return redirect('project/'.$project_id.'/edit')->with('message', trans('project.group_deleted_notification', ['name' => $group->name]));
  }
	
	
	public function renameProjectGroup(Request $request)
	{
		$group = Group::findOrFail($request->pk);
		
		$group->name = $request->value;
		$group->save();
		
		return Response::json('ok', 200);
	}
	

  /**
   * Leave project team is used by user with student role
   */
  public function leaveProject($id)
  {
    $project = Project::find($id);

    $project->users()->detach(Auth::user()->id);

    $projects = Project::whereHas('users', function($q)
    {
      $q->where('participation_role','LIKE','%member%')->where('id', Auth::user()->id);
    })->orderBy('created_at', 'desc')->paginate(5);


    return \Redirect::to('student/my-projects')
        ->with('message', [
            'text' => trans('project.left_project_notification').' "'.$project->name.'"',
            'type' => 'left'
        ]);

  }


  /**
   * Unlink member from project team is used by user with teacher role
   */
  public function unlinkMember($projectId, $userId)
  {
    $project = Project::find($projectId);

    $project->users()->detach($userId);

    $user = User::find($userId);
	
	
	  $user_group =  userBelongsToGroup($user);
	
	  if($user_group){
		  $user_group->first()->users()->detach($user->id);
	  }
	  

    if(!empty($user->full_name)){
      return redirect()->route('project_edit', ['id' => $project->id])
          ->with('message', 'Oled '.$user->full_name.' projektist '.$project->name.' kustutanud.');
    }else{
      return redirect()->route('project_edit', ['id' => $project->id])
          ->with('message', 'Oled '.$user->name.' projektist '.$project->name.' kustutanud.');
    }

  }


  /**
   * Store project proposal by student
   * This goes to moderation
   */
  public function storeProjectByStudent(ProjectByStudentRequest $request)
  {

    $project = new Project;
    $project->name = $request->name;

    $project->description = $request->description;
	
	  $project->aim = $request->aim;
	
	  $project->interdisciplinary_desc = $request->interdisciplinary_desc;
	
	  $project->novelty_desc = $request->novelty_desc;
	
	  $project->project_outcomes = $request->project_outcomes;
	
	  $project->student_expectations = $request->student_expectations;
	  
	  $project->author_management_skills = $request->author_management_skills;
	  
//    $project->integrated_areas = $request->integrated_areas;

    $project->study_term = $request->study_term;

//    $project->institute = $request->institutes;

    $project->supervisor = $request->cosupervisors;
	
	  $project->tags = $request->tags;
	  
	  
    $project->publishing_status = 0;

    $project->study_year = $request->study_year;

    $project->extra_info = $request->extra_info;
	
	  $project->status = 1;

    $project->submitted_by_student = true;
	
	  $project->requires_review = true;
	  

    $project->save();

    //Attach study areas
//    $study_areas = $request->input('study_areas');
//    foreach ($study_areas as $study_area){
//
//      $project->getCourses()->attach($study_area);
//    }

    $project->users()->attach(Auth::user()->id, ['participation_role' => 'member']);
	
	  
	  $this->newProjectIdeaAddedEmailNotification($project->name, Auth::user(), url('project/'.$project->id.'/edit'));
	  
	  
    return \Redirect::to('projects/open')
        ->with('message', [
            'text' => trans('project.project_sent_to_moderation_notification', ['name' => $project->name]),
            'type' => 'proposal'
        ]);

  }
	
	
	public function newProjectIdeaAddedEmailNotification($project, $author, $url)
	{
	
		$data = [
				'project_name' => $project,
				'project_author' => self::getUserName($author),
				'project_url' => $url,
		];
		
		$admins =  User::whereHas(
				'roles', function($q){
			$q->where('name', 'admin');
		}
		)->get();
		
		$superadmins =  User::whereHas(
				'roles', function($q){
			$q->where('name', 'superadmin');
		}
		)->get();
		
		//Remove superadmins from the list
		foreach ($admins as $key=>$admin){
			foreach ($superadmins as $superadmin){
				if($admin->id == $superadmin->id){
					unset($admins[$key]);
				}
			}
		}
	
		$admins_emails = array();
		
		foreach ($admins as $admin){
			array_push($admins_emails, getUserEmail($admin));
		}
		
		
		Mail::send('emails.project_idea_notification', ['data' => $data], function ($m) use ($admins_emails) {
			$m->to($admins_emails)->subject('Uus projektiidee');
//			$m->cc($admins_emails)->subject('Uus projektiidee');
		});
		
	
		
		Mail::send('emails.project_idea_student_confirmation', ['data' => $data], function ($m) use ($author) {
			$m->to($author->email)->subject('Projektiidee on lisatud / Project idea has been added');
		});
		
		
	}
	
	
	public function newProjectAddedEmailNotification($project, $author, $url)
	{
		
		$data = [
				'project_name' => $project,
				'project_author' => self::getUserName($author),
				'project_url' => $url,
		];
		
		$admins =  User::whereHas(
				'roles', function($q){
			$q->where('name', 'admin');
		}
		)->get();
		
		$superadmins =  User::whereHas(
				'roles', function($q){
			$q->where('name', 'superadmin');
		}
		)->get();
		
		//Remove superadmins from the list
		foreach ($admins as $key=>$admin){
			foreach ($superadmins as $superadmin){
				if($admin->id == $superadmin->id){
					unset($admins[$key]);
				}
			}
		}
		
		$admins_emails = array();
		
		foreach ($admins as $admin){
			array_push($admins_emails, getUserEmail($admin));
		}
		
		
		Mail::send('emails.new_project_notification', ['data' => $data], function ($m) use ($admins_emails) {
			$m->to($admins_emails)->subject('Uus projekt');
//			$m->cc($admins_emails)->subject('Uus projektiidee');
		});
		
		
	}


  /**
   * Admin analytics of projects view
   */
  public function indexAnalytics()
  {

    $projects = Project::where('publishing_status', '=', '1')->orderBy('name', 'asc')->paginate(20);


    $open_projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '>=', Carbon::today()->format('Y-m-d'))->count();

    $ongoing_projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->count();
	
	  $finished_projects = Project::where('publishing_status', 1)->where('status', '=', '0')->orderBy('name', 'asc')->count();

    return view('admin.analytics')
        ->with('projects', $projects)
        ->with('published_projects_count', $open_projects+$ongoing_projects+$finished_projects)
		    ->with('open_projects_count', $open_projects)
		    ->with('ongoing_projects_count', $ongoing_projects)
		    ->with('finished_projects_count', $finished_projects)
        ->with('users_count', User::count());

  }


  /**
   * Search the admin analytics listing of projects
   */
  public function getAdminAnalyticsListing(Request $request)
  {

    return view('admin.analytics')
        ->with('name', $request->search)
        ->with('param', $request->search_param)
        ->with('projects', $this->searchPublishedProjects($request))
        ->with('projects_count', Project::where('publishing_status', '=', '1')->count())
        ->with('users_count', User::count());
  }


  private function getProjectAuthorsNamesAndEmails(Project $project){
    $authors = array();
    foreach ($project->users as $user){

      if($user->pivot->participation_role == 'author'){
        if(!empty($user->full_name)){

          array_push($authors, $user->full_name.' ('.$user->email.')');
        }else{
          array_push($authors, $user->name.' ('.$user->email.')');
        }

      }
    }

    return $authors;
  }


  private function getProjectCosupervisors(Project $project){
    $cosupervisors = array();
    foreach (preg_split("/\\r\\n|\\r|\\n/", $project->supervisor) as $single_cosupervisor) {

      array_push($cosupervisors, $single_cosupervisor);

    }
    return $cosupervisors;
  }


  private function getProjectMembersData(Project $project){
    $members = array();
    foreach ($project->users as $user){

      if($user->pivot->participation_role == 'member'){
        if(!empty($user->full_name)){


          if(!$user->courses->isEmpty()){
            $course = $user->courses->first();
            array_push($members, $user->full_name.' / '.$course->oppekava_est.' ('.$user->email.')');

          }else{

            array_push($members, $user->full_name.' ('.$user->email.')');
          }
        }else{
          array_push($members, $user->name.' ('.$user->email.')');
        }

      }
    }


    return $members;
  }


  private static function arrayToImplodeString(array $data){

    return implode(', ', $data);
  }
	
	/**
	 * Get open projects statistics in form of csv file
	 */
	public function exportAnalyticsToCSVStudentsOngoingProjects()
	{


		$headers = array(
				"Content-type" => "text/csv",
				"Content-Disposition" => "attachment; filename=elu_students_ongoing.csv",
				"Pragma" => "no-cache",
				"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
				"Expires" => "0"
		);


		$projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->orderBy('name', 'asc')->get();

		$students_ids = array();

		foreach ($projects as $project){
			$members = $project->users()->select('id')->wherePivot('participation_role', 'member')->get();
			if(count($members)>0){
				foreach ($members as $member){
					array_push($students_ids, $member->id);
				}
				
			}
			
		}
		


		$columns = array(trans('auth.name'), trans('auth.email'), trans('project.course'), trans('project.project'), trans('project.supervisor'), trans('project.cosupervisor'));


		$callback = function() use ($students_ids, $columns)
		{
			$handle = fopen('php://output', 'w');
			fputcsv($handle, $columns);


			foreach ($students_ids as $student_id){
				$user = User::find($student_id);
				$project = Project::find($user->isMemberOfProject()['id']);
				$authors = $this->getProjectAuthorsNamesAndEmails($project);
				$cosupervisors = $this->getProjectCosupervisors($project);
				
				
				fputcsv($handle, array(self::getUserName($user), $user->email, getUserCourse($user), $user->isMemberOfProject()['name'], self::arrayToImplodeString($authors), self::arrayToImplodeString($cosupervisors)), ',');

			}
			
		};


		return Response::stream($callback, 200, $headers);
	}
 
	/**
	 * Get open projects statistics in form of csv file
	 */
	public function exportAnalyticsToCSVOpenProjects()
	{
		
		
		$headers = array(
				"Content-type" => "text/csv",
				"Content-Disposition" => "attachment; filename=elu_open.csv",
				"Pragma" => "no-cache",
				"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
				"Expires" => "0"
		);
		
		
		$projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '>=', Carbon::today()->format('Y-m-d'))->orderBy('name', 'asc')->get();
		
		$columns = array(trans('project.project'), trans('project.study_year'), trans('project.duration'), trans('project.supervisor'), trans('project.cosupervisor'), trans('search.team'), 'Õpilaste arv');
		
		
		$callback = function() use ($projects, $columns)
		{
			$handle = fopen('php://output', 'w');
			fputcsv($handle, $columns);
			
			foreach($projects as $project) {
				
				$authors = $this->getProjectAuthorsNamesAndEmails($project);
				$members = $this->getProjectMembersData($project);
				$cosupervisors = $this->getProjectCosupervisors($project);
				
				fputcsv($handle, array($project->name, $project->study_year, getProjectSemester($project), self::arrayToImplodeString($authors), self::arrayToImplodeString($cosupervisors), self::arrayToImplodeString($members), count($members)), ',');
			}
			
			
			fclose($handle);
		};
		
		
		return Response::stream($callback, 200, $headers);
	}

  /**
   * Get ongoing projects statistics in form of csv file
   */
  public function exportAnalyticsToCSVOngoingProjects()
  {


    $headers = array(
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=elu_ongoing.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    );


    $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->orderBy('name', 'asc')->get();

    $columns = array(trans('project.project'),  trans('project.study_year'), trans('project.duration'), trans('project.supervisor'), trans('project.cosupervisor'), trans('search.team'), 'Õpilaste arv');


    $callback = function() use ($projects, $columns)
    {
      $handle = fopen('php://output', 'w');
      fputcsv($handle, $columns);

      foreach($projects as $project) {

        $authors = $this->getProjectAuthorsNamesAndEmails($project);
        $members = $this->getProjectMembersData($project);
        $cosupervisors = $this->getProjectCosupervisors($project);

        fputcsv($handle, array($project->name, $project->study_year, getProjectSemester($project), self::arrayToImplodeString($authors), self::arrayToImplodeString($cosupervisors), self::arrayToImplodeString($members), count($members)), ',');
      }


      fclose($handle);
    };
	  

    return Response::stream($callback, 200, $headers);
  }
	
	
	/**
	 * Get finished projects statistics in form of csv file
	 */
	public function exportAnalyticsToCSVFinishedProjects()
	{
		
		
		$headers = array(
				"Content-type" => "text/csv",
				"Content-Disposition" => "attachment; filename=elu_finished.csv",
				"Pragma" => "no-cache",
				"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
				"Expires" => "0"
		);
		
		
		$projects = Project::where('publishing_status', 1)->where('status', '=', '0')->orderBy('name', 'asc')->get();
		
		$columns = array(trans('project.project'), trans('project.study_year'), trans('project.duration'), trans('project.supervisor'), trans('project.cosupervisor'), trans('search.team'), 'Õpilaste arv');
		
		
		$callback = function() use ($projects, $columns)
		{
			$handle = fopen('php://output', 'w');
			fputcsv($handle, $columns);
			
			foreach($projects as $project) {
				
				$authors = $this->getProjectAuthorsNamesAndEmails($project);
				$members = $this->getProjectMembersData($project);
				$cosupervisors = $this->getProjectCosupervisors($project);
				
				fputcsv($handle, array($project->name, $project->study_year, getProjectSemester($project), self::arrayToImplodeString($authors), self::arrayToImplodeString($cosupervisors), self::arrayToImplodeString($members), count($members)), ',');
			}
			
			
			fclose($handle);
		};
		
		
		return Response::stream($callback, 200, $headers);
	}


  /**
   * Search user api
   */
  public function searchUser(Request $request){

    $user = $request->q;

    $project_id = $request->project_id;


    //Get users that are not members of this project
    $users = User::where(function ($query) use ($user, $project_id) {
      $query->where('name', 'LIKE', '%'.$user.'%')
          ->whereNotIn('id', function ($query) use ($project_id)
          {
            $query->select('user_id')
                ->from('project_user')
                ->where('project_id', '=', $project_id);
          });
    })->orWhere(function($query) use ($user, $project_id) {
      $query->where('full_name', 'LIKE', '%'.$user.'%')
          ->whereNotIn('id', function ($query) use ($project_id)
          {
            $query->select('user_id')
                ->from('project_user')
                ->where('project_id', '=', $project_id);
          });
    })->orWhere(function($query) use ($user, $project_id) {
      $query->where('email', 'LIKE', '%'.$user.'%')
          ->whereNotIn('id', function ($query) use ($project_id)
          {
            $query->select('user_id')
                ->from('project_user')
                ->where('project_id', '=', $project_id);
          })
      ;
    })->orWhere(function($query) use ($user, $project_id) {
	    $query->where('contact_email', 'LIKE', '%'.$user.'%')
			    ->whereNotIn('id', function ($query) use ($project_id)
			    {
				    $query->select('user_id')
						    ->from('project_user')
						    ->where('project_id', '=', $project_id);
			    })
	    ;
    })->get();


    return Response::json($users);

  }


  /**
   * Add user to group api
   */
  public function addUserToGroup(Request $request){

    $user_id = $request->user;

    $from_group = null;

    if($request->from != 'project_all_members'){
      $from_group = Group::find($request->from);
    }



    $to_group = Group::find($request->to);



    if($from_group){
      \Debugbar::info($request->user);
      $from_group->users()->detach($user_id);
    }


    $to_group->users()->syncWithoutDetaching([$user_id]);

    return Response::json('Groups saved');
  }
	
	
	/**
	 * Get supervisors load for project api
	 */
//	public function getSupervisorsLoadForProject(Request $request){
//
//		$project_id = $request->project;
//
//
//		return Response::json('Groups saved');
//	}
	
	public function getSupervisorsLoadForProject($id){
		
		$project = Project::find($id);
		
		$supervisors = array();
		$members_count = 0;
		$total_points = 0;
		$limit_per_one = 0;
		$isFirstTimeSupervisor = false;
		
		
		foreach ($project->users as $user){
			if($user->pivot->participation_role == 'author'){
				array_push($supervisors, ['id' => $user->id, 'name' => self::getUserName($user), 'points' => 0]);
			}elseif ($user->pivot->participation_role == 'member'){
				$members_count++;
			}
		}
		
		
		
		foreach ($supervisors as $supervisor){
			if(count(getTeacherProjects(User::find($supervisor['id'])))<=1){
				$isFirstTimeSupervisor = true;
			}
		}
		
		if($members_count <= 8){
			$total_points = 3;
			$limit_per_one = 2;
		}else if($members_count <= 17){
			$total_points = 6;
			$limit_per_one = 4;
		}else if($members_count <= 24 || $isFirstTimeSupervisor){
			$total_points = 9;
			$limit_per_one = 6;
		}else if($members_count <= 32){
			$total_points = 12;
			$limit_per_one = 8;
		}
		
		
		return view('project.load_calc')
				->with('total_points', $total_points)
				->with('limit_per_one', $limit_per_one)
				->with('supervisors', $supervisors);
	}


  /**
   * Finish project button handler
   */
  public function finishProject($id)
  {


    $project = Project::find($id);
    $project->status = 0;

    $project->save();

    return view('project.finish')
        ->with('current_project', $project);

  }

  public function attachGroupGalleryImages(Request $request) {

    $group = Group::find($request->group_id);

    $input = Input::all();

    $rules = array(
        'file' => 'image|max:3000',
    );

    $validation = Validator::make($input, $rules);

    if ($validation->fails()) {
      return Response::make(trans('errors.not_image'), 400);
    }



    if(!empty(json_decode($group->images, true))){
      $images = json_decode($group->images, true);
      $new_image = $this->uploadGroupGalleryImage(Input::file('file'), $group->id);
      if($new_image){
        array_push($images, $new_image);
      }

    }else{
      $images = array($this->uploadGroupGalleryImage(Input::file('file'), $group->id));
      $new_image = $images;
    }




    $group->images = json_encode($images);

    $group->save();



//
//    $destinationPath = 'storage/projects_groups_images'; // upload path
//    $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension
//    $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
//    $upload_success = Input::file('file')->move($destinationPath, $fileName); // uploading file to given path

    if ($images) {
      return Response::json(['newfilename' => $new_image]);

    } else {
      return Response::json('error', 400);
    }
  }


  public function deleteFile(Request $request){



    $image = $request->name;

    $group = Group::find($request->group_id);



    $images = json_decode($group->images, true);

    if(($key = array_search($image, $images)) !== false) {
      unset($images[$key]);
    }


    $group->images = json_encode($images);

    $group->save();



    File::delete(public_path().'/storage/projects_groups_images/'.$group->id.'/'.$image);

    return Response::json([$image, $images]);




  }


  /**
   * Get images related to group api
   */
  public function getGroupImages(Request $request){



    $group = Group::find($request->query('groupid'));


    $imageAnswer = [];

    if(!empty(json_decode($group->images, true))){
      foreach (json_decode($group->images, true) as $image){

        $imageAnswer[] = [
            'name' => $image,
            'size' => File::size(public_path().'/storage/projects_groups_images/'.$group->id.'/'.$image)
        ];
      }
    }





    return response()->json([
        'images' => $imageAnswer
    ]);
  }



  public function saveFinishedProject(FinishedProjectRequest $request, $id){

    $project = Project::find($id);
    $project->summary = $request->summary;

    $project->save();

    if(count($project->groups)>0){
      foreach ($project->groups as $group){



        //Embedded link
        $embedded = null;
        if($request->input('group_embedded.'.$group->id) != null){

          $embed = Embed::make($request->input('group_embedded.'.$group->id))->parseUrl();

          if ($embed) {
            // Set width of the embed
            $embed->setAttribute(['width' => 600]);

          }

          $embed_html = $embed->getHtml();

          $embedded = $embed_html;

        }else{
	        $embedded = null;
        }


        $group->results = ($request->input('group_results.'.$group->id)) != null ? $request->input('group_results.'.$group->id) : null;
        $group->activities = ($request->input('group_activities.'.$group->id)) != null ? $request->input('group_activities.'.$group->id) : null;
        $group->reflection = ($request->input('group_reflection.'.$group->id)) !=null ? $request->input('group_reflection.'.$group->id) : null;
        $group->partners = ($request->input('group_partners.'.$group->id)) !=null ? $request->input('group_partners.'.$group->id) :null;
        $group->students_opinion = ($request->input('group_students_opinion.'.$group->id)) != null ? $request->input('group_students_opinion.'.$group->id) : null;
        $group->supervisor_opinion = ($request->input('group_supervisor_opinion.'.$group->id)) !=null ? $request->input('group_supervisor_opinion.'.$group->id) : null;
        $group->embedded = $embedded;


        $group->save();

        $materials_names = $request->input('group_material_name.'.$group->id);
        $materials_links = $request->input('group_material_link.'.$group->id);
        $materials_tags = $request->input('group_material_tags.'.$group->id);


        //Delete existing records to override them
        if(count($group->materials)>0){

          foreach ($group->materials as $material){
            $material->delete();
          }
        }

        if(!empty($materials_names)){

          foreach ($materials_names as $key => $material_name){
          	if(!empty($materials_names[$key])){
		
		          $group_material = new GroupMaterial;
		          $group_material->name = $materials_names[$key];
		          $group_material->link = $materials_links[$key];
		          $group_material->tags = $materials_tags[$key];
		          $group_material->group_id = $group->id;
		
		          $group_material->save();
          		
	          }

            }



        }




      }
    }

    return \Redirect::to('project/'.$project->id)
        ->with('message', [
            'text' => trans('project.finished_and_saved_notification', ['name' => $project->name]),
            'type' => 'finished'
        ])
        ->with('project', $project);

  }


  /**
   * Upload project featured image
   */
  private function uploadFeaturedImage(&$request, $id){

    $featured_image = $request->file('featured_image');

    $destinationPath = 'storage/projects_featured_images/';
    $extension = $featured_image->getClientOriginalExtension();



    $fileName = uniqid('img_'.$id.'_').'.'.$extension;

    $img = Image::make($featured_image);
    // resize the image to a width of 700 and constrain aspect ratio (auto height)
    $img->resize(700, null, function ($constraint) {
      $constraint->aspectRatio();
    });

    $img->save($destinationPath.$fileName);


    return($fileName);
  }



  /**
   * Upload group gallery multiple images
   */
  private function uploadGroupGalleryImage($image, $id){

    if(!File::exists(public_path('storage/projects_groups_images/'.$id))) {
      // path does not exist
      File::makeDirectory(public_path('storage/projects_groups_images/'.$id), 0755, true);
    }


    $destinationPath = 'storage/projects_groups_images/'.$id.'/';



    $extension = $image->getClientOriginalExtension();

    $fileName = uniqid('img_'.$id.'_').'.'.$extension;

    $img = Image::make($image);
    // resize the image to a width of 700 and constrain aspect ratio (auto height)
    $img->resize(700, null, function ($constraint) {
      $constraint->aspectRatio();
    });

    $saved = $img->save($destinationPath.$fileName);


    if($saved){
      return($fileName);
    }else{
      return false;
    }








  }
}
