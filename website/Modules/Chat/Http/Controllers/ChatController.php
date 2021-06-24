<?php

namespace Modules\Chat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Chat\Http\Controllers\API\ChatController AS APIChatController;
use Modules\Common\Services\CommonService;
use Modules\User\Services\ProfileService;

class ChatController extends Controller
{
    private $chatController;

    public function __construct(CommonService $commonService, APIChatController $chatController)
    {
        $this->commonService = $commonService;
        $this->chatController = $chatController;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $ctrlObj = $this->chatController;
        $request->merge(['profile_uuid' => $request->user()->profile->uuid]);

        $chattedUsers = $notChattedUsers = [];
        $chattedUsersApiResponse = $ctrlObj->getChattedUserList($request)->getData();
        if ($chattedUsersApiResponse->status) {
            $chats = $chattedUsersApiResponse->data;
        }
        $notChattedUsersApiResponse = $ctrlObj->getNewUsersListToChat($request)->getData();
        if ($notChattedUsersApiResponse->status) {
            $notChattedUsers = $notChattedUsersApiResponse->data;
        }
        // dd($chattedUsers, $notChattedUsers);
        return view('chat::index', ['chats' => $chats, 'newUsers' => $notChattedUsers]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('chat::create');
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
        return view('chat::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('chat::edit');
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
