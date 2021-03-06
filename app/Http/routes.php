<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\Project;
use Illuminate\Http\Request;
use App\Page;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Course;
use App\Group;
use Carbon\Carbon;



Route::group(['middleware' =>['web']], function () {


    Route::group(['middleware' =>['auth']], function () {

        Route::get('profile', 'UserController@index');

        Route::post('profile/update-password', 'UserController@updatePassword');

        Route::post('profile/update-contact-email', 'UserController@updateContactEmail');




        // Teacher section
        Route::group(['middleware' =>['teacher']], function () {

            Route::get('project/new', 'ProjectController@add');

            Route::post('project/new', 'ProjectController@store');


            Route::delete('/project/{id}/delete', function ($id) {
                $project = Project::findOrFail($id);

                $name = $project->name;
                // $project->delete();
                $project->deleted = 1;
                $project->save();

                return redirect('teacher/my-projects')->with('message', trans('project.project_deleted_notification', ['name' => $name]));
            });


            Route::get('/teacher/my-projects', function () {
                $projects = Project::where('deleted', NULL)->whereHas('users', function ($q) {
                    $q->where('participation_role', 'LIKE', '%author%')->where('id', Auth::user()->id);
                })->orderBy('created_at', 'desc')->paginate(5);


                return view('user.teacher.my_projects', [
                    'projects' => $projects]
                );
            });

            Route::post('/project/{project}/unlink/{user}', 'ProjectController@unlinkMember');

            Route::get('/project/{id}/calculate-load', 'ProjectController@getSupervisorsLoadForProject');

            Route::post('api/calculate-load/set', 'ProjectController@setSupervisorsLoadForProject');

            Route::get('/project/{project}/student-answers', 'ProjectController@listStudentAnswers');

        });


        // Teacher can do all these as well
        Route::group(['middleware' =>['project_moderator']], function ($id) {

            Route::get('project/{id}/edit', array('as' => 'project_edit', function ($id) {

                $current_project = Project::find($id);

                if ($current_project->embedded != null) {
                    preg_match('/src="([^"]+)"/', $current_project->embedded, $match);

                    $current_project->embedded = $match[1];
                }

                //Supervisors field
                $teachers = User::select('id','name', 'full_name')->whereHas('roles', function ($q) {
                    $q->where('name', 'oppejoud');
                })->get();

                $authors = $current_project->users()->select('id')->wherePivot('participation_role', 'author')->get();
                $authors_ids = array();
                foreach ($authors as $author) {
                    array_push($authors_ids, $author->id);
                }


                $linked_courses = $current_project->getCourses()->select('id')->get();
                    $linked_courses_ids = array();
                foreach ($linked_courses as $linked_course) {
                    array_push($linked_courses_ids, $linked_course->id);
                }

                $projects = Project::whereHas('users', function ($q) {
                    $q->where('participation_role', 'LIKE', '%author%')->where('id', Auth::user()->id);
                })->get();


                if ($current_project->join_deadline) {
                    $current_project->join_deadline = date("m/d/Y", strtotime($current_project->join_deadline));
                }

                return view('project.edit', compact('teachers','authors_ids', 'current_project', 'projects', 'linked_courses_ids'));

            }));

            Route::post('/project/{id}', 'ProjectController@update');


            Route::post('project/{id}/add-group', 'ProjectController@addNewProjectGroup');


            Route::post('api/group/add-user', 'ProjectController@addUserToGroup');

            Route::post('api/group/rename', 'ProjectController@renameProjectGroup');

            Route::delete('project/{id}/group/delete/{group_id}', 'ProjectController@deleteProjectGroup');


            Route::get('project/{id}/finish', 'ProjectController@finishProject');


            Route::post('project/{id}/finish', 'ProjectController@saveFinishedProject');

            Route::post('project/{id}/finishv2', 'ProjectController@saveFinishedProjectv2');


            Route::post('project/{id}/finish/uploadFiles', 'ProjectController@attachGroupGalleryImages');

            Route::post('project/{id}/finish/uploadPoster', 'ProjectController@attachGroupPresentation');

            Route::post('project/{id}/finish/uploadMaterials', 'ProjectController@attachGroupMaterials');

            Route::post('project/{id}/finish/deleteFile', 'ProjectController@deleteFile');

            Route::post('project/{id}/finish/deletePoster', 'ProjectController@deletePoster');

            Route::post('project/{id}/finish/deleteMaterials', 'ProjectController@deleteMaterial');


            Route::get('project/{id}/api/group-Poster', 'ProjectController@getGroupPoster');

            Route::get('project/{id}/api/group-Materials', 'ProjectController@getGroupMaterials');

        });




        // Admin section
        Route::group(['middleware' => ['admin']], function () {

            Route::get('folders', 'ProjectController@makeFolders');

            Route::get('get-folders', 'ProjectController@getFolderNames');

            Route::get('/admin/users', 'AdminController@index');

            Route::post('/admin/users/{id}/add-admin', 'AdminController@addAdmin');

            Route::post('/admin/users/{id}/remove-admin', 'AdminController@removeAdmin');


            Route::post('/admin/users/{id}/add-teacher', 'AdminController@addTeacher');

            Route::post('/admin/users/{id}/remove-teacher', 'AdminController@removeTeacher');

            Route::post('/admin/users/{id}/add-project-moderator', 'AdminController@addProjectModerator');

            Route::post('/admin/users/{id}/remove-project-moderator', 'AdminController@removeProjectModerator');


            Route::get('/admin/users/search', 'AdminController@search');


            Route::get('/news/edit', 'PageController@editNews');

            Route::post('/news/edit', 'PageController@storeNews');

            Route::get('/faq/edit', 'PageController@editFaq');

            Route::post('/faq/edit', 'PageController@storeFaq');

            Route::get('admin/all-projects/search', 'ProjectController@getAllProjectsSearch');

            Route::get('admin/analytics', 'ProjectController@indexAnalytics');

            Route::get('admin/analytics/search', 'ProjectController@getAdminAnalyticsListing');

            Route::get('admin/analytics/download/open', 'ProjectController@exportAnalyticsToCSVOpenProjects');

            Route::get('admin/analytics/download/ongoing', 'ProjectController@exportAnalyticsToCSVOngoingProjects');

            Route::get('admin/analytics/download/finished', 'ProjectController@exportAnalyticsToCSVFinishedProjects');

            Route::get('admin/analytics/download/students/ongoing', 'ProjectController@exportAnalyticsToCSVStudentsOngoingProjects');

            Route::get('admin/analytics/download/teachers/ongoing/load', 'ProjectController@exportAnalyticsToCSVOngoingProjectsTeachersLoad');

            Route::get('admin/analytics/download/student/responses', 'ProjectController@exportAnalyticsToCSVStudentResponses');

            // Attach user to project api for ajax
            Route::post('api/search/user', 'ProjectController@searchUser');

            Route::post('project/{id}/attach-users', 'ProjectController@attachUsersToProject');


            Route::get('admin/all-projects', function () {

                $projects = Project::where('deleted', NULL)->orderBy('created_at', 'desc')->paginate(10);

                return view('admin.all_projects', [
                    'projects' => $projects]
                );
            });

            Route::delete('admin/all-projects/{id}/delete', function ($id) {
                $project = Project::findOrFail($id);

                $name = $project->name;
                // $project->delete();
                $project->deleted = 1;
                $project->save();

                return redirect('admin/all-projects')->with('message', trans('project.project_deleted_notification', ['name' => $name]));
            });


            Route::get('admin/student-projects', function () {

                $projects = Project::where('submitted_by_student', true)->orderBy('created_at', 'desc')->paginate(10);

                return view('admin.student_projects', [
                    'projects' => $projects]
                );

            });

            // Superadmin section
            Route::group(['middleware' => ['superadmin']], function () {

                //Activity log summary
                Route::get('admin/log', 'AdminController@getActivityLogItems');


                //Update courses
                Route::get('admin/courses/update', function () {
                    return view('admin.update_courses');
                });

                Route::post('admin/courses/update', 'AdminController@updateCourses');

            });

        });


        // Student section
        Route::group(['middleware' => ['student']], function () {

            Route::post('finish/{id}/finish/uploadPoster', 'ProjectController@attachGroupPresentation');

            Route::post('finish/{id}/finish/uploadMaterials', 'ProjectController@attachGroupMaterials');

            Route::post('finish/{id}/finish/deletePoster', 'ProjectController@deletePoster');

            Route::post('finish/{id}/finish/deleteMaterials', 'ProjectController@deleteMaterial');

            // Route::post('midterm/{id}/finish/uploadMidtermMaterials', 'ProjectController@attachMidtermMaterial');
            // Route::post('midterm/{id}/finish/deleteMidtermMaterials', 'ProjectController@deleteMidtermMaterial');
            // Route::get('midterm/{id}', 'ProjectController@midtermMaterials');
            // Route::get('midterm/{id}/api/group-MidtermMaterials', 'ProjectController@getGroupMidtermMaterials');

            Route::get('finish/{id}/api/group-Poster', 'ProjectController@getGroupPoster');

            Route::get('finish/{id}/api/group-Materials', 'ProjectController@getGroupMaterials');

            Route::get('finish/{id}', 'ProjectController@finishProject');

            Route::post('finishv2/{id}', 'ProjectController@saveFinishedProjectv2');




            Route::post('join/{id}', 'ProjectController@joinProject');


            Route::post('leave/{id}', 'ProjectController@leaveProject');


            Route::get('student/project/new', function () {

                /*
                if(\App::getLocale() == 'en'){
                    $courses = Course::select('id','oppekava_eng')->get();
                }else{
                    $courses = Course::select('id','oppekava_est')->get();
                }
                */

                return view('user.student.new_project');
            });

            Route::post('student/project/new', 'ProjectController@storeProjectByStudent');

        });

    });


});



