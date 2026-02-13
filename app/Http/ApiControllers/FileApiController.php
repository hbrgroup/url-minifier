<?php

namespace App\Http\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileApiController extends Controller
{

    /**
     * Create a new file and send it to the specified email addresses.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message'  => 'nullable|string',
            'sendTo'   => 'required|string',
        ]);

        if (is_string($data['sendTo'])) {
            $data['sendTo'] = array_map('trim', explode(',', $data['sendTo']));
        }

        if (count($data['sendTo']) > 10) {
            return response()->json([
                'status' => 'error',
                'error' => 'You can only send to a maximum of 10 email addresses'
            ], 422);
        }

        // validate email addresses
        foreach ($data['sendTo'] as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'Invalid email address: ' . $email
                ], 422);
            }
        }

        // Upload files and get their paths
        $attachments = [];
        $attachment_file_names = [];
        foreach ($request->file('attachments', []) as $file) {
            $path = $file->store('files');
            $attachments[] = $path;
            $attachment_file_names[$path] = $file->getClientOriginalName();
        }

        $file = File::create([
            'message' => $data['message'] ?? null,
            'sendTo'  => $data['sendTo'],
            'attachments' => $attachments,
            'attachment_file_names' => $attachment_file_names,
            'user_id' => auth()->id(),
        ]);

        if (!$file) {
            return response()->json([
                'status' => 'error',
                'error' => 'Failed to create file'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'uuid' => $file->uuid,
        ], 201);
    }
}
