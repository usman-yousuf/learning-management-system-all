<?php

namespace Modules\Teacher\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Services\StatsService;
use Modules\Course\Services\CourseDetailService;
use Modules\User\Services\ProfileService;

class TeacherController extends Controller
{
    private $profileService;
    private $statsService;
    private $courseService;

    public function __construct(ProfileService $profileService, StatsService $statsService, CourseDetailService $courseService)
    {
        $this->profileService = $profileService;
        $this->statsService = $statsService;
        $this->courseService = $courseService;
    }

    /**
     * dashboard Page for teacher
     *
     * @param Request $request
     * @return void
     */
    public function dashboard(Request $request)
    {
        // validate if request user is actually a teacher
        $request->merge([
            'profile_uuid' => $request->user()->profile->uuid
        ]);
        $result = $this->profileService->checkTeacher($request);
        if (!$result['status']) {
            return view('common::errors.403');
        }
        $currentProfile = $result['data'];

        // get All courses stats
        $result = $this->statsService->getAllCoursesStats($request);
        if(!$result['status']){
            return abort($result['responseCode'], $result['message']);
        }
        $stats = $result['data'];

        // get top 10 courses
        $request->merge([
            'is_top' => 1,
            'offset' => 0,
            'limit' => 10,
            'is_read' => 0
        ]);
        $result = $this->courseService->getCourses($request);
        if (!$result['status']) {
            // return abort($result['responseCode'], $result['message']);
            return view('common::errors.404');
        }
        $top_courses = $result['data'];
        return view('teacher::dashboard', ['stats' => $stats, 'top_courses' => $top_courses]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('teacher::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('teacher::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('teacher::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('teacher::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
