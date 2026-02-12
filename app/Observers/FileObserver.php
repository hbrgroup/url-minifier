<?php

namespace App\Observers;

use App\Mail\FileDownloadMailable;
use App\Models\File;
use App\Models\Link;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FileObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the File "created" event.
     */
    public function created(File $file): void
    {
        $slug = Link::createSlug();
        $uuid = (string) Str::uuid();

        $link = new Link();
        $link->slug = $slug;
        $link->url = route('file.page.download', ['uuid' => $uuid]);
        $link->title = 'File download #' . $uuid;
        $link->qr_code = true;
        $link->type_of_link = 'file';
        $link->save();

        $file = File::where('id', $file->id)->first();
        $file->uuid = $uuid;
        $file->link_id = $link->id;
        $file->user_id = auth()->id();
        $file->update();

        foreach ((array)$file->sendTo as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($email)->send(new FileDownloadMailable($link, $file->message));
            }
        }
    }

    /**
     * Handle the File "updated" event.
     */
    public function updated(File $file): void
    {
        //
    }

    /**
     * Handle the File "deleted" event.
     */
    public function deleted(File $file): void
    {
        //
    }

    /**
     * Handle the File "restored" event.
     */
    public function restored(File $file): void
    {
        //
    }

    /**
     * Handle the File "force deleted" event.
     */
    public function forceDeleted(File $file): void
    {
        //
    }
}
