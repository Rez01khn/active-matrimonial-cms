<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryImage;
use App\Models\Member;
use App\Models\User;
use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $sort = $request->get('sort', 'newest');
        
        $query = GalleryImage::where('user_id', $user->id);
        if ($sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        $gallery_images = $query->paginate(24)->appends($request->query());

        $remaining_uploads = get_remaining_package_value($user->id, 'remaining_photo_gallery');
        $uploaded_count = GalleryImage::where('user_id', $user->id)->count();
        $is_profile_photo_set = !empty($user->photo);
        $gallery_privacy = $user->gallery_privacy ?? (get_setting('gallery_image_privacy') ?? 'only_me');

        return view('frontend.member.gallery_image.index', compact(
            'gallery_images',
            'remaining_uploads',
            'uploaded_count',
            'is_profile_photo_set',
            'gallery_privacy',
            'sort'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (package_validity(Auth::user()->id)) {
            if (get_remaining_package_value(Auth::user()->id, 'remaining_photo_gallery') > 0) {
                return redirect()->route('gallery-image_index');
            } else {
                flash(translate('You have 0 Remaining Gallery Photo upload. Please update your package.'))->error();
                return redirect()->route('packages');
            }
        } else {
            flash(translate('Your package has been expired. Please update your package.'))->error();
            return redirect()->route('packages');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!package_validity($user->id)) {
            $msg = translate('Your package has been expired. Please update your package.');
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            flash($msg)->error();
            return redirect()->route('packages');
        }

        $remaining = get_remaining_package_value($user->id, 'remaining_photo_gallery');
        if ($remaining <= 0) {
            $msg = translate('You have 0 Remaining Gallery Photo upload. Please update your package.');
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            flash($msg)->error();
            return back();
        }

        $uploaded_total = 0;

        // 1. Direct file upload (drag & drop or file input)
        if ($request->hasFile('photos')) {
            $files = is_array($request->file('photos')) ? $request->file('photos') : [$request->file('photos')];
            foreach ($files as $file) {
                if ($remaining <= 0) {
                    break;
                }
                if ($file->isValid()) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (in_array($extension, $allowed)) {
                        $path = $file->store('uploads/all', 'local');

                        $upload = new Upload();
                        $upload->file_original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $upload->file_name = $path;
                        $upload->user_id = $user->id;
                        $upload->extension = $extension;
                        $upload->type = 'image';
                        $upload->file_size = $file->getSize();
                        $upload->save();

                        $gallery_image = new GalleryImage();
                        $gallery_image->user_id = $user->id;
                        $gallery_image->image = $upload->id;
                        if ($gallery_image->save()) {
                            $member = Member::where('user_id', $user->id)->first();
                            if ($member) {
                                $member->remaining_photo_gallery = max(0, $member->remaining_photo_gallery - 1);
                                $member->save();
                            }
                            $remaining--;
                            $uploaded_total++;

                            // If user doesn't have a profile photo set yet, set this as primary!
                            if (empty($user->photo)) {
                                $user->photo = $upload->id;
                                $user->save();
                            }
                        }
                    }
                }
            }
        } elseif ($request->hasFile('photo')) {
            $file = $request->file('photo');
            if ($file->isValid()) {
                $extension = strtolower($file->getClientOriginalExtension());
                $path = $file->store('uploads/all', 'local');

                $upload = new Upload();
                $upload->file_original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $upload->file_name = $path;
                $upload->user_id = $user->id;
                $upload->extension = $extension;
                $upload->type = 'image';
                $upload->file_size = $file->getSize();
                $upload->save();

                $gallery_image = new GalleryImage();
                $gallery_image->user_id = $user->id;
                $gallery_image->image = $upload->id;
                if ($gallery_image->save()) {
                    $member = Member::where('user_id', $user->id)->first();
                    if ($member) {
                        $member->remaining_photo_gallery = max(0, $member->remaining_photo_gallery - 1);
                        $member->save();
                    }
                    $uploaded_total++;

                    if (empty($user->photo)) {
                        $user->photo = $upload->id;
                        $user->save();
                    }
                }
            }
        } elseif ($request->filled('gallery_image')) {
            // 2. AIZ Uploader IDs (e.g. "12" or "12,13,14")
            $image_ids = explode(',', $request->gallery_image);
            foreach ($image_ids as $img_id) {
                $img_id = trim($img_id);
                if (!empty($img_id) && $remaining > 0) {
                    $gallery_image = new GalleryImage();
                    $gallery_image->user_id = $user->id;
                    $gallery_image->image = $img_id;
                    if ($gallery_image->save()) {
                        $member = Member::where('user_id', $user->id)->first();
                        if ($member) {
                            $member->remaining_photo_gallery = max(0, $member->remaining_photo_gallery - 1);
                            $member->save();
                        }
                        $remaining--;
                        $uploaded_total++;

                        if (empty($user->photo)) {
                            $user->photo = $img_id;
                            $user->save();
                        }
                    }
                }
            }
        }

        if ($uploaded_total > 0) {
            $msg = $uploaded_total > 1 
                ? translate('Photos uploaded successfully.')
                : translate('Gallery image uploaded successfully.');

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            flash($msg)->success();
            return redirect()->route('gallery-image_index');
        }

        $msg = translate('No valid images were uploaded or remaining quota exceeded.');
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $msg], 422);
        }
        flash($msg)->error();
        return back();
    }

    /**
     * Set a gallery image as the user's primary profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function set_profile_photo(Request $request)
    {
        $request->validate([
            'gallery_image_id' => 'required|integer',
        ]);

        $gallery_image = GalleryImage::where('id', $request->gallery_image_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$gallery_image) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => translate('Image not found.')], 404);
            }
            flash(translate('Image not found.'))->error();
            return back();
        }

        $user = Auth::user();
        $user->photo = $gallery_image->image;
        if (get_setting('profile_picture_approval_by_admin') && $user->user_type == 'member') {
            $user->photo_approved = 0;
        }
        $user->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => translate('Primary profile photo updated successfully!'),
                'image_id' => $gallery_image->image
            ]);
        }

        flash(translate('Primary profile photo updated successfully!'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery_image = GalleryImage::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($gallery_image) {
            $gallery_image->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => translate('Image deleted successfully.')
                ]);
            }

            flash(translate('Image deleted successfully.'))->success();
            return redirect()->route('gallery-image_index');
        }

        if (request()->ajax()) {
            return response()->json(['success' => false, 'message' => translate('Image not found.')], 404);
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Update gallery privacy for authenticated member (only_me vs all).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_privacy(Request $request)
    {
        $user = Auth::user();
        $privacy = ($request->privacy == 'only_me') ? 'only_me' : 'all';
        $user->gallery_privacy = $privacy;
        $user->save();

        return response()->json([
            'success' => true,
            'privacy' => $privacy,
            'label' => ($privacy == 'only_me') ? translate('Visible to approved members') : translate('Visible to all members'),
            'notification_type' => ($privacy == 'only_me') ? 'success' : 'danger',
            'message' => ($privacy == 'only_me') ? translate('Visibility On') : translate('Visibility Off')
        ]);
    }
}
