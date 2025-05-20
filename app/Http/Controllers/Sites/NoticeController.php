<?php

namespace App\Http\Controllers\Sites;

use App\Models\Sites\Site;
use App\Models\Sites\Notice;
use Illuminate\Http\Request;
use App\Services\DateFormatter;
use App\Models\Sites\NoticeType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiteNote;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreSiteNoteRequest;
use App\Http\Resources\NoticeResource;

class NoticeController extends Controller
{

    public function noticeTypes()
    {
        return response()->json([
            'message' => 'success',
            'notice_types' => NoticeType::all()
        ], 200);
    }
     public function notices()
    {
        $notices=Notice::all();
        return response()->json([
            'message' => 'success',
            'notices' => NoticeResource::collection($notices->load(['noticeType','site']))
        ], 200);
    }
    public function create(StoreSiteNoteRequest $request)
    {

        $validated = $request->validated();
        $notice = Notice::create($validated);

        return response()->json([
            'message' => 'success',
            'notice' => $notice
        ], 200);
    }

    public function siteNotices(Request $request)
    {
        $data = [
            'site_code' => $request->query('site_code')
        ];
        $validator = Validator::make($data, [
            'site_code' => ['required', 'exists:sites,site_code']
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        } else {
            $validated = $validator->validated();

            $per_page = $request->query('per_page', 5);
         
            $notices = Notice::with('noticeType')->where('site_code', $validated['site_code'])->paginate($per_page);
        
            foreach ($notices as $notice) {
                $notice->created_at = app(DateFormatter::class)
                    ->formatToUserTimezone($notice->created_at);
            }

            return response()->json([
                "message" => 'success',
                "notices" => $notices
            ], 200);
        }
    }

    public function update(StoreSiteNoteRequest $request)
    {

        $validated = $request->validated();

        $notice = Notice::find($validated['id']);

        $notice->fill([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'notice_type' => $validated['notice_type'],
            'is_solved' => $validated['is_solved']
        ]);
        $notice->save();




        return response()->json([
            "message" => 'success',
            "notices" => $notice
        ], 200);
    }
}
