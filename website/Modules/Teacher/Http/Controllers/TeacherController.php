<?php

namespace Modules\Teacher\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Services\StatsService;
use Modules\Course\Services\CourseDetailService;

class TeacherController extends Controller
{
    private $statsService;
    private $courseService;

    public function __construct(StatsService $statsService, CourseDetailService $courseService)
    {
        $this->statsService = $statsService;
        $this->courseService = $courseService;
    }

    public function dashbaord(Request $request)
    {
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
            'limit' => 10
        ]);
        $result = $this->courseService->getCourses($request);
        if (!$result['status']) {
            return abort($result['responseCode'], $result['message']);
        }
        $top_courses = $result['data'];
        // dd($top_courses);


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
