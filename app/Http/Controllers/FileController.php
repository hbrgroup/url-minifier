<?php

namespace App\Http\Controllers;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{

    /**
    * Show the download page for the file with the given uuid.
    *
    * @param string $uuid
    * @return Response
    */
    public function download_page(string $uuid): Response
    {
        $file = File::where('uuid', $uuid)->first();

        if (!$file) {
            abort(404, 'Ficheiro não encontrado');
        }

        if (count($file->attachments) == 0) {
            abort(404, 'Ficheiro não encontrado');
        }

        /* if ($file->is_expired) {
            abort(404, 'Link expirou');
        } */

        $attachments = array_map(function ($attachment) use ($file) {
            return [
                'name' => $file->attachment_file_names[$attachment],
                'size' => filesize(Storage::path($attachment)),
                'path' => Storage::path($attachment),
                'url'  => Storage::temporaryUrl($attachment, now()->addHours(24)),
            ];
        }, $file->attachments);

        if ($file->link->qr_code) {
            $qr_code = route('links.qrcode', ['slug' => $file->link->slug]);
        } else {
            $qr_code = '';
        }

        return response()->view('pages.files.download', [
            'uuid'          => $file->uuid,
            'qr_code'       => $qr_code,
            'message'       => $file->message,
            'attachments'   => $attachments,
            'is_downloaded' => $file->is_downloaded,
            'is_expired'    => $file->is_expired,
            'expires_at'    => $file->created_at->addDays(21)->diffForHumans(),
            'created_at'    => $file->created_at,
        ]);
    }

    /**
     * Download the file with the given uuid.
     * The file can have multiple attachments, if there are multiple attachments, they will be zipped and downloaded as a single file.
     * If the file has only one attachment, it will be downloaded directly.
     *
     * @param string $uuid
     * @return BinaryFileResponse
     */
    public function download_files(string $uuid): BinaryFileResponse
    {
        $file = File::where('uuid', $uuid)->first();

        if (!$file) {
            abort(404, 'Ficheiro não encontrado');
        }

        if (count($file->attachments) == 0) {
            abort(404, 'Ficheiro não encontrado');
        }

        if (!$file->is_downloaded) {
            $file->is_downloaded = true;
            $file->save();
        }

        $attachments = array_map(function ($attachment) use ($file) {
            return [
                'name' => $file->attachment_file_names[$attachment],
                'size' => filesize(Storage::path($attachment)),
                'path' => Storage::path($attachment),
                'url'  => Storage::temporaryUrl($attachment, now()->addHours(24)),
            ];
        }, $file->attachments);

        if (count($attachments) == 1) {
            return response()->download($attachments[0]['path'], $attachments[0]['name']);
        } else {
            // Zip the files and return the zip file
            $zipFileName = Storage::path("files/" . $file->uuid . '.zip');
            $zip = new \ZipArchive();
            if ($zip->open($zipFileName, \ZipArchive::CREATE) === true) {
                foreach ($attachments as $attachment) {
                    $zip->addFile($attachment['path'], $attachment['name']);
                }
                $zip->close();
            } else {
                abort(500, 'Erro ao criar o ficheiro zip');
            }

            return response()->download($zipFileName, $file->uuid . '.zip')->deleteFileAfterSend(true);
        }
    }

    /**
     * Download a specific file attachment for the file with the given uuid.
     *
     * @param string $uuid
     * @param string $file
     * @return BinaryFileResponse
     */
    public function download_file(string $uuid, string $file): BinaryFileResponse
    {
        $f = File::where('uuid', $uuid)->first();

        if (!$f) {
            abort(404, 'Ficheiro não encontrado');
        }

        if (count($f->attachments) == 0) {
            abort(404, 'Ficheiro não encontrado');
        }

        $attachments = array_map(function ($attachment) use ($f) {
            return [
                'name' => $f->attachment_file_names[$attachment],
                'size' => filesize(Storage::path($attachment)),
                'path' => Storage::path($attachment),
                'url'  => Storage::temporaryUrl($attachment, now()->addHours(24)),
            ];
        }, $f->attachments);

        foreach ($attachments as $attachment) {
            if (str_contains($attachment['path'], $file)) {
                if (!$f->is_downloaded) {
                    $f->is_downloaded = true;
                    $f->save();
                }

                if (!file_exists($attachment['path'])) {
                    abort(404, 'Ficheiro não encontrado');
                }

                return response()->download($attachment['path']);
            }
        }

        abort(404, 'Ficheiro não encontrado');
    }
}