Route::group(['middleware' => ['web']], function () {


    Route::get('/login/tlu', 'SimpleSamlController@redirectToProvider');


    Route::get('/login/choose', function () {

        return view('auth.login_tlu');
    });


    Auth::routes();
    Route::get('/logout', 'Auth\LoginController@logout');


    Route::get('/', 'PageController@index');


    Route::get('/faq', 'PageController@indexFaq');

    Route::get('/calendar', function () {
        return view('page.calendar');
    });


    Route::get('/projects/open', 'ProjectController@indexOpenProjects');

    Route::get('/projects/ongoing', 'ProjectController@indexOngoingProjects');

    Route::get('/projects/finished', 'ProjectController@indexFinishedProjects');


    Route::get('lang/{lang}', ['as'=>'lang.switch', 'uses'=>'LanguageController@switchLang']);


    Route::get('/projects/open/search', 'ProjectController@getOpenProjectsSearch');

    Route::get('/projects/ongoing/search', 'ProjectController@getOngoingProjectsSearch');

    Route::get('/projects/finished/search', 'ProjectController@getFinishedProjectsSearch');


    Route::get('project/{id}', array('as' => 'project', function ($id) {
        
        //Hide project temporarily from public
        if($id == 477 && !Auth::user()){
            return view('errors.404');
        }
        
        $project = Project::find($id);

        if($project){
            if(Auth::user()){
                return view('project.project')
                        ->with('project', $project)
                        ->with('isTeacher', Auth::user()->is('oppejoud'));
            }else{
                return view('project.project')
                        ->with('project', $project)
                        ->with('isTeacher', false);
            }

        }else{
            return view('errors.404');
        }

    }));

    Route::get('oisJoinConfirmation', function (Request $request) {
        return file_get_contents('https://ois.tlu.ee/ois2/ois2.elu_kontroll?oppijaId='.$request->oppijaId.'&ainekood='.$request->ainekood);
    });

    Route::get('getCurrentSemester', function () {
        $year = date('o');
        $month = date('n');
        if ($month > 6) {
            $semester = 'autumn';
        } else {
            $semester = 'spring';
        }
        $full_semester = $year.'_'.$semester;
        return $full_semester;
    });

    Route::get('declarations', function () {

        $declarations = array();
        $course_code = env('COURSE_CODE');
        
        $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->where('deleted', NULL)->orderBy('name', 'asc')->get();
        
        $students_ids = array();

        foreach ($projects as $project){
            $members = $project->users()->select('id')->wherePivot('participation_role', 'member')->get();
            if(count($members)>0){
                foreach ($members as $member){
                    array_push($students_ids, $member->id);
                }

            }

        }

        foreach ($students_ids as $student_id){
            $user = User::find($student_id);
            $project = Project::find($user->isMemberOfProject()['id']);
            $authors = getProjectAuthors($project);
            $cosupervisors = getProjectCosupervisors($project);

            try {
                $tlu_student_id = explode('@', json_decode($user->tlu_student_id, true)[0][0])[0];
            } catch (Exception  $exception) {
                $tlu_student_id = 0;
            }

            $supervisor_id_code = $authors[0]->id_code;
            $supervisor_name = $authors[0]->full_name;
            $split_supervisor_name = explode(' ', $supervisor_name);


            $declaration = array(
                'oppijaId' => $tlu_student_id,
                'ainekood' => $course_code,
                'oppejoudIk' => $supervisor_id_code,
                'oppejoudEesnimi' => $split_supervisor_name[0],
                'oppejoudPerenimi' => end($split_supervisor_name)
            );

            array_push($declarations, $declaration);
        }

        $declarations_json = ['deklaratsioonid' => $declarations];

        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        
        return response()->json($declarations_json, 200, $header, JSON_UNESCAPED_UNICODE);

    });

    Route::get('declarationsInCaseNull', function () {

        $declarations = array();
        $course_code = env('COURSE_CODE');
        
        $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->where('deleted', NULL)->orderBy('name', 'asc')->get();
        
        $students_ids = array();

        foreach ($projects as $project){
            $members = $project->users()->select('id')->wherePivot('participation_role', 'member')->get();
            if(count($members)>0){
                foreach ($members as $member){
                    array_push($students_ids, $member->id);
                }

            }

        }

        foreach ($students_ids as $student_id){
            $user = User::find($student_id);
            $project = Project::find($user->isMemberOfProject()['id']);
            $authors = getProjectAuthors($project);
            $cosupervisors = getProjectCosupervisors($project);

            try {
                $tlu_student_id = explode('@', json_decode($user->tlu_student_id, true)[0][0])[0];
            } catch (Exception  $exception) {
                $tlu_student_id = 0;
            }

            $supervisor_id_code = $authors[0]->id_code;
            $supervisor_name = $authors[0]->full_name;
            $split_supervisor_name = explode(' ', $supervisor_name);


            $declaration = array(
                'oppijaId' => $tlu_student_id,
                'ainekood' => $course_code,
                'oppejoudIk' => $supervisor_id_code,
                'oppejoudEesnimi' => $split_supervisor_name[0],
                'oppejoudPerenimi' => end($split_supervisor_name)
            );

            if ($tlu_student_id == null || $course_code == null || $supervisor_id_code == null || $supervisor_name == null) {
                array_push($declarations, $declaration);
            }
        }

        $declarations_json = ['deklaratsioonid' => $declarations];

        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        
        return response()->json($declarations_json, 200, $header, JSON_UNESCAPED_UNICODE);

    });

    Route::get('declarationsInCaseNoNull', function () {

        $declarations = array();
        $course_code = env('COURSE_CODE');
        
        $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->where('deleted', NULL)->orderBy('name', 'asc')->get();
        
        $students_ids = array();

        foreach ($projects as $project){
            $members = $project->users()->select('id')->wherePivot('participation_role', 'member')->get();
            if(count($members)>0){
                foreach ($members as $member){
                    array_push($students_ids, $member->id);
                }

            }

        }

        foreach ($students_ids as $student_id){
            $user = User::find($student_id);
            $project = Project::find($user->isMemberOfProject()['id']);
            $authors = getProjectAuthors($project);
            $cosupervisors = getProjectCosupervisors($project);

            try {
                $tlu_student_id = explode('@', json_decode($user->tlu_student_id, true)[0][0])[0];
            } catch (Exception  $exception) {
                $tlu_student_id = 0;
            }

            $supervisor_id_code = $authors[0]->id_code;
            $supervisor_name = $authors[0]->full_name;
            $split_supervisor_name = explode(' ', $supervisor_name);


            $declaration = array(
                'oppijaId' => $tlu_student_id,
                'ainekood' => $course_code,
                'oppejoudIk' => $supervisor_id_code,
                'oppejoudEesnimi' => $split_supervisor_name[0],
                'oppejoudPerenimi' => end($split_supervisor_name)
            );

            if ($tlu_student_id != null && $course_code != null && $supervisor_id_code != null && $supervisor_name != null) {
                array_push($declarations, $declaration);
            }
        }

        $declarations_json = ['deklaratsioonid' => $declarations];

        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        
        return response()->json($declarations_json, 200, $header, JSON_UNESCAPED_UNICODE);

    });

    Route::get('declarationsWithCsvData', function () {

        $declarations = array();
        $course_code = env('COURSE_CODE');
        
        $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->where('deleted', NULL)->orderBy('name', 'asc')->get();
        
        $students_ids = array();

        foreach ($projects as $project){
            $members = $project->users()->select('id')->wherePivot('participation_role', 'member')->get();
            if(count($members)>0){
                foreach ($members as $member){
                    array_push($students_ids, $member->id);
                }

            }

        }

        foreach ($students_ids as $student_id){
            $user = User::find($student_id);
            $project = Project::find($user->isMemberOfProject()['id']);
            $authors = getProjectAuthors($project);
            $cosupervisors = getProjectCosupervisors($project);

            $author_names_array = array();
            $cosupervisor_names_array = array();

            foreach ($authors as $author) {
                if ($author) {
                    array_push($author_names_array, $author->full_name);
                }
            }
            /*
            foreach ($cosupervisors as $cosupervisor) {
                if ($cosupervisor) {
                    array_push($cosupervisor_names_array, $cosupervisor->full_name);
                }
            }
            */
            try {
                $tlu_student_id = explode('@', json_decode($user->tlu_student_id, true)[0][0])[0];
            } catch (Exception  $exception) {
                $tlu_student_id = 0;
            }

            $supervisor_id_code = $authors[0]->id_code;
            $supervisor_name = $authors[0]->full_name;
            $split_supervisor_name = explode(' ', $supervisor_name);


            $declaration = array(
                'oppijaId' => $tlu_student_id,
                'ainekood' => $course_code,
                'oppejoudIk' => $supervisor_id_code,
                'oppejoudEesnimi' => $split_supervisor_name[0],
                'oppejoudPerenimi' => end($split_supervisor_name),
                'eesJaPerekonnanimi' => getUserName($user),
                'email' => $user->email,
                'kursus' => getUserCourse($user),
                'projektiNimi' => $project->name,
                'projektiId' => $project->id,
                'juhendajad' => $author_names_array,
                'kaasjuhendajad' => $cosupervisors
            );

            array_push($declarations, $declaration);
        }

        $declarations_json = ['deklaratsioonid' => $declarations];
        
        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        
        return response()->json($declarations_json, 200, $header, JSON_UNESCAPED_UNICODE);

    });

    Route::get('declarationsWithCsvDataInCaseNull', function () {

        $declarations = array();
        $course_code = env('COURSE_CODE');
        
        $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->where('deleted', NULL)->orderBy('name', 'asc')->get();
        
        $students_ids = array();

        foreach ($projects as $project){
            $members = $project->users()->select('id')->wherePivot('participation_role', 'member')->get();
            if(count($members)>0){
                foreach ($members as $member){
                    array_push($students_ids, $member->id);
                }

            }

        }

        foreach ($students_ids as $student_id){
            $user = User::find($student_id);
            $project = Project::find($user->isMemberOfProject()['id']);
            $authors = getProjectAuthors($project);
            $cosupervisors = getProjectCosupervisors($project);

            $author_names_array = array();
            $cosupervisor_names_array = array();

            foreach ($authors as $author) {
                if ($author) {
                    array_push($author_names_array, $author->full_name);
                }
            }
            /*
            foreach ($cosupervisors as $cosupervisor) {
                if ($cosupervisor) {
                    array_push($cosupervisor_names_array, $cosupervisor->full_name);
                }
            }
            */
            try {
                $tlu_student_id = explode('@', json_decode($user->tlu_student_id, true)[0][0])[0];
            } catch (Exception  $exception) {
                $tlu_student_id = 0;
            }

            $supervisor_id_code = $authors[0]->id_code;
            $supervisor_name = $authors[0]->full_name;
            $split_supervisor_name = explode(' ', $supervisor_name);


            $declaration = array(
                'oppijaId' => $tlu_student_id,
                'ainekood' => $course_code,
                'oppejoudIk' => $supervisor_id_code,
                'oppejoudEesnimi' => $split_supervisor_name[0],
                'oppejoudPerenimi' => end($split_supervisor_name),
                'eesJaPerekonnanimi' => getUserName($user),
                'email' => $user->email,
                'kursus' => getUserCourse($user),
                'projektiNimi' => $project->name,
                'projektiId' => $project->id,
                'juhendajad' => $author_names_array,
                'kaasjuhendajad' => $cosupervisors
            );

            if ($tlu_student_id == null || $course_code == null || $supervisor_id_code == null || $supervisor_name == null) {
                array_push($declarations, $declaration);
            }

        }

        $declarations_json = ['deklaratsioonid' => $declarations];
        
        $header = array (
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'
        );
        
        return response()->json($declarations_json, 200, $header, JSON_UNESCAPED_UNICODE);

    });

    Route::get('declarationsAsCsv', function () {

        $course_code = env('COURSE_CODE');

        $headers = array(
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=elu_students_ongoing.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
            "charset" => "utf-8"
        );

        $columns = array(trans('auth.name'), trans('auth.email'), trans('project.course'), trans('project.project'), trans('project.supervisor'), trans('project.cosupervisor'), 'oppojaId', 'Tehtud');
        
        $projects = Project::where('publishing_status', 1)->where('status', '=', '1')->where('join_deadline', '<', Carbon::today()->format('Y-m-d'))->where('deleted', NULL)->orderBy('name', 'asc')->get();
        
        $students_ids = array();

        foreach ($projects as $project){
            $members = $project->users()->select('id')->wherePivot('participation_role', 'member')->get();
            if(count($members)>0){
                foreach ($members as $member){
                    array_push($students_ids, $member->id);
                }

            }

        }

        $callback = function() use ($students_ids, $columns, $course_code) {

			$handle = fopen('php://output', 'w');
			fputcsv($handle, $columns);

            foreach ($students_ids as $student_id){
                $user = User::find($student_id);
                $project = Project::find($user->isMemberOfProject()['id']);
                $authors = getProjectAuthors($project);
                $cosupervisors = getProjectCosupervisors($project);

                $author_names_array = array();

                foreach ($authors as $author) {
                    if ($author) {
                        array_push($author_names_array, $author->full_name);
                    }
                }
                /*
                foreach ($cosupervisors as $cosupervisor) {
                    if ($cosupervisor) {
                        array_push($cosupervisor_names_array, $cosupervisor->full_name);
                    }
                }
                */
                try {
                    $tlu_student_id = explode('@', json_decode($user->tlu_student_id, true)[0][0])[0];
                } catch (Exception  $exception) {
                    $tlu_student_id = 0;
                }

                $supervisor_id_code = $authors[0]->id_code;
                $supervisor_name = $authors[0]->full_name;


                if ($tlu_student_id == null || $course_code == null || $supervisor_id_code == null || $supervisor_name == null) {
                    $isDone = '';
                } else {
                    $isDone = 'TEHTUD';
                }

                fputcsv($handle, array(getUserName($user), $user->email, getUserCourse($user), $user->isMemberOfProject()['name'], arrayToImplodeString($author_names_array), arrayToImplodeString($cosupervisors), $tlu_student_id, $isDone), ',');
            }

        };
        
        return Response::stream($callback, 200, $headers);

    });


    /*
    ===============================================
    404 ===========================================
    ===============================================
    */

    /*
    App::missing(function($exception)
    {

      // shows an error page (app/views/error.blade.php)
      // returns a page not found error
      return Response::view('error', array(), 404);
    });
    */


});
