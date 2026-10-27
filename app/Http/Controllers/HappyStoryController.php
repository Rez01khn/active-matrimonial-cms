<?php

namespace App\Http\Controllers;

use App\Models\HappyStory;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class HappyStoryController extends Controller
{
    protected array $rules = [];
    protected array $messages = [];

    public function __construct()
    {
        $this->middleware(['permission:show_happy_stories'])->only('index');
        $this->middleware(['permission:edit_happy_story'])->only('edit');
        $this->middleware(['permission:view_details_happy_story'])->only('show');

        $this->rules = [
            'partner_name' => ['required', 'max:255'],
            'details'      => ['required'],
        ];

        $this->messages = [
            'partner_name.required' => translate('Partner Name is required'),
            'partner_name.max'      => translate('Max 255 characters'),
            'details.required'      => translate('Story Details is required'),
        ];
    }

    /**
     * Display a listing of the resource in Admin Panel.
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $happy_stories = HappyStory::with(['user.addresses.city', 'user.addresses.country'])->latest();

        if ($request->has('search')) {
            $sort_search = $request->search;
        }
        $happy_stories = $happy_stories->paginate(18);
        return view('admin.happy_stories.index', compact('happy_stories', 'sort_search'));
    }

    /**
     * Show the form for creating/viewing member's happy story.
     */
    public function create()
    {
        return view('frontend.member.happy_story.index');
    }

    /**
     * Store or update a newly created resource in storage from User End.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            flash(translate('Please fill in all required fields'))->error();
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $mediaType = $request->input('media_type', 'photo');

        $story = HappyStory::where('user_id', $user->id)->first();
        if (!$story) {
            $story = new HappyStory();
            $story->approved = 0;
        }
        $story->user_id = $user->id;
        $story->partner_name = trim($request->partner_name);
        $story->title = $request->filled('title')
            ? trim($request->title)
            : trim($user->first_name . ' & ' . $request->partner_name);
        $story->details = $request->details;

        if ($mediaType === 'video') {
            // 1. Direct video file upload
            if ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
                $videoFile = $request->file('video_file');
                $ext = strtolower($videoFile->getClientOriginalExtension());
                $allowedVideo = ['mp4', 'webm', 'mov', 'ogg', 'mkv', 'avi', 'mpeg', 'mpg'];
                if (!in_array($ext, $allowedVideo)) {
                    flash(translate('Please upload a valid video file (MP4, WebM, MOV).'))->error();
                    return Redirect::back()->withInput();
                }

                $path = $videoFile->store('uploads/all', 'local');
                $upload = new Upload();
                $upload->file_original_name = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $upload->file_name = $path;
                $upload->user_id = $user->id;
                $upload->extension = $ext;
                $upload->type = 'video';
                $upload->file_size = $videoFile->getSize();
                $upload->save();

                $story->video_provider = 'upload';
                $story->video_link = uploaded_asset($upload->id);
            } elseif ($request->filled('video_link')) {
                // 2. YouTube or Google Drive link
                $rawLink = trim($request->video_link);
                $story->video_link = $rawLink;
                if (str_contains($rawLink, 'youtube.com') || str_contains($rawLink, 'youtu.be')) {
                    $story->video_provider = 'youtube';
                } elseif (str_contains($rawLink, 'drive.google.com')) {
                    $story->video_provider = 'drive';
                } else {
                    $story->video_provider = 'link';
                }
            } elseif (empty($story->video_link)) {
                flash(translate('Please upload a video file or provide a YouTube / Google Drive link.'))->error();
                return Redirect::back()->withInput();
            }

            if (empty($story->photos) && !empty($user->photo)) {
                $story->photos = (string) $user->photo;
            }
        } else {
            // Photo / Text Story mode
            $story->video_provider = null;
            $story->video_link = null;

            if ($request->hasFile('photo_file') && $request->file('photo_file')->isValid()) {
                $photoId = $this->uploadImageFile($request->file('photo_file'), $user->id);
                if (!$photoId) {
                    flash(translate('Please upload a valid image file (JPG, PNG, WEBP).'))->error();
                    return Redirect::back()->withInput();
                }
                $story->photos = (string) $photoId;
            } elseif ($request->filled('photos')) {
                $story->photos = $request->photos;
            } elseif (empty($story->photos)) {
                flash(translate('Please select a photo to upload.'))->error();
                return Redirect::back()->withInput();
            }
        }

        if ($story->save()) {
            flash(translate('Your Happy Story has been saved successfully!'))->success();
            return redirect()->route('happy_story.member');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Helper to store an uploaded image in the uploads table.
     */
    protected function uploadImageFile($file, int $userId): ?int
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $allowedImage = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowedImage)) {
            return null;
        }

        $path = $file->store('uploads/all', 'local');
        $upload = new Upload();
        $upload->file_original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $upload->file_name = $path;
        $upload->user_id = $userId;
        $upload->extension = $ext;
        $upload->type = 'image';
        $upload->file_size = $file->getSize();
        $upload->save();

        return $upload->id;
    }

    /**
     * Display the specified resource in Admin Panel.
     */
    public function show($id)
    {
        $happy_story = HappyStory::findOrFail(decrypt($id));
        return view('admin.happy_stories.view', compact('happy_story'));
    }

    /**
     * Show the form for editing the specified resource in Admin Panel.
     */
    public function edit($id)
    {
        $happy_story = HappyStory::findOrFail(decrypt($id));
        return view('admin.happy_stories.edit', compact('happy_story'));
    }

    /**
     * Update the specified resource in storage from Admin Panel.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $story = HappyStory::findOrFail($id);
        $story->partner_name = $request->partner_name;
        $story->title = $request->filled('title')
            ? $request->title
            : (($story->user ? $story->user->first_name : '') . ' & ' . $request->partner_name);
        $story->details = $request->details;
        if ($request->filled('photos')) {
            $story->photos = $request->photos;
        }
        $story->video_provider = $request->video_provider;
        $story->video_link = $request->video_link;

        if ($story->save()) {
            flash(translate('Story updated successfully'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function approval_status(Request $request)
    {
        $happy_story = HappyStory::findOrFail($request->id);
        $happy_story->approved = $request->status;
        if ($happy_story->save()) {
            return 1;
        }
        return 0;
    }

    public function destroy($id)
    {
        //
    }
}
